<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('admin')->only('index', 'blockUser');
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

    public function changePassword(User $user){

    }
}
