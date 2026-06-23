<x-app-layout>
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div><h1 class="text-3xl font-black">سجل التشخيصات</h1><p class="mt-2 text-slate-500">كل نتائج تحليل صور الشبكية محفوظة هنا.</p></div>
        <a href="{{ route('diagnoses.create') }}" class="rounded-2xl bg-blue-700 px-6 py-3 font-black text-white shadow-lg shadow-blue-200 hover:bg-blue-800">تشخيص جديد</a>
    </div>

    <div class="glass-card rounded-3xl p-6 mb-6">
        <form method="GET" class="grid md:grid-cols-4 gap-3">
            <input name="search" value="{{ request('search') }}" placeholder="اسم المريض أو النتيجة" class="rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
            <select name="risk_level" class="rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
                <option value="">كل مستويات الخطورة</option>
                <option value="low" @selected(request('risk_level') === 'low')>منخفض</option>
                <option value="medium" @selected(request('risk_level') === 'medium')>متوسط</option>
                <option value="high" @selected(request('risk_level') === 'high')>مرتفع</option>
            </select>
            <select name="status" class="rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
                <option value="">كل الحالات</option>
                <option value="completed" @selected(request('status') === 'completed')>مكتمل</option>
                <option value="reviewed" @selected(request('status') === 'reviewed')>تمت المراجعة</option>
                <option value="failed" @selected(request('status') === 'failed')>فشل</option>
            </select>
            <div class="flex gap-2">
                <button class="flex-1 rounded-2xl bg-slate-900 px-5 py-3 font-bold text-white">تصفية</button>
                <a href="{{ route('diagnoses.index') }}" class="rounded-2xl bg-slate-100 px-5 py-3 font-bold text-slate-700">إلغاء</a>
            </div>
        </form>
    </div>

    <div class="grid gap-5">
        @forelse ($diagnoses as $diagnosis)
            <div class="glass-card rounded-3xl p-5 grid lg:grid-cols-[130px,1fr,auto] gap-5 items-center">
                <img src="{{ asset('storage/' . $diagnosis->image_path) }}" class="h-28 w-full rounded-2xl object-cover border border-slate-100" alt="retina image">
                <div>
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $diagnosis->risk_level === 'high' ? 'bg-rose-100 text-rose-700' : ($diagnosis->risk_level === 'medium' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">خطورة {{ $diagnosis->risk_label }}</span>
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">ثقة {{ $diagnosis->confidence_percent }}%</span>
                    </div>
                    <h2 class="text-xl font-black">{{ $diagnosis->predicted_class }}</h2>
                    <p class="mt-1 text-slate-500">المريض: {{ $diagnosis->patient?->full_name }} — {{ $diagnosis->created_at->format('Y-m-d H:i') }}</p>
                </div>
                <a href="{{ route('diagnoses.show', $diagnosis) }}" class="rounded-2xl bg-blue-700 px-6 py-3 text-center font-black text-white shadow-lg shadow-blue-200">عرض التقرير</a>
            </div>
        @empty
            <div class="glass-card rounded-3xl p-10 text-center text-slate-500">لا توجد تشخيصات مطابقة.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $diagnoses->links() }}</div>
</x-app-layout>
