<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>حساب جديد - RetinaCare AI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/retina-pro.css') }}">
</head>
<body class="min-h-screen bg-slate-50 medical-grid flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-2xl rounded-[2rem] bg-white p-8 lg:p-12 shadow-2xl">
        <a href="{{ route('home') }}" class="text-sm text-blue-700 font-semibold">← الرئيسية</a>
        <h1 class="mt-8 text-3xl font-black">إنشاء حساب طبيب</h1>
        <p class="mt-2 text-slate-500">سيتم استخدام الحساب للوصول إلى لوحة التحكم والتشخيصات.</p>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl bg-rose-50 border border-rose-200 p-4 text-rose-700 text-sm">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="mt-8 grid gap-5">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">الاسم</label>
                <input name="name" value="{{ old('name') }}" required class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-4 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني</label>
                <input name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-4 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">كلمة المرور</label>
                    <input name="password" type="password" required class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-4 focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">تأكيد كلمة المرور</label>
                    <input name="password_confirmation" type="password" required class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-4 focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            <button class="rounded-2xl bg-blue-700 px-6 py-4 font-black text-white shadow-lg shadow-blue-200 hover:bg-blue-800">إنشاء الحساب</button>
        </form>

        <p class="mt-6 text-sm text-slate-500">لديك حساب؟ <a href="{{ route('login') }}" class="font-bold text-blue-700">تسجيل الدخول</a></p>
    </div>
</body>
</html>
