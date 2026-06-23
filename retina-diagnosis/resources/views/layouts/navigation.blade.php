<nav x-data="{ open: false }" class="no-print sticky top-0 z-40 border-b border-white/30 bg-white/90 backdrop-blur-xl shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-2xl bg-blue-700 text-white flex items-center justify-center shadow-lg shadow-blue-200">
                        <span class="text-2xl">◉</span>
                    </div>
                    <div>
                        <div class="text-xl font-black text-slate-900">RetinaCare AI</div>
                        <div class="text-xs text-slate-500">نظام تشخيص أمراض الشبكية</div>
                    </div>
                </a>

                <div class="hidden lg:flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white shadow' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-700' }}">لوحة التحكم</a>
                    <a href="{{ route('patients.index') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('patients.*') ? 'bg-blue-700 text-white shadow' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-700' }}">المرضى</a>
                    <a href="{{ route('diagnoses.index') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('diagnoses.*') ? 'bg-blue-700 text-white shadow' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-700' }}">التشخيصات</a>
                    <a href="{{ route('diagnoses.create') }}" class="px-4 py-2 rounded-xl text-sm font-semibold bg-cyan-500 text-white hover:bg-cyan-600 shadow">تشخيص جديد</a>
                </div>
            </div>

            <div class="hidden lg:flex items-center gap-4">
                <a href="{{ route('profile.edit') }}" class="text-sm text-slate-600 hover:text-blue-700">{{ Auth::user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-4 py-2 rounded-xl text-sm font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100">تسجيل الخروج</button>
                </form>
            </div>

            <button @click="open = ! open" class="lg:hidden inline-flex items-center justify-center rounded-xl p-2 text-slate-600 hover:bg-slate-100">
                <span class="text-2xl">☰</span>
            </button>
        </div>
    </div>

    <div x-show="open" class="lg:hidden border-t border-slate-100 bg-white px-4 py-4 space-y-2">
        <a href="{{ route('dashboard') }}" class="block rounded-xl px-4 py-3 font-semibold text-slate-700 hover:bg-blue-50">لوحة التحكم</a>
        <a href="{{ route('patients.index') }}" class="block rounded-xl px-4 py-3 font-semibold text-slate-700 hover:bg-blue-50">المرضى</a>
        <a href="{{ route('diagnoses.index') }}" class="block rounded-xl px-4 py-3 font-semibold text-slate-700 hover:bg-blue-50">التشخيصات</a>
        <a href="{{ route('diagnoses.create') }}" class="block rounded-xl px-4 py-3 font-semibold text-white bg-blue-700">تشخيص جديد</a>
        <form method="POST" action="{{ route('logout') }}" class="pt-2">
            @csrf
            <button class="w-full text-right rounded-xl px-4 py-3 font-semibold text-rose-600 bg-rose-50">تسجيل الخروج</button>
        </form>
    </div>
</nav>
