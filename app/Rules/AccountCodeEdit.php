<?php

namespace App\Rules;

use App\Account;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AccountCodeEdit implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $account_id;

    public function __construct($account_id)
    {
        $this->account_id=$account_id;
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
            if ($account->code === $value   ){
                if($account->id == $this->account_id)
                    return true;
                return false;
            }
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
        return 'Code already in use.';
    }
}
