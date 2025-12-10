<?php

namespace App\Policies;

use App\Models\User;

class PatientPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    function list(User $user): bool
    {
        return $user->isAdministrator();
    }

    function view(): bool
    {
        return true;
    }

    function add(User $user): bool
    {
        return $user->isAdministrator();
    }

    function update(User $user): bool
    {
        return $this->add($user);
    }

}
