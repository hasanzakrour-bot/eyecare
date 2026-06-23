# RetinaCare AI - نسخة مشروع تخرج Laravel 10

هذه الحزمة تحول مشروعك من نموذج ابتدائي إلى مشروع تخرج أقرب لموقع حقيقي:

- واجهة Landing Page احترافية.
- Login/Register بتصميم جديد.
- Dashboard بإحصائيات وحالة FastAPI.
- إدارة مرضى كاملة CRUD.
- سجل تشخيصات وفلاتر بحث.
- صفحة رفع صورة شبكية.
- تقرير تشخيص جميل وقابل للطباعة.
- مراجعة الطبيب بعد نتيجة الذكاء الاصطناعي.
- FastAPI جاهز مع Hugging Face model أو Placeholder عند فشل تحميل الموديل.

---

## 1) نسخ ملفات Laravel

فك الضغط عن الحزمة، ثم من PowerShell انسخ ملفات الترقية فوق مشروعك الحالي:

```powershell
Copy-Item -Path ".\laravel-files\*" -Destination "F:\مجلد جديد\retina-diagnosis" -Recurse -Force
```

أو انسخ محتويات مجلد `laravel-files` يدويًا إلى داخل مجلد مشروع Laravel.

> مهم: هذا سيستبدل بعض الملفات مثل `routes/web.php` وبعض الواجهات.

---

## 2) إعداد FastAPI داخل Laravel

افتح ملف `.env` في Laravel وأضف:

```env
FASTAPI_URL=http://127.0.0.1:8001
```

افتح `config/services.php` وتأكد أن هذا الجزء موجود داخل `return [ ... ];`:

```php
'fastapi' => [
    'url' => env('FASTAPI_URL', 'http://127.0.0.1:8001'),
],
```

---

## 3) تحديث قاعدة البيانات

بما أن الجداول أصبحت أكبر وأنسب لمشروع تخرج، الأسهل في مرحلة التطوير تشغيل:

```powershell
cd "F:\مجلد جديد\retina-diagnosis"
php artisan migrate:fresh
php artisan storage:link
php artisan optimize:clear
```

تحذير: `migrate:fresh` يحذف البيانات القديمة. إذا عندك بيانات مهمة، أخبرني لأعطيك migrations ترقية بدون حذف.

---

## 4) بناء الواجهات

```powershell
npm.cmd install
npm.cmd run build
```

ثم شغل Laravel:

```powershell
php artisan serve
```

افتح:

```text
http://127.0.0.1:8000
```

---

## 5) تشغيل FastAPI

افتح CMD جديد:

```cmd
cd /d "F:\مجلد جديد\fastapi-service"
python -m venv venv
venv\Scripts\activate.bat
pip install -r requirements.txt
uvicorn main:app --host 127.0.0.1 --port 8001 --reload
```

إذا كان مجلد `fastapi-service` غير موجود عندك، انسخ المجلد الموجود في هذه الحزمة إلى:

```text
F:\مجلد جديد\fastapi-service
```

ثم شغل الأوامر السابقة.

---

## 6) اختبار الربط

افتح:

```text
http://127.0.0.1:8001/health
```

ثم افتح Laravel:

```text
http://127.0.0.1:8000/dashboard
```

إذا ظهرت حالة FastAPI في Dashboard باسم `متصل`، فالربط يعمل.

---

## 7) صفحات المشروع

- `/` الصفحة الرئيسية.
- `/about` عن المشروع.
- `/how-it-works` آلية العمل.
- `/dashboard` لوحة التحكم.
- `/patients` إدارة المرضى.
- `/diagnoses` سجل التشخيصات.
- `/diagnoses/create` رفع صورة وتشخيص.

---

## ملاحظة طبية مهمة

يجب كتابة هذه الملاحظة في العرض النهائي للمشروع:

> النظام يساعد الطبيب في قراءة صور الشبكية ولا يعتبر بديلًا عن التشخيص الطبي النهائي.

