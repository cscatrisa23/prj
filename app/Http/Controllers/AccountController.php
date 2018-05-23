<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use App\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'associateOf'])->only('getUserAccounts');
    }

    public function getUserAccounts(User $user){

            $accounts = Account::where('owner_id', $user->id)->get();
            return view('accounts.list', compact('accounts', 'user'));
    }

    public function deleteAccount(Account $account){
        $account->deleteAccount();
        return redirect()->back();
    }
}