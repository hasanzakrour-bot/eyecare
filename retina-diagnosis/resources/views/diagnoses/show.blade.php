<x-app-layout>
    <div class="print-only mb-8 text-center">
        <h1 class="text-2xl font-black">RetinaCare AI - تقرير تشخيص الشبكية</h1>
        <p class="text-sm text-slate-500">{{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <div class="no-print flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div><h1 class="text-3xl font-black">تقرير التشخيص</h1><p class="mt-2 text-slate-500">نتيجة تحليل صورة الشبكية ومراجعة الطبيب.</p></div>
        <div class="flex flex-wrap gap-3">
            <button onclick="window.print()" class="rounded-2xl bg-slate-900 px-6 py-3 font-black text-white">طباعة التقرير</button>
            <a href="{{ route('diagnoses.create', ['patient_id' => $diagnosis->patient_id]) }}" class="rounded-2xl bg-blue-700 px-6 py-3 font-black text-white shadow-lg shadow-blue-200">تشخيص آخر</a>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card rounded-3xl overflow-hidden">
                <div class="retina-gradient p-7 text-white">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <div class="text-sm text-blue-100">النتيجة المتوقعة</div>
                            <h2 class="mt-2 text-4xl font-black">{{ $diagnosis->predicted_class }}</h2>
                        </div>
                        <div class="rounded-3xl bg-white/15 p-5 text-center ring-1 ring-white/20">
                            <div class="text-4xl font-black">{{ $diagnosis->confidence_percent }}%</div>
                            <div class="text-sm text-blue-100">نسبة الثقة</div>
                        </div>
                    </div>
                </div>
                <div class="p-6 grid md:grid-cols-3 gap-4">
                    <div class="rounded-2xl bg-slate-50 p-5"><div class="text-sm text-slate-500">مستوى الخطورة</div><div class="mt-2 text-xl font-black {{ $diagnosis->risk_level === 'high' ? 'text-rose-600' : ($diagnosis->risk_level === 'medium' ? 'text-amber-600' : 'text-emerald-600') }}">{{ $diagnosis->risk_label }}</div></div>
                    <div class="rounded-2xl bg-slate-50 p-5"><div class="text-sm text-slate-500">حالة التقرير</div><div class="mt-2 text-xl font-black">{{ $diagnosis->status === 'reviewed' ? 'تمت المراجعة' : ($diagnosis->status === 'failed' ? 'فشل' : 'مكتمل') }}</div></div>
                    <div class="rounded-2xl bg-slate-50 p-5"><div class="text-sm text-slate-500">النموذج</div><div class="mt-2 text-sm font-black leading-6">{{ $diagnosis->model_name ?? '-' }}</div></div>
                </div>
            </div>

            <div class="glass-card rounded-3xl p-6">
                <h2 class="text-xl font-black mb-5">احتمالات التصنيف</h2>
                <div class="space-y-4">
                    @forelse (($diagnosis->probabilities ?? []) as $label => $value)
                        @php $percent = round(((float) $value) * 100, 2); @endphp
                        <div>
                            <div class="flex justify-between text-sm font-bold"><span>{{ $label }}</span><span>{{ $percent }}%</span></div>
                            <div class="mt-2 h-3 rounded-full bg-slate-100"><div class="h-3 rounded-full bg-blue-700" style="width: {{ min(100, $percent) }}%"></div></div>
                        </div>
                    @empty
                        <p class="text-slate-500">لا توجد احتمالات مفصلة.</p>
                    @endforelse
                </div>
            </div>

            <div class="glass-card rounded-3xl p-6">
                <h2 class="text-xl font-black mb-4">التوصية</h2>
                <p class="rounded-2xl bg-blue-50 p-5 leading-8 text-blue-900">{{ $diagnosis->recommendation ?? 'هذه نتيجة مساعدة ولا تغني عن التشخيص الطبي المتخصص.' }}</p>
                @if ($diagnosis->clinical_notes)
                    <h3 class="mt-6 font-black">ملاحظات سريرية</h3>
                    <p class="mt-2 leading-8 text-slate-600">{{ $diagnosis->clinical_notes }}</p>
                @endif
            </div>

            <div class="no-print glass-card rounded-3xl p-6">
                <h2 class="text-xl font-black mb-4">مراجعة الطبيب</h2>
                <form method="POST" action="{{ route('diagnoses.review', $diagnosis) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <textarea name="doctor_decision" rows="5" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500" placeholder="اكتب قرار الطبيب أو خطة المتابعة...">{{ old('doctor_decision', $diagnosis->doctor_decision) }}</textarea>
                    <select name="status" class="rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
                        <option value="completed" @selected($diagnosis->status === 'completed')>مكتمل</option>
                        <option value="reviewed" @selected($diagnosis->status === 'reviewed')>تمت المراجعة</option>
                        <option value="failed" @selected($diagnosis->status === 'failed')>فشل</option>
                    </select>
                    <button class="rounded-2xl bg-blue-700 px-7 py-3 font-black text-white shadow-lg shadow-blue-200">حفظ المراجعة</button>
                </form>
            </div>

            @if ($diagnosis->doctor_decision)
                <div class="glass-card rounded-3xl p-6">
                    <h2 class="text-xl font-black mb-4">قرار الطبيب المحفوظ</h2>
                    <p class="leading-8 text-slate-700">{{ $diagnosis->doctor_decision }}</p>
                    <p class="mt-3 text-sm text-slate-500">تاريخ المراجعة: {{ $diagnosis->reviewed_at?->format('Y-m-d H:i') ?? '-' }}</p>
                </div>
            @endif
        </div>

        <aside class="space-y-6">
            <div class="glass-card rounded-3xl p-6">
                <img src="{{ asset('storage/' . $diagnosis->image_path) }}" class="w-full rounded-3xl border border-slate-100 object-cover" alt="Retina Image">
            </div>
            <div class="glass-card rounded-3xl p-6">
                <h2 class="text-xl font-black mb-5">بيانات المريض</h2>
                <div class="space-y-4 text-sm">
                    <div><div class="text-slate-500">الاسم</div><a href="{{ route('patients.show', $diagnosis->patient) }}" class="font-black text-blue-700">{{ $diagnosis->patient?->full_name }}</a></div>
                    <div class="flex justify-between"><span class="text-slate-500">الرقم الوطني</span><span class="font-bold">{{ $diagnosis->patient?->national_id ?? '-' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">العمر</span><span class="font-bold">{{ $diagnosis->patient?->age ? $diagnosis->patient->age . ' سنة' : '-' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">الهاتف</span><span class="font-bold">{{ $diagnosis->patient?->phone ?? '-' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">تاريخ التشخيص</span><span class="font-bold">{{ $diagnosis->created_at->format('Y-m-d') }}</span></div>
                </div>
            </div>
            <div class="rounded-3xl border border-amber-200 bg-amber-50 p-6 text-amber-900 leading-8">
                <strong>تنبيه طبي:</strong> هذه النتيجة مبنية على نموذج ذكاء اصطناعي وتستخدم للدعم فقط، ولا تعتبر تشخيصًا نهائيًا دون مراجعة الطبيب المختص.
            </div>
        </aside>
    </div>
</x-app-layout>
