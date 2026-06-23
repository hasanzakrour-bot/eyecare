<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'user_id',
        'image_path',
        'predicted_class',
        'confidence',
        'risk_level',
        'probabilities',
        'api_response',
        'recommendation',
        'clinical_notes',
        'doctor_decision',
        'model_name',
        'model_version',
        'status',
        'reviewed_at',
    ];

    protected $casts = [
        'confidence' => 'float',
        'probabilities' => 'array',
        'api_response' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getConfidencePercentAttribute(): int
    {
        return (int) round(($this->confidence ?? 0) * 100);
    }

    public function getRiskLabelAttribute(): string
    {
        return match ($this->risk_level) {
            'low' => 'منخفض',
            'medium' => 'متوسط',
            'high' => 'مرتفع',
            default => 'غير محدد',
        };
    }
}
