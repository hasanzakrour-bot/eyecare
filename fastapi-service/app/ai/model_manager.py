# -*- coding: utf-8 -*-
"""
ModelManager for a multi-model retinal disease screening service.

Why this file exists:
- A single public model rarely covers all target retinal diseases reliably.
- This manager can run several models, map their labels to a common disease catalog,
  and merge their scores using a weighted ensemble.
- Models can be Hugging Face Transformers, remote HTTP endpoints, or local Keras models.

Returned API remains compatible with Laravel:
- predicted_class
- confidence
- probabilities
- recommendation
"""

from __future__ import annotations

import io
import os
import time
from dataclasses import dataclass, field
from typing import Any, Dict, List, Optional, Tuple

import numpy as np
import requests
from PIL import Image, ImageStat

from .disease_catalog import TARGET_DISEASES, RISK_PRIORITY


@dataclass
class ModelSpec:
    name: str
    kind: str
    weight: float = 1.0
    enabled: bool = True
    repo_id: Optional[str] = None
    local_path: Optional[str] = None
    endpoint: Optional[str] = None
    labels: List[str] = field(default_factory=list)
    timeout: int = 90
    notes: str = ""


DEFAULT_MODEL_SPECS: List[ModelSpec] = [
    # A public multi-label retinal disease model. It may cover many RFMiD-style diseases.
    # Keep it enabled if your internet connection can download Hugging Face models.
    ModelSpec(
        name="rfmid_multidisease_hf",
        kind="hf_transformers",
        repo_id="lebiraja/retinal-disease-classifier",
        weight=1.20,
        enabled=True,
        notes="Multi-label retinal disease classifier. Main broad coverage model.",
    ),
    # Backup public classifier. It may not cover all target diseases, but it helps with common labels.
    ModelSpec(
        name="retinal_disease_vit_backup",
        kind="hf_transformers",
        repo_id="eliasteikari/retinal_disease_model",
        weight=0.70,
        enabled=True,
        notes="Backup image-classification model. Used only for labels that can be mapped.",
    ),
    # Example placeholder for a DR severity model. Set enabled=True after adding a real model.
    ModelSpec(
        name="dr_severity_model",
        kind="disabled_placeholder",
        weight=1.00,
        enabled=False,
        labels=["mild", "moderate", "severe", "proliferative"],
        notes="Optional model for DR severity staging.",
    ),
]


def _normalize_text(value: str) -> str:
    return value.strip().lower().replace("_", " ").replace("-", " ")


def _match_canonical_disease(label: str) -> Optional[str]:
    normalized = _normalize_text(label)

    for disease_key, disease_info in TARGET_DISEASES.items():
        for alias in disease_info["aliases"]:
            alias_norm = _normalize_text(alias)
            if normalized == alias_norm or alias_norm in normalized:
                return disease_key

    return None


def _risk_level_from_probabilities(probabilities: Dict[str, float]) -> str:
    best_risk = "low"
    best_priority = 1

    for disease_key, probability in probabilities.items():
        if probability < 0.35:
            continue
        risk = TARGET_DISEASES[disease_key]["risk"]
        priority = RISK_PRIORITY.get(risk, 1)
        if priority > best_priority:
            best_priority = priority
            best_risk = risk

    return best_risk


class BaseModelRunner:
    def __init__(self, spec: ModelSpec):
        self.spec = spec
        self.loaded = False
        self.error: Optional[str] = None

    def load(self) -> None:
        self.loaded = True

    def predict(self, image: Image.Image) -> Dict[str, float]:
        raise NotImplementedError

    def status(self) -> Dict[str, Any]:
        return {
            "name": self.spec.name,
            "kind": self.spec.kind,
            "enabled": self.spec.enabled,
            "loaded": self.loaded,
            "error": self.error,
            "weight": self.spec.weight,
            "notes": self.spec.notes,
        }


class HFTransformersRunner(BaseModelRunner):
    def __init__(self, spec: ModelSpec):
        super().__init__(spec)
        self.processor = None
        self.model = None
        self.torch = None

    def load(self) -> None:
        if self.loaded:
            return

        try:
            import torch
            from transformers import AutoImageProcessor, AutoModelForImageClassification

            model_id = self.spec.local_path or self.spec.repo_id
            if not model_id:
                raise ValueError("HF model requires repo_id or local_path.")

            self.processor = AutoImageProcessor.from_pretrained(model_id)
            self.model = AutoModelForImageClassification.from_pretrained(model_id)
            self.model.eval()
            self.torch = torch
            self.loaded = True
            self.error = None
        except Exception as exc:
            self.loaded = False
            self.error = str(exc)

    def predict(self, image: Image.Image) -> Dict[str, float]:
        self.load()
        if not self.loaded or self.processor is None or self.model is None or self.torch is None:
            return {}

        inputs = self.processor(images=image, return_tensors="pt")

        with self.torch.no_grad():
            outputs = self.model(**inputs)
            logits = outputs.logits[0]

            problem_type = getattr(self.model.config, "problem_type", None) or ""
            if "multi_label" in problem_type.lower():
                scores = self.torch.sigmoid(logits)
            else:
                scores = self.torch.nn.functional.softmax(logits, dim=-1)

        id2label = getattr(self.model.config, "id2label", {}) or {}
        raw_scores: Dict[str, float] = {}

        for idx, score in enumerate(scores):
            label = id2label.get(idx, str(idx))
            raw_scores[label] = float(score.item())

        return raw_scores


