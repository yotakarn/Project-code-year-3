<?php

namespace App\Policies;
use App\Models\Appointment;

use App\Models\User;

class AppointmentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    function list(User $user, Appointment $appointment): bool
    {
        // Admin สามารถดูได้ทุกนัดหมาย 
        if ($user->role === 'admin') { 
            return true; 
        } 
        // Dentist สามารถดูได้เฉพาะนัดหมายของตัวเอง 
        if ($user->role === 'dentist') { 
            return $user->dentist_id === $appointment->dentist_id; 
        } 
        // Patient สามารถดูได้เฉพาะนัดหมายของตัวเอง 
        if ($user->role === 'patient') { 
            return $user->patient_id === $appointment->patient_id; 
        } 
        return false;
    }

    function view(): bool
    {
        return true;
    }

    function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    function update(User $user): bool
    {
        return $this->create($user);
    }

    function delete(User $user): bool
    {
        return $this->update($user);
    }

}
