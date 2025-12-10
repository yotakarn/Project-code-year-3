<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'dentist_id',
        'patient_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    function isAdministrator(): bool
    {

        return $this->role === 'ADMIN';
    }

    function isDentist(): bool
    {

        return $this->role === 'DENTIST';
    }

    function isPatient(): bool
    {

        return $this->role === 'PATIENT';
    }

    function dentist(): BelongsTo {
        return $this->belongsTo(Dentist::class, 'dentist_id');
    }
    
    function patient(): BelongsTo {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
