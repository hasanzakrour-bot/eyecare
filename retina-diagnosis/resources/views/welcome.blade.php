<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RetinaCare AI - تشخيص أمراض الشبكية</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/retina-pro.css') }}">
</head>
<body class="bg-slate-50 text-slate-900 medical-grid">
    <header class="retina-gradient text-white">
        <nav class="max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-2xl bg-white/15 flex items-center justify-center text-2xl shadow-xl">◉</div>
                <div>
                    <div class="text-xl font-black">RetinaCare AI</div>
                    <div class="text-xs text-blue-100">Graduation Project</div>
                </div>
            </a>
            <div class="hidden md:flex items-center gap-3 text-sm font-semibold">
                <a href="{{ route('about') }}" class="hover:text-cyan-100">عن المشروع</a>
                <a href="{{ route('how-it-works') }}" class="hover:text-cyan-100">آلية العمل</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-xl bg-white px-5 py-2.5 text-blue-700 shadow-lg">لوحة التحكم</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl border border-white/30 px-5 py-2.5 hover:bg-white/10">دخول</a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-white px-5 py-2.5 text-blue-700 shadow-lg">حساب جديد</a>
                @endauth
            </div>
        </nav>

        <section class="max-w-7xl mx-auto px-6 py-20 lg:py-28 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex rounded-full bg-white/10 px-4 py-2 text-sm font-semibold ring-1 ring-white/20 mb-6">نظام ذكي لدعم أطباء العيون</div>
                <h1 class="text-4xl lg:text-6xl font-black leading-tight">تشخيص أمراض شبكية العين باستخدام الذكاء الاصطناعي</h1>
                <p class="mt-6 text-lg text-blue-50 leading-9">منصة ويب متكاملة لإدارة المرضى، رفع صور قاع العين، تحليلها بواسطة نموذج ذكاء اصطناعي عبر FastAPI، وحفظ النتائج والتقارير داخل MySQL.</p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="rounded-2xl bg-white px-7 py-4 font-bold text-blue-700 shadow-2xl hover:bg-blue-50">ابدأ الآن</a>
                    <a href="{{ route('how-it-works') }}" class="rounded-2xl border border-white/30 px-7 py-4 font-bold text-white hover:bg-white/10">كيف يعمل النظام؟</a>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -inset-4 rounded-[2rem] bg-white/10 blur-2xl"></div>
                <div class="relative rounded-[2rem] bg-white p-6 text-slate-900 shadow-2xl">
                    <div class="flex items-center justify-between border-b pb-5">
                        <div>
                            <div class="text-sm text-slate-500">نتيجة تحليل تجريبية</div>
                            <div class="text-2xl font-black">Diabetic Retinopathy</div>
                        </div>
                        <div class="h-16 w-16 rounded-2xl bg-blue-50 flex items-center justify-center text-3xl">👁️</div>
                    </div>
                    <div class="mt-6 space-y-5">
                        <div>
                            <div class="mb-2 flex justify-between text-sm font-semibold"><span>الثقة</span><span>87%</span></div>
                            <div class="h-3 rounded-full bg-slate-100"><div class="h-3 rounded-full bg-blue-700" style="width:87%"></div></div>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="rounded-2xl bg-blue-50 p-4 text-center"><div class="text-2xl font-black text-blue-700">+120</div><div class="text-xs text-slate-500">مريض</div></div>
                            <div class="rounded-2xl bg-cyan-50 p-4 text-center"><div class="text-2xl font-black text-cyan-700">+340</div><div class="text-xs text-slate-500">تشخيص</div></div>
                            <div class="rounded-2xl bg-rose-50 p-4 text-center"><div class="text-2xl font-black text-rose-700">24</div><div class="text-xs text-slate-500">حالة خطرة</div></div>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-5 text-sm leading-7 text-slate-600">النظام يقدم نتيجة مساعدة ولا يغني عن قرار الطبيب المختص.</div>
                    </div>
                </div>
            </div>
        </section>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-20">
        <div class="grid md:grid-cols-3 gap-6">
            <div class="glass-card rounded-3xl p-8"><div class="text-4xl mb-4">🧠</div><h3 class="text-xl font-black mb-3">ذكاء اصطناعي</h3><p class="text-slate-600 leading-8">ربط مباشر مع FastAPI ونموذج تصنيف صور شبكية العين.</p></div>
            <div class="glass-card rounded-3xl p-8"><div class="text-4xl mb-4">🗂️</div><h3 class="text-xl font-black mb-3">إدارة ملفات المرضى</h3><p class="text-slate-600 leading-8">حفظ بيانات المرضى، التاريخ المرضي، وسجل التشخيصات.</p></div>
            <div class="glass-card rounded-3xl p-8"><div class="text-4xl mb-4">📊</div><h3 class="text-xl font-black mb-3">تقارير ولوحة تحكم</h3><p class="text-slate-600 leading-8">إحصائيات فورية، سجل نتائج، وتقرير قابل للطباعة.</p></div>
        </div>
    </main>
</body>
</html>
