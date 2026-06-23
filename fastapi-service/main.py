import io
import os
from typing import Dict, Any

import torch
from fastapi import FastAPI, UploadFile, File
from fastapi.middleware.cors import CORSMiddleware
from PIL import Image
from transformers import AutoImageProcessor, AutoModelForImageClassification

APP_NAME = "RetinaCare AI FastAPI Service"
MODEL_ID = os.getenv("HF_MODEL_ID", "eliasteikari/retinal_disease_model")
MODEL_VERSION = os.getenv("MODEL_VERSION", "1.0")

app = FastAPI(
    title=APP_NAME,
    description="AI service for retinal fundus image classification.",
    version=MODEL_VERSION,
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

processor = None
model = None
model_error = None

try:
    processor = AutoImageProcessor.from_pretrained(MODEL_ID)
    model = AutoModelForImageClassification.from_pretrained(MODEL_ID)
    model.eval()
    print(f"Model loaded successfully: {MODEL_ID}")
except Exception as exc:
    model_error = str(exc)
    print("Model could not be loaded. Placeholder mode is active.")
    print(model_error)


def placeholder_prediction() -> Dict[str, Any]:
    return {
        "predicted_class": "Normal",
        "confidence": 0.87,
        "risk_level": "low",
        "probabilities": {
            "Normal": 0.87,
            "Diabetic Retinopathy": 0.08,
            "Glaucoma": 0.03,
            "Cataract": 0.02,
        },
        "recommendation": "Placeholder result. Add or fix the AI model before using real predictions.",
        "model_name": "Placeholder Retina Model",
        "model_version": MODEL_VERSION,
        "model_status": "placeholder",
        "model_error": model_error,
    }


@app.get("/")
def home():
    return {
        "message": "RetinaCare AI FastAPI service is running",
        "model_id": MODEL_ID,
        "model_loaded": model is not None,
    }


@app.get("/health")
def health():
    return {
        "status": "ok",
        "model_loaded": model is not None,
        "model_id": MODEL_ID,
    }


@app.get("/model-info")
def model_info():
    return {
        "model_id": MODEL_ID,
        "model_version": MODEL_VERSION,
        "model_loaded": model is not None,
        "model_error": model_error,
    }


@app.post("/predict")
async def predict(file: UploadFile = File(...)):
    image_bytes = await file.read()
    image = Image.open(io.BytesIO(image_bytes)).convert("RGB")

    if model is None or processor is None:
        return placeholder_prediction()

    inputs = processor(images=image, return_tensors="pt")

    with torch.no_grad():
        outputs = model(**inputs)
        probabilities_tensor = torch.nn.functional.softmax(outputs.logits, dim=-1)[0]

    predicted_index = int(torch.argmax(probabilities_tensor).item())
    confidence = float(probabilities_tensor[predicted_index].item())

    id2label = model.config.id2label
    predicted_class = id2label.get(predicted_index, str(predicted_index))

    probabilities = {
        id2label.get(i, str(i)): float(probabilities_tensor[i].item())
        for i in range(len(probabilities_tensor))
    }

    return {
        "predicted_class": predicted_class,
        "confidence": confidence,
        "probabilities": probabilities,
        "recommendation": "هذه نتيجة مساعدة فقط ويجب مراجعتها من طبيب عيون مختص.",
        "model_name": MODEL_ID,
        "model_version": MODEL_VERSION,
        "model_status": "loaded",
    }
