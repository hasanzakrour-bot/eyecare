<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-black">إضافة مريض جديد</h1>
        <p class="mt-2 text-slate-500">أنشئ ملفًا طبيًا منظمًا قبل رفع صورة الشبكية.</p>
    </div>

    <form method="POST" action="{{ route('patients.store') }}" class="glass-card rounded-3xl p-6 lg:p-8">
        @include('patients.partials.form', ['patient' => null])
    </form>
</x-app-layout>