class RemoteHTTPRunner(BaseModelRunner):
    def predict(self, image: Image.Image) -> Dict[str, float]:
        if not self.spec.endpoint:
            self.error = "Remote model endpoint is missing."
            return {}

        buffer = io.BytesIO()
        image.save(buffer, format="PNG")
        buffer.seek(0)

        try:
            response = requests.post(
                self.spec.endpoint,
                files={"file": ("retina.png", buffer.getvalue(), "image/png")},
                timeout=self.spec.timeout,
            )
            response.raise_for_status()
            data = response.json()
            self.loaded = True
            self.error = None
            return data.get("probabilities", data.get("scores", {}))
        except Exception as exc:
            self.error = str(exc)
            return {}


class LocalKerasRunner(BaseModelRunner):
    def __init__(self, spec: ModelSpec):
        super().__init__(spec)
        self.model = None

    def load(self) -> None:
        if self.loaded:
            return

        try:
            if not self.spec.local_path:
                raise ValueError("Keras model requires local_path.")
            import tensorflow as tf

            self.model = tf.keras.models.load_model(self.spec.local_path)
            self.loaded = True
            self.error = None
        except Exception as exc:
            self.loaded = False
            self.error = str(exc)

    def predict(self, image: Image.Image) -> Dict[str, float]:
        self.load()
        if not self.loaded or self.model is None:
            return {}

        image_resized = image.resize((224, 224))
        array = np.array(image_resized).astype("float32") / 255.0
        array = np.expand_dims(array, axis=0)
        predictions = self.model.predict(array, verbose=0)[0]

        labels = self.spec.labels or [str(i) for i in range(len(predictions))]
        return {labels[i]: float(predictions[i]) for i in range(min(len(labels), len(predictions)))}


def _make_runner(spec: ModelSpec) -> BaseModelRunner:
    if spec.kind == "hf_transformers":
        return HFTransformersRunner(spec)
    if spec.kind == "remote_http":
        return RemoteHTTPRunner(spec)
    if spec.kind == "keras":
        return LocalKerasRunner(spec)
    return BaseModelRunner(spec)


