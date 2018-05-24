<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(){
        $numberOfUsers = DB::table('users')->count();
        $numberOfAccounts = DB::table('accounts')->count();
        $numberOfMovements = DB::table('movements')->count();
        return view('welcome', compact('numberOfUsers', 'numberOfAccounts', 'numberOfMovements'));
    }
}
