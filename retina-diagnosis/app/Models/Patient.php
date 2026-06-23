<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'full_name',
        'national_id',
        'birth_date',
        'gender',
        'phone',
        'email',
        'address',
        'blood_type',
        'diabetes_type',
        'medical_history',
        'current_symptoms',
        'medical_notes',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }
}
