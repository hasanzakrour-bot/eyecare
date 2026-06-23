<x-app-layout>
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div><h1 class="text-3xl font-black">إدارة المرضى</h1><p class="mt-2 text-slate-500">بحث، إضافة، وتحديث ملفات المرضى.</p></div>
        <a href="{{ route('patients.create') }}" class="rounded-2xl bg-blue-700 px-6 py-3 font-black text-white shadow-lg shadow-blue-200 hover:bg-blue-800">إضافة مريض</a>
    </div>

    <div class="glass-card rounded-3xl p-6 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-3">
            <input name="search" value="{{ $search }}" placeholder="ابحث بالاسم، الرقم الوطني، الهاتف أو البريد" class="flex-1 rounded-2xl border-slate-200 bg-slate-50 px-5 py-3 focus:border-blue-500 focus:ring-blue-500">
            <button class="rounded-2xl bg-slate-900 px-6 py-3 font-bold text-white">بحث</button>
            <a href="{{ route('patients.index') }}" class="rounded-2xl bg-slate-100 px-6 py-3 font-bold text-slate-700 text-center">إلغاء</a>
        </form>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="text-right px-6 py-4">المريض</th>
                    <th class="text-right px-6 py-4">النوع</th>
                    <th class="text-right px-6 py-4">العمر</th>
                    <th class="text-right px-6 py-4">الهاتف</th>
                    <th class="text-right px-6 py-4">عدد التشخيصات</th>
                    <th class="text-right px-6 py-4"></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse ($patients as $patient)
                    <tr class="hover:bg-blue-50/50">
                        <td class="px-6 py-4"><div class="font-black text-slate-900">{{ $patient->full_name }}</div><div class="text-xs text-slate-500">{{ $patient->national_id ?? 'بدون رقم وطني' }}</div></td>
                        <td class="px-6 py-4">{{ $patient->gender === 'male' ? 'ذكر' : 'أنثى' }}</td>
                        <td class="px-6 py-4">{{ $patient->age ? $patient->age . ' سنة' : '-' }}</td>
                        <td class="px-6 py-4">{{ $patient->phone ?? '-' }}</td>
                        <td class="px-6 py-4"><span class="rounded-full bg-blue-50 px-3 py-1 font-bold text-blue-700">{{ $patient->diagnoses_count }}</span></td>
                        <td class="px-6 py-4 flex gap-3">
                            <a href="{{ route('patients.show', $patient) }}" class="font-bold text-blue-700">عرض</a>
                            <a href="{{ route('patients.edit', $patient) }}" class="font-bold text-slate-600">تعديل</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-10 text-center text-slate-500">لا يوجد مرضى بعد.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">{{ $patients->links() }}</div>
    </div>
</x-app-layout>
