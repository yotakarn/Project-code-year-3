<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
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

    function view(User $user): bool
    {
        return $this->list($user);
    }

    function create(User $user): bool
    {
        return $this->view($user);
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
