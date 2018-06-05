<?php

namespace App\Http\Controllers;

use App\Account_type;
use App\Policies\AccountPolicy;
use App\Rules\AccountCode;
use App\Rules\AccountCodeEdit;
use App\User;
use Auth;
use App\Account;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'associateOf'])->only('getUserAccounts', 'getUserAccountsOpen', 'getUserAccountsClose');
        $this->middleware('auth');
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
        return view('accounts.createAccount', compact( 'accountTypes'))->with('token');
    }

    public function store(Request $request)
    {
        $data=$request->validate([
            'account_type_id' =>'required|exists:account_types,id',
            'code'=>['required', new AccountCode],
            'date'=>'nullable|date',
            'start_balance'=>'required|numeric',
            'description'=>'nullable|string',
        ]);

        $account = new Account();
        $account->fill($data);
        if (!array_key_exists('date', $data) || $data['date']==null){
            $account->date = Carbon::now()->format('Y-m-d');
        }
        $account->owner_id=Auth::user()->id;
        $account->current_balance=$account->start_balance;
        $account->save();



        return redirect()->route('accounts.users',Auth::user()->id)->with('success', 'Account added successfully!');
    }

    public function showEdit(Account $account){
        if (Auth::user()->id == $account->user->id){
            $accountTypes= Account_type::all();
            return view('accounts.edit', compact('account', 'accountTypes'));
        }
        $error = "You cant edit an account that doesn't belong to you!";
        return Response::make(view('home', compact('error')), 403);
    }

    public function edit2(Request $request){
        if(!$account = Account::findOrFail($request->route('account'))){
            $error = "Invalid account!";
            return Response::make(view('home', compact('error')), 404);
        };
        if (Auth::user()->id != $account->user->id){
            $error = "You cant edit an account that doesn't belong to you!";
            return Response::make(view('home', compact('error')), 403);
        }
        $data = $request->validate([
            'account_type_id' => 'required|exists:account_types,id',
            'code' => ['required', new AccountCodeEdit($account->id)],
            'date' => 'required|date',
            'start_balance' => 'required|numeric',
            'description' => 'nullable|string'
        ]);
        $account->account_type_id = $data['account_type_id'];
        $account->code = $data['code'];
        $account->start_balance = $data['start_balance'];
        $account->date = $data['date'];

        if (array_key_exists('description', $data) || $data['description']=!null){
            $account->description = $data['description'];
        }
        $last_end_balance = $account->start_balance;
        //update every value
        $movements = $account->movements()->orderBy('date', 'desc')->get()->reverse(true);
        foreach ($movements as $movement){
            $movement->start_balance =$last_end_balance;
            if ($movement->type == "expense"){
                $movement->end_balance = $movement->start_balance  - $movement->value;
            }else{
                $movement->end_balance = $movement->start_balance  + $movement->value;
            }
            $last_end_balance=$movement->end_balance;
            $movement->save();
        }
        $account->current_balance = $last_end_balance;
        $account->save();


        return redirect()->route('accounts.users',Auth::user()->id)->with('success', 'Account added successfully!');
    }



}
