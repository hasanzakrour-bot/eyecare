<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>آلية العمل - RetinaCare AI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/retina-pro.css') }}">
</head>
<body class="bg-slate-50 text-slate-900 medical-grid">
<div class="max-w-5xl mx-auto px-6 py-16">
    <a href="{{ route('home') }}" class="text-sm text-blue-700 font-semibold">← الرئيسية</a>
    <h1 class="mt-8 text-5xl font-black text-slate-900">كيف يعمل النظام؟</h1>
    <div class="mt-10 grid gap-5">
        <div class="glass-card rounded-3xl p-7 flex gap-5"><div class="h-12 w-12 rounded-2xl bg-blue-700 text-white flex items-center justify-center font-black">1</div><div><h3 class="font-black text-xl">إدخال بيانات المريض</h3><p class="text-slate-600 mt-2">يتم إنشاء ملف طبي يحتوي على المعلومات الأساسية والتاريخ المرضي.</p></div></div>
        <div class="glass-card rounded-3xl p-7 flex gap-5"><div class="h-12 w-12 rounded-2xl bg-blue-700 text-white flex items-center justify-center font-black">2</div><div><h3 class="font-black text-xl">رفع صورة الشبكية</h3><p class="text-slate-600 mt-2">يرفع المستخدم صورة fundus بصيغة jpg أو png أو webp.</p></div></div>
        <div class="glass-card rounded-3xl p-7 flex gap-5"><div class="h-12 w-12 rounded-2xl bg-blue-700 text-white flex items-center justify-center font-black">3</div><div><h3 class="font-black text-xl">تحليل الصورة عبر FastAPI</h3><p class="text-slate-600 mt-2">Laravel يرسل الصورة إلى خدمة FastAPI، ثم تعود النتيجة والاحتمالات.</p></div></div>
        <div class="glass-card rounded-3xl p-7 flex gap-5"><div class="h-12 w-12 rounded-2xl bg-blue-700 text-white flex items-center justify-center font-black">4</div><div><h3 class="font-black text-xl">عرض التقرير وحفظه</h3><p class="text-slate-600 mt-2">تُحفظ النتيجة في MySQL وتظهر في لوحة التحكم وسجل التشخيصات.</p></div></div>
    </div>
</div>
</body>
</html>
