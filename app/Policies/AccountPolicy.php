<?php

namespace App\Policies;

use App\Http\Middleware\isAssociateOf;
use App\User;
use App\Account;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the account.
     *
     * @param  \App\User  $user
     * @param  \App\Account  $account
     * @return mixed
     */
    public function deleteCloseOrReopen(User $user, Account $account)
    {
        return $user->id==$account->user->id;
    }

    public function viewMovements(User $user, Account $account){
        return $user->id==$account->user->id;
    }

    public function accountBeDeleted(User $user, Account $account){
        return count($account->movements()->get())==0 && $account->last_movement_date==null;
    }


}
