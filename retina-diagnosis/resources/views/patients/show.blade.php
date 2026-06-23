<x-app-layout>
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div><h1 class="text-3xl font-black">{{ $patient->full_name }}</h1><p class="mt-2 text-slate-500">ملف المريض وسجل التشخيصات.</p></div>
        <div class="flex gap-3">
            <a href="{{ route('diagnoses.create', ['patient_id' => $patient->id]) }}" class="rounded-2xl bg-blue-700 px-6 py-3 font-black text-white shadow-lg shadow-blue-200 hover:bg-blue-800">تشخيص جديد</a>
            <a href="{{ route('patients.edit', $patient) }}" class="rounded-2xl bg-slate-100 px-6 py-3 font-bold text-slate-700">تعديل</a>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 glass-card rounded-3xl p-6">
            <h2 class="text-xl font-black mb-5">بيانات المريض</h2>
            <div class="space-y-4 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">الرقم الوطني</span><span class="font-bold">{{ $patient->national_id ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">الجنس</span><span class="font-bold">{{ $patient->gender === 'male' ? 'ذكر' : 'أنثى' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">العمر</span><span class="font-bold">{{ $patient->age ? $patient->age . ' سنة' : '-' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">الهاتف</span><span class="font-bold">{{ $patient->phone ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">فصيلة الدم</span><span class="font-bold">{{ $patient->blood_type ?? '-' }}</span></div>
                <div class="pt-4 border-t"><div class="text-slate-500 mb-1">التاريخ المرضي</div><div class="font-semibold leading-7">{{ $patient->medical_history ?? '-' }}</div></div>
                <div><div class="text-slate-500 mb-1">الأعراض الحالية</div><div class="font-semibold leading-7">{{ $patient->current_symptoms ?? '-' }}</div></div>
            </div>
        </div>

        <div class="lg:col-span-2 glass-card rounded-3xl p-6">
            <h2 class="text-xl font-black mb-5">سجل التشخيصات</h2>
            <div class="space-y-4">
                @forelse ($patient->diagnoses as $diagnosis)
                    <a href="{{ route('diagnoses.show', $diagnosis) }}" class="block rounded-3xl border border-slate-100 p-5 hover:bg-blue-50/50">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div>
                                <div class="font-black text-lg">{{ $diagnosis->predicted_class }}</div>
                                <div class="text-sm text-slate-500">{{ $diagnosis->created_at->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="rounded-full bg-blue-50 px-3 py-1 text-sm font-bold text-blue-700">{{ $diagnosis->confidence_percent }}%</span>
                                <span class="rounded-full px-3 py-1 text-sm font-bold {{ $diagnosis->risk_level === 'high' ? 'bg-rose-100 text-rose-700' : ($diagnosis->risk_level === 'medium' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">{{ $diagnosis->risk_label }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="rounded-3xl bg-slate-50 p-8 text-center text-slate-500">لا توجد تشخيصات لهذا المريض بعد.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
