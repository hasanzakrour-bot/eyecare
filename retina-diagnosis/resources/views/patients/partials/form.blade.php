@csrf
<div class="grid lg:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">اسم المريض الكامل *</label>
        <input name="full_name" value="{{ old('full_name', $patient->full_name ?? '') }}" required class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
        @error('full_name') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">الرقم الوطني</label>
        <input name="national_id" value="{{ old('national_id', $patient->national_id ?? '') }}" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
        @error('national_id') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">تاريخ الميلاد</label>
        <input name="birth_date" type="date" value="{{ old('birth_date', isset($patient) && $patient->birth_date ? $patient->birth_date->format('Y-m-d') : '') }}" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
    </div>
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">الجنس *</label>
        <select name="gender" required class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
            <option value="">اختر</option>
            <option value="male" @selected(old('gender', $patient->gender ?? '') === 'male')>ذكر</option>
            <option value="female" @selected(old('gender', $patient->gender ?? '') === 'female')>أنثى</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">الهاتف</label>
        <input name="phone" value="{{ old('phone', $patient->phone ?? '') }}" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
    </div>
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني</label>
        <input name="email" type="email" value="{{ old('email', $patient->email ?? '') }}" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
    </div>
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">فصيلة الدم</label>
        <input name="blood_type" value="{{ old('blood_type', $patient->blood_type ?? '') }}" placeholder="مثال: O+" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
    </div>
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">نوع السكري إن وجد</label>
        <input name="diabetes_type" value="{{ old('diabetes_type', $patient->diabetes_type ?? '') }}" placeholder="Type 1 / Type 2 / لا يوجد" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
    </div>
</div>

<div class="mt-6">
    <label class="block text-sm font-bold text-slate-700 mb-2">العنوان</label>
    <input name="address" value="{{ old('address', $patient->address ?? '') }}" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
</div>

<div class="mt-6 grid lg:grid-cols-3 gap-6">
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">التاريخ المرضي</label>
        <textarea name="medical_history" rows="5" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">{{ old('medical_history', $patient->medical_history ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">الأعراض الحالية</label>
        <textarea name="current_symptoms" rows="5" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">{{ old('current_symptoms', $patient->current_symptoms ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">ملاحظات طبية</label>
        <textarea name="medical_notes" rows="5" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">{{ old('medical_notes', $patient->medical_notes ?? '') }}</textarea>
    </div>
</div>

<div class="mt-6 grid lg:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">اسم جهة الطوارئ</label>
        <input name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name ?? '') }}" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
    </div>
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">هاتف جهة الطوارئ</label>
        <input name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone ?? '') }}" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
    </div>
</div>

<div class="mt-8 flex flex-wrap gap-3">
    <button class="rounded-2xl bg-blue-700 px-8 py-3 font-black text-white shadow-lg shadow-blue-200 hover:bg-blue-800">حفظ البيانات</button>
    <a href="{{ route('patients.index') }}" class="rounded-2xl bg-slate-100 px-8 py-3 font-bold text-slate-700">رجوع</a>
</div>
