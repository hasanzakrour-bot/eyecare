<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-black">تعديل بيانات المريض</h1>
        <p class="mt-2 text-slate-500">{{ $patient->full_name }}</p>
    </div>

    <form method="POST" action="{{ route('patients.update', $patient) }}" class="glass-card rounded-3xl p-6 lg:p-8">
        @method('PUT')
        @include('patients.partials.form', ['patient' => $patient])
    </form>
</x-app-layout>
