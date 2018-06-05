<?php

namespace App\Rules;

use App\Account;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AccountCode implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $accounts = Account::where('owner_id', Auth::user()->id)->get();
        foreach ($accounts as $account){
            if ($account->code == $value)
                return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid account code.';
    }
}
