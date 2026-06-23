<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\Patient;
use App\Services\FastApiDiagnosisClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DiagnosisController extends Controller
{
    public function index(Request $request)
    {
        $diagnoses = Diagnosis::query()
            ->with(['patient', 'user'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->get('search');
                $query->whereHas('patient', function ($patientQuery) use ($search) {
                    $patientQuery->where('full_name', 'like', "%{$search}%")
                        ->orWhere('national_id', 'like', "%{$search}%");
                })->orWhere('predicted_class', 'like', "%{$search}%");
            })
            ->when($request->filled('risk_level'), fn ($query) => $query->where('risk_level', $request->get('risk_level')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->get('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('diagnoses.index', compact('diagnoses'));
    }

    public function create(Request $request)
    {
        $patients = Patient::orderBy('full_name')->get();
        $selectedPatient = $request->get('patient_id');

        return view('diagnoses.create', compact('patients', 'selectedPatient'));
    }

    public function store(Request $request, FastApiDiagnosisClient $fastApi)
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'clinical_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $imagePath = $request->file('image')->store('retina-images', 'public');
        $absolutePath = Storage::disk('public')->path($imagePath);

        try {
            $apiResult = $fastApi->predict($absolutePath);

            $predictedClass = $apiResult['predicted_class'] ?? 'Unknown';
            $confidence = (float) ($apiResult['confidence'] ?? 0);
            $riskLevel = $this->detectRiskLevel($predictedClass, $confidence);

            $diagnosis = Diagnosis::create([
                'patient_id' => $data['patient_id'],
                'user_id' => Auth::id(),
                'image_path' => $imagePath,
                'predicted_class' => $predictedClass,
                'confidence' => $confidence,
                'risk_level' => $riskLevel,
                'probabilities' => $apiResult['probabilities'] ?? [],
                'api_response' => $apiResult,
                'recommendation' => $apiResult['recommendation'] ?? $this->defaultRecommendation($riskLevel),
                'clinical_notes' => $data['clinical_notes'] ?? null,
                'doctor_decision' => null,
                'model_name' => $apiResult['model_name'] ?? 'Retinal Disease AI Model',
                'model_version' => $apiResult['model_version'] ?? '1.0',
                'status' => 'completed',
                'reviewed_at' => null,
            ]);

            return redirect()
                ->route('diagnoses.show', $diagnosis)
                ->with('success', 'تم تحليل صورة الشبكية وحفظ نتيجة التشخيص بنجاح.');
        } catch (\Throwable $exception) {
            $diagnosis = Diagnosis::create([
                'patient_id' => $data['patient_id'],
                'user_id' => Auth::id(),
                'image_path' => $imagePath,
                'predicted_class' => 'API Error',
                'confidence' => 0,
                'risk_level' => 'unknown',
                'probabilities' => [],
                'api_response' => ['error' => $exception->getMessage()],
                'recommendation' => 'تعذر الاتصال بخدمة الذكاء الاصطناعي. تأكد من تشغيل FastAPI ثم أعد المحاولة.',
                'clinical_notes' => $data['clinical_notes'] ?? null,
                'model_name' => 'Unavailable',
                'model_version' => null,
                'status' => 'failed',
            ]);

            return redirect()
                ->route('diagnoses.show', $diagnosis)
                ->with('error', 'تعذر الاتصال بخدمة FastAPI: ' . $exception->getMessage());
        }
    }

    public function show(Diagnosis $diagnosis)
    {
        $diagnosis->load(['patient', 'user']);

        return view('diagnoses.show', compact('diagnosis'));
    }

    public function updateReview(Request $request, Diagnosis $diagnosis)
    {
        $data = $request->validate([
            'doctor_decision' => ['required', 'string', 'max:2000'],
            'status' => ['required', 'in:completed,reviewed,failed'],
        ]);

        $diagnosis->update([
            'doctor_decision' => $data['doctor_decision'],
            'status' => $data['status'],
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->route('diagnoses.show', $diagnosis)
            ->with('success', 'تم حفظ مراجعة الطبيب.');
    }

    private function detectRiskLevel(string $predictedClass, float $confidence): string
    {
        $class = mb_strtolower($predictedClass);

        if (str_contains($class, 'normal') || str_contains($class, 'healthy')) {
            return $confidence >= 0.55 ? 'low' : 'medium';
        }

        if ($confidence >= 0.70) {
            return 'high';
        }

        if ($confidence >= 0.45) {
            return 'medium';
        }

        return 'low';
    }

    private function defaultRecommendation(string $riskLevel): string
    {
        return match ($riskLevel) {
            'high' => 'يوصى بمراجعة طبيب عيون مختص في أقرب وقت لإجراء فحص سريري شامل.',
            'medium' => 'يوصى بمتابعة الحالة وإعادة الفحص عند الحاجة مع مراجعة طبيب مختص.',
            'low' => 'النتيجة منخفضة الخطورة، مع الاستمرار في الفحوصات الدورية.',
            default => 'هذه نتيجة مساعدة ولا تغني عن التشخيص الطبي المتخصص.',
        };
    }
}
