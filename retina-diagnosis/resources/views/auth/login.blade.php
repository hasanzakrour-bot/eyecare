<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الدخول - RetinaCare AI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/retina-pro.css') }}">
</head>
<body class="min-h-screen bg-slate-50 medical-grid flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-5xl grid lg:grid-cols-2 rounded-[2rem] overflow-hidden shadow-2xl bg-white">
        <div class="retina-gradient p-10 text-white hidden lg:flex flex-col justify-between">
            <div>
                <div class="h-14 w-14 rounded-2xl bg-white/15 flex items-center justify-center text-3xl">◉</div>
                <h1 class="mt-8 text-4xl font-black leading-tight">مرحبًا بك في RetinaCare AI</h1>
                <p class="mt-5 text-blue-50 leading-8">ادخل إلى لوحة التحكم لإدارة المرضى وتشخيص صور الشبكية وحفظ النتائج.</p>
            </div>
            <a href="{{ route('home') }}" class="text-blue-100">← العودة للرئيسية</a>
        </div>

        <div class="p-8 lg:p-12">
            <h2 class="text-3xl font-black text-slate-900">تسجيل الدخول</h2>
            <p class="mt-2 text-slate-500">أدخل بيانات حسابك للمتابعة.</p>

            @if ($errors->any())
                <div class="mt-6 rounded-2xl bg-rose-50 border border-rose-200 p-4 text-rose-700 text-sm">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني</label>
                    <input name="email" type="email" value="{{ old('email') }}" required autofocus class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-4 focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">كلمة المرور</label>
                    <input name="password" type="password" required class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-4 focus:border-blue-500 focus:ring-blue-500">
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input name="remember" type="checkbox" class="rounded border-slate-300 text-blue-700 focus:ring-blue-500">
                    تذكرني
                </label>
                <button class="w-full rounded-2xl bg-blue-700 px-6 py-4 font-black text-white shadow-lg shadow-blue-200 hover:bg-blue-800">دخول</button>
            </form>

            <p class="mt-6 text-sm text-slate-500">ليس لديك حساب؟ <a href="{{ route('register') }}" class="font-bold text-blue-700">إنشاء حساب جديد</a></p>
        </div>
    </div>
</body>
</html>
