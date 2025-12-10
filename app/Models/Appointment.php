<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $primaryKey = 'appointment_id';
    public $timestamps = false;

    protected $fillable = ['appointment_code','dentist_id', 'patient_id','appointment_date', 'appointment_time', 'description'  ];

    function dentist(): BelongsTo
    {
        // (Foreign Key, Owner Key/Primary Key ของ Dentist)
        return $this->belongsTo(Dentist::class, 'dentist_id', 'dentist_id');
    }

    function patient(): BelongsTo
    {
        // (Foreign Key, Owner Key/Primary Key ของ Patient)
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }
}
