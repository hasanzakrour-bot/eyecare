<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\Patient;
use App\Services\FastApiDiagnosisClient;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(FastApiDiagnosisClient $fastApi)
    {
        $patientsCount = Patient::count();
        $diagnosesCount = Diagnosis::count();
        $todayDiagnoses = Diagnosis::whereDate('created_at', now()->toDateString())->count();
        $highRiskCount = Diagnosis::where('risk_level', 'high')->count();
        $mediumRiskCount = Diagnosis::where('risk_level', 'medium')->count();
        $lowRiskCount = Diagnosis::where('risk_level', 'low')->count();
        $avgConfidence = Diagnosis::whereNotNull('confidence')->avg('confidence');

        $latestDiagnoses = Diagnosis::with(['patient', 'user'])
            ->latest()
            ->take(6)
            ->get();

        $diseaseDistribution = Diagnosis::select('predicted_class', DB::raw('COUNT(*) as total'))
            ->whereNotNull('predicted_class')
            ->groupBy('predicted_class')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        $fastApiOnline = $fastApi->health();

        return view('dashboard', compact(
            'patientsCount',
            'diagnosesCount',
            'todayDiagnoses',
            'highRiskCount',
            'mediumRiskCount',
            'lowRiskCount',
            'avgConfidence',
            'latestDiagnoses',
            'diseaseDistribution',
            'fastApiOnline'
        ));
    }
}