class ModelManager:
    def __init__(self, specs: Optional[List[ModelSpec]] = None):
        self.specs = specs or DEFAULT_MODEL_SPECS
        self.runners: List[BaseModelRunner] = [
            _make_runner(spec) for spec in self.specs if spec.enabled
        ]

    def health(self) -> Dict[str, Any]:
        active = 0
        for runner in self.runners:
            runner.load()
            if runner.loaded:
                active += 1

        return {
            "status": "connected" if active > 0 else "degraded",
            "active_models": active,
            "total_enabled_models": len(self.runners),
            "models": [runner.status() for runner in self.runners],
            "supported_diseases": self.supported_diseases(),
        }

    def supported_diseases(self) -> List[Dict[str, str]]:
        return [
            {
                "key": key,
                "name_en": info["en"],
                "name_ar": info["ar"],
                "risk": info["risk"],
            }
            for key, info in TARGET_DISEASES.items()
        ]

    def image_quality(self, image: Image.Image) -> Dict[str, Any]:
        width, height = image.size
        grayscale = image.convert("L")
        stat = ImageStat.Stat(grayscale)
        brightness = float(stat.mean[0])
        contrast = float(stat.stddev[0])

        warnings = []
        if width < 300 or height < 300:
            warnings.append("image_resolution_is_low")
        if brightness < 35:
            warnings.append("image_is_too_dark")
        if brightness > 230:
            warnings.append("image_is_too_bright")
        if contrast < 20:
            warnings.append("image_contrast_is_low")

        return {
            "width": width,
            "height": height,
            "brightness": round(brightness, 2),
            "contrast": round(contrast, 2),
            "warnings": warnings,
            "accepted": len(warnings) == 0,
        }

    def predict(self, image: Image.Image) -> Dict[str, Any]:
        started = time.time()
        image = image.convert("RGB")
        quality = self.image_quality(image)

        score_sum = {key: 0.0 for key in TARGET_DISEASES.keys()}
        weight_sum = {key: 0.0 for key in TARGET_DISEASES.keys()}
        raw_model_outputs: List[Dict[str, Any]] = []

        for runner in self.runners:
            raw_scores = runner.predict(image)
            mapped_scores: Dict[str, float] = {}

            for raw_label, raw_score in raw_scores.items():
                disease_key = _match_canonical_disease(raw_label)
                if disease_key is None:
                    continue

                score = float(max(0.0, min(1.0, raw_score)))
                if score > mapped_scores.get(disease_key, 0.0):
                    mapped_scores[disease_key] = score

            for disease_key, score in mapped_scores.items():
                score_sum[disease_key] += score * runner.spec.weight
                weight_sum[disease_key] += runner.spec.weight

            raw_model_outputs.append({
                "model": runner.spec.name,
                "kind": runner.spec.kind,
                "loaded": runner.loaded,
                "error": runner.error,
                "mapped_scores": mapped_scores,
                "raw_top_labels": sorted(raw_scores.items(), key=lambda item: item[1], reverse=True)[:10],
            })

        probabilities = {}
        for disease_key in TARGET_DISEASES.keys():
            if weight_sum[disease_key] > 0:
                probabilities[disease_key] = score_sum[disease_key] / weight_sum[disease_key]
            else:
                probabilities[disease_key] = 0.0

        # If no usable model produced mapped labels, return a safe unknown result.
        if max(probabilities.values()) <= 0:
            return {
                "predicted_class": "غير محدد",
                "predicted_class_en": "Unknown",
                "confidence": 0.0,
                "risk_level": "unknown",
                "probabilities": {
                    TARGET_DISEASES[key]["en"]: 0.0 for key in TARGET_DISEASES.keys()
                },
                "probabilities_ar": {
                    TARGET_DISEASES[key]["ar"]: 0.0 for key in TARGET_DISEASES.keys()
                },
                "canonical_probabilities": probabilities,
                "quality": quality,
                "model_outputs": raw_model_outputs,
                "supported_diseases": self.supported_diseases(),
                "recommendation": "تعذر الحصول على نتيجة موثوقة من النماذج المتاحة. تأكد من تشغيل النماذج أو إضافة نموذج محلي مناسب.",
                "elapsed_ms": int((time.time() - started) * 1000),
            }

        best_key = max(probabilities, key=probabilities.get)
        confidence = float(probabilities[best_key])
        risk_level = _risk_level_from_probabilities(probabilities)

        # DR severity is intentionally separated. Do not invent a clinical stage without a real staging model.
        dr_stage = None
        if probabilities.get("diabetic_retinopathy", 0.0) >= 0.35:
            dr_stage = {
                "available": False,
                "message": "يتطلب تصنيف شدة اعتلال الشبكية السكري نموذجاً متخصصاً منفصلاً.",
            }

        recommendation = self._recommendation(best_key, confidence, risk_level, quality)

        return {
            "predicted_class": TARGET_DISEASES[best_key]["ar"],
            "predicted_class_en": TARGET_DISEASES[best_key]["en"],
            "confidence": confidence,
            "risk_level": risk_level,
            "probabilities": {
                TARGET_DISEASES[key]["en"]: float(value)
                for key, value in probabilities.items()
            },
            "probabilities_ar": {
                TARGET_DISEASES[key]["ar"]: float(value)
                for key, value in probabilities.items()
            },
            "canonical_probabilities": probabilities,
            "dr_stage": dr_stage,
            "quality": quality,
            "model_outputs": raw_model_outputs,
            "supported_diseases": self.supported_diseases(),
            "recommendation": recommendation,
            "elapsed_ms": int((time.time() - started) * 1000),
        }

    def _recommendation(self, best_key: str, confidence: float, risk_level: str, quality: Dict[str, Any]) -> str:
        if quality["warnings"]:
            return "جودة الصورة قد تؤثر على النتيجة. يفضل إعادة رفع صورة أوضح ثم مراجعة الطبيب المختص."

        if risk_level in ["critical", "high"]:
            return "تم رصد مؤشر خطورة مرتفع. ينصح بمراجعة اختصاصي عيون بشكل عاجل وعدم اعتبار النتيجة تشخيصاً نهائياً."

        if confidence < 0.45:
            return "الثقة منخفضة نسبياً. يفضل إعادة الفحص بصورة أوضح أو الاعتماد على مراجعة الطبيب."

        if best_key == "healthy":
            return "لا تظهر مؤشرات مرضية واضحة حسب النماذج المتاحة، مع ضرورة الاعتماد على الفحص السريري عند وجود أعراض."

        return "توجد مؤشرات تحتاج إلى مراجعة اختصاصي عيون. هذه النتيجة أداة مساعدة وليست تشخيصاً نهائياً."
