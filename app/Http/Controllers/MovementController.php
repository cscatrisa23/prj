<?php

namespace App\Http\Controllers;

use App\Account;
use App\Movement;
use Illuminate\Http\Request;

class MovementController extends Controller
{

    public function listMovements(Account $account){

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
