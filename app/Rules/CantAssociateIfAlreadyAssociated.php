<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CantAssociateIfAlreadyAssociated implements Rule
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
        $user = User::find($value);
        return (count(DB::table('associate_members')->where('associated_user_id', $value)->where('main_user_id', Auth::user()->id)->get())==0);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Already associated with.';
    }
}
