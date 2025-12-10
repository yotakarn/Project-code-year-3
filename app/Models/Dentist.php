<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dentist extends Model
{
    protected $primaryKey = 'dentist_id';
    public $timestamps = false;

    protected $fillable = ['dentist_code','dentist_name', 'dentist_department', 'phone_number', 'description' ];

    function patients(): BelongsToMany
    {
        return $this->belongsToMany(
            Patient::class,
            'appointments',     // 1. ระบุชื่อตาราง Pivot
            'dentist_id',       // 2. Foreign key ของ Model นี้ (Dentist) ในตาราง Pivot
            'patient_id'        // 3. Foreign key ของ Model ที่มาเชื่อม (Patient) ในตาราง Pivot
        );
    }

    function appointments(): HasMany
    {
        // ระบุ Foreign key และ Local key ให้ชัดเจน
        return $this->hasMany(Appointment::class, 'dentist_id', 'dentist_id');
    }

    function user(): HasOne {
        return $this->hasOne(User::class, 'dentist_id', 'dentist_id');
    }
    
}
