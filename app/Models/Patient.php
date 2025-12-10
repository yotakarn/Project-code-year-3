<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $primaryKey = 'patient_id';
    public $timestamps = false;

    protected $fillable = ['patient_code', 'patient_name', 'gender', 'date_birth', 'age', 'identification_number', 'phone_number', 'address', 'blood_group', 'medical_condition', 'drug_allergy' ];

    function dentists(): BelongsToMany
    {
        return $this->belongsToMany(
            Dentist::class,
            'appointments',     // 1. ระบุชื่อตาราง Pivot
            'patient_id',       // 2. Foreign key ของ Model นี้ (Patient) ในตาราง Pivot
            'dentist_id'        // 3. Foreign key ของ Model ที่มาเชื่อม (Dentist) ในตาราง Pivot
        );
    }

    function appointments(): HasMany
    {
        // ระบุ Foreign key และ Local key ให้ชัดเจน
        return $this->hasMany(Appointment::class, 'patient_id', 'patient_id');
    }

    function user(): HasOne {
        return $this->hasOne(User::class, 'patient_id', 'patient_id');
    }
}
