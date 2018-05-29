<?php

namespace App\Http\Controllers;

use App\Account;
use App\Movement;
use Auth;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;

class MovementController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only('listMovements');
    }

    public function listMovements(Account $account){
        if (Auth::user()->can('viewMovements', $account)) {
            $movements = $account->movements()->orderBy('date', 'desc')->get();

            return view('movements.list', compact('movements', 'account'));
        }
        $error = "You can't list movements from an account that doesn't belong to you!";
        return Response::make(view('home', compact('error')), 403);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'description_category_id'=>''
        ]);
    }

    protected function createMovement(array $data)
    {
        $filename = null;

        return User::create([
            'description_category_id' => $data['description_category_id'],
            'date' => '',
            ''
        ]);
    }

    public function showCreateMovement(Account $account){

    }

    public function editMovement(Movement $movement){

    }

    public function updateMovement(Movement $movement){

    }

    public function destroyMovement(Movement $movement){

    }
}
