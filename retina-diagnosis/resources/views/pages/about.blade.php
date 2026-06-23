<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>عن المشروع - RetinaCare AI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/retina-pro.css') }}">
</head>
<body class="bg-slate-50 text-slate-900 medical-grid">
<div class="retina-gradient text-white py-16">
    <div class="max-w-5xl mx-auto px-6">
        <a href="{{ route('home') }}" class="text-sm text-blue-100">← الرئيسية</a>
        <h1 class="mt-8 text-5xl font-black">عن مشروع RetinaCare AI</h1>
        <p class="mt-6 text-lg leading-9 text-blue-50">مشروع تخرج يهدف إلى بناء منصة ويب احترافية تساعد في تحليل صور شبكية العين باستخدام الذكاء الاصطناعي، مع حفظ بيانات المرضى ونتائج التشخيص في قاعدة بيانات منظمة.</p>
    </div>
</div>
<div class="max-w-5xl mx-auto px-6 py-14 grid md:grid-cols-2 gap-6">
    <div class="glass-card rounded-3xl p-8"><h2 class="text-2xl font-black mb-4">الهدف</h2><p class="text-slate-600 leading-8">تسهيل عملية رفع صورة العين، تحليلها، وتقديم نتيجة أولية منظمة للطبيب.</p></div>
    <div class="glass-card rounded-3xl p-8"><h2 class="text-2xl font-black mb-4">التقنيات</h2><p class="text-slate-600 leading-8">Laravel 10، MySQL، FastAPI، ونموذج ذكاء اصطناعي لتصنيف صور قاع العين.</p></div>
</div>
</body>
</html>
