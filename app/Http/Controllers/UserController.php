<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->only('index', 'blockUser', 'promoteUser', 'unblockUser', 'demoteUser');
    }

    public function index(){
        $pagetittle= "List of users";
        $users = User::all();
        return view('users.index', compact('pagetittle', 'users'));
    }

    public function blockUser(User $user){
        $user->block();
        return redirect()->back();
    }

    public function unblockUser(User $user){
        $user->unblock();
        return redirect()->back();
    }
    public function promoteUser(User $user){
        $user->promote();
        return redirect()->back();
    }

    public function demoteUser(User $user){
        $user->demote();
        return redirect()->back();
    }

    public function changePasswordForm(){
        return view('auth.passwords.reset');
    }

    public function getProfiles(){
        $users = User::all();
        $associates = DB::table('associate_members')->where('main_user_id', Auth::user()->id)->get();
        $associate_of = DB::table('associate_members')->where('associated_user_id', Auth::user()->id)->get();

        return view('users.profiles', compact('users', 'associates', 'associate_of'));

    }


    public function changePassword(User $user){

        $request = request();

        $validatedData=$request->validate([
            'old_password'=>'required',
            'password'=>'required|confirmed|min:6|different:old_password',
            'password_confirmation'=>'required|same:password',
        ]);
        $user_id=Auth::user()->id;
        $user->password=Hash::make($request->input('password'));
        $user->save();
    }
}
