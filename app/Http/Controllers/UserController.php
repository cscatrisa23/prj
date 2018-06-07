<?php

namespace App\Http\Controllers;

use App\Account;
use App\Rules\SameEmail;
use App\Rules\VerifyOldPassword;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

use App\Policies\UserPolicy;

use Intervention\Image\ImageManagerStatic as Image;



class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin'])->only('list');
        $this->middleware(['auth', 'admin','canPerformAction'])->only('demoteUser', 'blockUser', 'unblockUser', 'promoteUser');
        $this->middleware('auth')->only('getProfiles', 'getAssociates', 'getAssociate_of', 'changePassword', 'editMyProfile', 'showEditMyProfile');
    }

    public function list(Request $request){
        $users = $this->listFilter($request);
        return view('users.list', compact( 'users'));
    }

    public function listFilter(Request $request)
    {
        //Somente o campo nome preenchido
        if ($request->filled('name') && !$request->filled('type') && !$request->filled('status'))
            return User::where('name', 'like', "%{$request->query('name')}%")->get();

        //Somente o tipo preenchido
        if ($request->filled('type') && !$request->filled('name') && !$request->filled('status')){
            if ($request->query('type') == "admin")
                return User::where('admin', 1)->get();
            if ($request->query('type') == "normal")
                return User::where('admin', 0)->get();
        }

        //Somente o status preenchido
        if ($request->filled('status') && !$request->filled('type') && !$request->filled('name')){
            if ($request->query('status') == "blocked")
                return User::where('blocked', 1)->get();
            if ($request->query('status') == "unblocked")
                return User::where('blocked', 0)->get();
        }

        //Nome e status preenchido
        if ($request->filled('name') && $request->filled('status') && !$request->filled('type')){
            if ($request->query('status') == "blocked")
                return User::where('blocked', 1)->where('name', 'like', "%{$request->query('name')}%")->get();
            if ($request->query('status') == "unblocked")
                return User::where('blocked', 0)->where('name', 'like', "%{$request->query('name')}%")->get();
        }

        //Tipo e status
        if(!$request->filled('name')&& $request->filled('type') && $request->query('type')=='admin' && $request->filled('status')){
            //admin e blocked
            if ($request->query('status') == "blocked")
                return User::where('admin', 1)->where('blocked',1)->get();
            //admin e unblocked
            if ($request->query('status') == "unblocked")
                return User::where('admin', 1)->where('blocked',0)->get();
        }

        if(!$request->filled('name')&& $request->filled('type') && $request->query('type')=='normal' && $request->filled('status')){
            //normal e unblocked
            if ($request->query('status') == "unblocked")
                return User::where('admin', 0)->where('blocked',0)->get();
            //normal e blocked
            if ($request->query('status') == "blocked")
                return User::where('admin', 0)->where('blocked',1)->get();
        }
        //Nome e tipo preenchido
        if ($request->filled('name') && !$request->filled('status') && $request->filled('type')){
            if ($request->query('type') == "admin")
                return User::where('admin', 1)->where('name', 'like', "%{$request->query('name')}%")->get();
            if ($request->query('type') == "normal")
                return User::where('admin', 0)->where('name', 'like', "%{$request->query('name')}%")->get();
        }

        //Todos preenchidos
        if ($request->filled('name') && $request->filled('status') && $request->filled('type')){
            //nome + admin + blocked
            if($request->query('type')=='admin'){
                if($request->query('status')=='blocked'){
                    return User::where('name','like','%'.$request->query('name').'%')->where('admin', 1)->where('blocked', 1)->get();
                }
            }
            //nome + admin + unblocked
            if($request->query('type')=='admin'){
                if($request->query('status')=='unblocked'){
                    return User::where('name','like','%'.$request->query('name').'%')->where('admin', 1)->where('blocked', 0)->get();
                }
            }
            //nome + normal + blocked
            if($request->query('type')=='normal'){
                if($request->query('status')=='blocked'){
                    return User::where('name','like','%'.$request->query('name').'%')->where('admin', 0)->where('blocked', 1)->get();
                }
            }
            //nome + normal + unblocked
            if($request->query('type')=='normal'){
                if($request->query('status')=='unblocked'){
                    return User::where('name','like','%'.$request->query('name').'%')->where('admin', 0)->where('blocked', 0)->get();
                }
            }
        }

        //Nenhum dos campos preenchidos
        return User::all();
    }

    public function blockUser(User $user){
        $user->block();
        return redirect()->back()->with('status', 'User '. $user->name. ' blocked with success!');
    }

    public function unblockUser(User $user){
       $user->unblock();
       return redirect()->back()->with('status', 'User '. $user->name. ' unblocked with success!');
    }

    public function promoteUser(User $user){
        $user->promote();
        return redirect()->back()->with('status', 'User '. $user->name. ' promoted with success!');
    }

    public function demoteUser(User $user){
        $user->demote();
        return redirect()->back()->with('status', 'User '. $user->name. ' demoted with success!');
    }

    public function changePasswordForm(){
        return view('auth.passwords.changePasswordForm');
    }

    public function getProfiles(Request $request){
        $users = $this->profilesFilterByName($request);
        $encontrou=false;
        $associates = DB::table('associate_members')->where('main_user_id', Auth::user()->id)->get();
        $associate_of = DB::table('associate_members')->where('associated_user_id', Auth::user()->id)->get();
        return view('users.profiles', compact('users', 'associates', 'associate_of', 'encontrou'));
    }

    public function profilesFilterByName(Request $request){
        //Somente o campo nome preenchido
        if ($request->filled('name'))
            return User::where('name', 'like', "%{$request->query('name')}%")->get();
        return User::all();
    }

    public function getAssociates(){
        $associatesUsers= Auth::user()->associateds;
        return view('me.listAssociates', compact( 'associatesUsers'));
    }

    public function editMyProfile(Request $request){
        $user = Auth::user();
        $validatedData= $request->validate([
            'name' => 'required|string|regex:/^[a-zA-Z ]+$/|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|regex:/^[0-9 +\s]+$/',
            'profile_photo' => 'nullable|mimes:jpeg,bmp,png,jpg'
        ]);

        $filename = null;
        if(array_key_exists('profile_photo', $validatedData)) {
            $avatar = $validatedData['profile_photo'];
            do {
                $filename = str_random(32) . '.' . $avatar->getClientOriginalExtension();
            }while(count(User::where('profile_photo', $filename)->get())>0);
            Storage::disk('public')->putFileAs('profiles', $avatar, $filename);
        }
        if ($validatedData['name']!=null) {
            $user->name=$validatedData['name'];
        }
        if ($validatedData['email']!=null) {
            $user->email=$validatedData['email'];
        }
        if (array_key_exists('phone', $validatedData)) {
            $user->phone=$validatedData['phone'];
        }else{
            $user->phone=null;
        }
        if (array_key_exists('profile_photo', $validatedData) && $validatedData['profile_photo']!=null) {
            $user->profile_photo=$filename;
        }
        $user->save();
        return redirect()->route('home')->with('message', 'User updated with success!');
    }

    public function showEditMyProfile(){
        $user = Auth::user();
        return view('me.editProfile', compact('user'))->with('token');
    }

    public function getAssociate_of(){
        $associate_ofUsers= Auth::user()->associated_of;
        return view('me.listAssociate_of', compact( 'associate_ofUsers'));
    }

    public function changePassword(Request $request){
        $request->validate([
            'old_password'=>['required',new VerifyOldPassword],
            'email' => new SameEmail,
            'password'=>'required|confirmed|min:3|different:old_password',
            'password_confirmation'=>'required|same:password',
        ]);

        $user=User::findOrFail(Auth::user()->id);
        $user->password=Hash::make($request->input('password'));
        $user->save();
        return redirect()->route('home')->with('message', 'Password Changed');
    }

    public function statistics(Request $request){


        $id= $request->route('user');
        $accounts= Account::where('owner_id', $id)->get();
        $numberOfAccounts = Account::where('owner_id', $id)->count();
        $username= DB::table('users')->where('id', $id)->value('name');


        $totalBalance=0;
        foreach ($accounts as $account)
        {
            $totalBalance+=$account->current_balance;
        }

        return view('users.statistics', compact('username','numberOfAccounts','totalBalance', 'accounts'));
    }



}
