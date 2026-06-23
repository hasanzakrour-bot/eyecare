# قسم الذكاء الاصطناعي المتعدد النماذج RetinaCare AI

هذه الملفات تضيف طبقة `ModelManager` إلى FastAPI بحيث يمكن تشغيل أكثر من نموذج وتوحيد نتائجها داخل تصنيفات مرضية ثابتة.

## لماذا نستخدم أكثر من نموذج؟

لأن أغلب النماذج العامة لا تغطي كل الأمراض المطلوبة في مشروع الفحص الشبكي. لذلك الحل العملي هو:

1. نموذج واسع متعدد الأمراض إن وجد.
2. نموذج احتياطي للأمراض الشائعة.
3. نموذج متخصص لاحقاً لشدة اعتلال الشبكية السكري.
4. توحيد المخرجات في قائمة أمراض ثابتة قبل إرسالها إلى Laravel.

## الأمراض المدعومة في الكتالوج

- Healthy / سليم
- Diabetic Retinopathy / اعتلال الشبكية السكري
- CSR / اعتلال الشبكية المصلي المركزي
- Optic Disc Edema / وذمة القرص البصري
- Glaucoma / الجلوكوما
- Macular Scar / ندبة البقعة
- Pathological Myopia / قصر النظر الشديد
- Retinal Detachment / انفصال الشبكية
- Retinitis Pigmentosa / التهاب الشبكية الصباغي

## طريقة التركيب

انسخ مجلد `fastapi-service` فوق مجلد FastAPI الحالي، أو انسخ الملفات التالية فقط:

```text
main.py
requirements.txt
app/ai/disease_catalog.py
app/ai/model_manager.py
app/__init__.py
app/ai/__init__.py
```

ثم شغل:

```cmd
cd /d "F:\مجلد جديد\fastapi-service"
venv\Scripts\activate.bat
pip install -r requirements.txt
uvicorn main:app --host 127.0.0.1 --port 8001 --reload
```

## إضافة نموذج جديد

افتح:

```text
app/ai/model_manager.py
```

ثم أضف ModelSpec جديد داخل `DEFAULT_MODEL_SPECS`:

```python
ModelSpec(
    name="my_glaucoma_model",
    kind="hf_transformers",
    repo_id="username/model-name",
    weight=1.0,
    enabled=True,
)
```

إذا كان لديك نموذج Keras محلي:

```python
ModelSpec(
    name="local_keras_model",
    kind="keras",
    local_path="model/my_model.keras",
    labels=["Healthy", "Glaucoma", "Diabetic Retinopathy"],
    weight=1.0,
    enabled=True,
)
```

## شكل الرد إلى Laravel

```json
{
  "predicted_class": "الجلوكوما",
  "predicted_class_en": "Glaucoma",
  "confidence": 0.78,
  "risk_level": "high",
  "probabilities": {
    "Healthy": 0.05,
    "Diabetic Retinopathy": 0.12,
    "Glaucoma": 0.78
  },
  "recommendation": "..."
}
```

هذا الرد متوافق مع Laravel الحالي لأنه ما زال يحتوي على `predicted_class`, `confidence`, `probabilities`, و `recommendation`.

## ملاحظة مهمة

هذه البنية تجعل المشروع قابلاً للتوسع، لكنها لا تجعل النتيجة تشخيصاً طبياً نهائياً. يجب عرض النتائج دائماً كأداة دعم قرار ومراجعة الطبيب المختص.
