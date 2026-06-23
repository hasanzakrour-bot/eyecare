<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-black">تشخيص صورة شبكية جديدة</h1>
        <p class="mt-2 text-slate-500">ارفع صورة قاع العين وسيتم إرسالها إلى FastAPI للتحليل.</p>
    </div>

    <form method="POST" action="{{ route('diagnoses.store') }}" enctype="multipart/form-data" class="grid lg:grid-cols-3 gap-6">
        @csrf
        <div class="lg:col-span-2 glass-card rounded-3xl p-6 lg:p-8">
            <h2 class="text-xl font-black mb-6">بيانات التحليل</h2>
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">اختر المريض *</label>
                    <select name="patient_id" required class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">اختر من القائمة</option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}" @selected((string) old('patient_id', $selectedPatient) === (string) $patient->id)>{{ $patient->full_name }} {{ $patient->national_id ? ' - ' . $patient->national_id : '' }}</option>
                        @endforeach
                    </select>
                    @error('patient_id') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">صورة الشبكية *</label>
                    <input name="image" type="file" accept="image/*" required class="block w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-700 file:ml-4 file:rounded-xl file:border-0 file:bg-blue-700 file:px-4 file:py-2 file:text-white file:font-bold">
                    <p class="mt-2 text-sm text-slate-500">الصيغ المدعومة: JPG, PNG, WEBP. الحد الأقصى: 8MB.</p>
                    @error('image') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ملاحظات سريرية قبل التحليل</label>
                    <textarea name="clinical_notes" rows="6" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500" placeholder="مثال: تشوش رؤية، تاريخ سكري، ألم في العين...">{{ old('clinical_notes') }}</textarea>
                </div>
            </div>
            <div class="mt-8 flex flex-wrap gap-3">
                <button class="rounded-2xl bg-blue-700 px-8 py-4 font-black text-white shadow-lg shadow-blue-200 hover:bg-blue-800">بدء التشخيص</button>
                <a href="{{ route('diagnoses.index') }}" class="rounded-2xl bg-slate-100 px-8 py-4 font-bold text-slate-700">رجوع</a>
            </div>
        </div>

        <aside class="glass-card rounded-3xl p-6 h-fit">
            <div class="h-16 w-16 rounded-3xl bg-blue-50 text-blue-700 flex items-center justify-center text-3xl mb-5">⚕️</div>
            <h2 class="text-xl font-black">تعليمات مهمة</h2>
            <ul class="mt-4 space-y-3 text-sm leading-7 text-slate-600">
                <li>• استخدم صورة واضحة لقاع العين.</li>
                <li>• تجنب الصور الضبابية أو منخفضة الإضاءة.</li>
                <li>• شغّل FastAPI قبل الضغط على بدء التشخيص.</li>
                <li>• النتيجة مساعدة ولا تغني عن تشخيص الطبيب.</li>
            </ul>
        </aside>
    </form>
</x-app-layout>
