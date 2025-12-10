<?php

namespace App\Policies;

use App\Models\User;

class DentistPolicy
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

    function delete(User $user): bool
    {
        return $this->update($user);
    }
}
