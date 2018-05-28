<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    public function block(User $user)
    {
        return Auth::user()->id != $user->id && Auth::user()->isAdministrator();
    }

    public function unblock(User $user)
    {
        return $this->isAdministrator() && !($this->id == $user->id);
    }

    public function demote(User $user)
    {
        return Auth::user()->id != $user->id && Auth::user()->isAdministrator();
    }
    //Para ver os users a bloquear, desbloquear etc, tem que ser admin
    public function accessUsers(User $user)
    {
        return $user->isAdministrator();
    }
}
