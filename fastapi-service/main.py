# -*- coding: utf-8 -*-
from fastapi import FastAPI, UploadFile, File, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from PIL import Image
import io

from app.ai.model_manager import ModelManager

app = FastAPI(title="RetinaCare AI Multi-Model Service")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

model_manager = ModelManager()


@app.get("/")
def home():
    return {
        "message": "RetinaCare AI service is running",
        "service": "multi-model retinal disease screening",
    }


@app.get("/health")
def health():
    return model_manager.health()


@app.get("/model-info")
def model_info():
    return {
        "strategy": "weighted multi-model ensemble with canonical disease mapping",
        "supported_diseases": model_manager.supported_diseases(),
        "health": model_manager.health(),
    }


@app.post("/predict")
async def predict(file: UploadFile = File(...)):
    if not file.content_type or not file.content_type.startswith("image/"):
        raise HTTPException(status_code=422, detail="Please upload a valid image file.")

    try:
        image_bytes = await file.read()
        image = Image.open(io.BytesIO(image_bytes)).convert("RGB")
    except Exception:
        raise HTTPException(status_code=422, detail="Could not read the uploaded image.")

    return model_manager.predict(image)
