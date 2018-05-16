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
       User::where("id", $user->id)->update([
           "blocked" => 1
       ]);


    }

    public function unblockUser(User $user){

    }
    public function promoteUser(User $user){

    }
    public function demoteUser(User $user){

    }
}
