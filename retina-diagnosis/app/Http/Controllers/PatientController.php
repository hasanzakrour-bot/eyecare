<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));

        $patients = Patient::query()
            ->withCount('diagnoses')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('full_name', 'like', "%{$search}%")
                        ->orWhere('national_id', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('patients.index', compact('patients', 'search'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatePatient($request);
        $data['created_by'] = Auth::id();

        $patient = Patient::create($data);

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'تم إنشاء ملف المريض بنجاح.');
    }

    public function show(Patient $patient)
    {
        $patient->load(['diagnoses' => fn ($query) => $query->latest()]);

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $patient->update($this->validatePatient($request, $patient->id));

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'تم تحديث بيانات المريض بنجاح.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('success', 'تم حذف ملف المريض.');
    }

    private function validatePatient(Request $request, ?int $patientId = null): array
    {
        return $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'national_id' => ['nullable', 'string', 'max:50', 'unique:patients,national_id,' . $patientId],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'blood_type' => ['nullable', 'string', 'max:10'],
            'diabetes_type' => ['nullable', 'string', 'max:100'],
            'medical_history' => ['nullable', 'string', 'max:2000'],
            'current_symptoms' => ['nullable', 'string', 'max:2000'],
            'medical_notes' => ['nullable', 'string', 'max:2000'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
        ]);
    }
}
