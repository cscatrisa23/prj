<?php

namespace App\Http\Controllers;

use App\Policies\AccountPolicy;
use App\User;
use Auth;
use App\Account;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'associateOf'])->only('getUserAccounts', 'getUserAccountsOpen', 'getUserAccountsClose');
        $this->middleware('auth')->only('deleteAccount', 'closeAccount', 'reopenAccount', 'create', 'store');
    }

    public function getUserAccounts(User $user){
        $accounts = Account::where('owner_id', $user->id)->get();
        return view('accounts.list', compact('accounts', 'user'));
    }

    public function closeAccount(Account $account){
        if (Auth::user()->can('deleteCloseOrReopen', $account)){
            if (!$account->isOpen()){
                return redirect()->back()->with('error', 'The account is already closed');
            }
            $account->close();
            return redirect()->back()->with('status', 'You have successfully closed the account \''. $account->code.'\'');
        }

        $error = "You cant close an account that doesn't belong to you!";
        return Response::make(view('home', compact('error')), 403);
    }

    public function deleteAccount(Account $account){
        if (Auth::user()->can('deleteCloseOrReopen', $account)){

            if (Auth::user()->can('accountBeDeleted', $account)){
                $account->delete();
                return redirect()->back()->with('status', 'You have successfully deleted the account \''. $account->code.'\'');
            }
            else //nao tem movimentos associados
            {
                return redirect()->route('accounts.users', [$account->user])->with('error', 'You cant delete an account that has movements!');
            }
        }
        $error = "You cant delete an account that doesn't belong to you!";
        return Response::make(view('home', compact('error')), 403);
    }

    public function reopenAccount(Account $account){
        if (Auth::user()->can('deleteCloseOrReopen', $account)){
            if ($account->isOpen()){
                return redirect()->back()->with('error', 'The account is already open');
            }
            $account->reopen();
            return redirect()->back()->with('status', 'You have successfully reopened the account \''. $account->code.'\'');
        }

        $error = "You cant reopen an account that doesn't belong to you!";
        return Response::make(view('home', compact('error')), 403);
    }

    public function getUserAccountsOpen(User $user){
        $accounts = Account::where('owner_id', $user->id)->whereNull('deleted_at')->get();
        return view('accounts.list', compact('accounts', 'user'));
    }

    public function getUserAccountsClose(User $user){
        $accounts = Account::where('owner_id', $user->id)->whereNotNull('deleted_at')->get();
        return view('accounts.list', compact('accounts', 'user'));
    }


    public function create()
    {
        $accountTypes= Account_type::all();
        return view('accounts.createAccount', compact( 'accountTypes'));
    }

    public function store(Request $request)
    {

        $data=$request->validate([
            'account_type_id' =>'required',
            'code'=>'required|unique:accounts',
            'date'=>'date',
            'start_balance'=>'numeric|required',
            'description'=>'nullable',
        ], [
            'account_type_id.required' => 'The account type is required',
            'account_type_id.exists' => 'The account type already exists',
            'code.required' => 'The code is required',
            'date.date' => 'The date is invalid',
            'start_balance.required'=> 'The start balance is required',
        ]);

        $account = new Account();
        $account->fill($data);
        $account->owner_id=Auth::user()->id;
        $account->current_balance=$account->start_balance;
        $account->save();

        $data['current_balance']=$request->input('start_balance');
        $data['owner_id']=Auth::user()->id;

        return redirect()->route('accounts.users',Auth::user()->id)->with('success', 'Account added successfully!');
    }

}
