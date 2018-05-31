<?php

namespace App\Http\Controllers;

use App\Account;
use App\Movement;
use Auth;
use Illuminate\Support\Facades\Response;
use App\MovementCategories;

use Illuminate\Http\Request;

class MovementController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth'])->only('listMovements');
    }

    public function listMovements(Account $account){
        if (Auth::user()->can('viewMovements', $account)) {
            $movements = $account->movements()->orderBy('date', 'desc')->paginate(10);

            return view('movements.listMovs', compact('movements', 'account'));
        }
        $error = "You can't list movements from an account that doesn't belong to you!";
        return Response::make(view('home', compact('error')), 403);
    }

    public function create(Account $account){

        $movementCategories  = MovementCategories::all();
        $user=$account->user;
        return view('movements.create', compact('movementCategories', 'account', 'user'))->with('token');
    }

    protected function store(Request $request)
    {
        $account = $request->route('account');

        if(Auth::user()->can('addMovement', $account)){
            $data = $request->validate([
                'movement_category_id' => 'required|Exists:movement_categories,id',
                'date' => 'required|date|before:tomorrow',
                'value' => 'required|numeric|min:0.01',
                'description' => 'nullable|string|max:255',
                'document_file' => 'file|mimes:pdf,png,jpeg|required_with:document_description',
                'document_description'=> 'required_if:document_file, file',
            ]);

            $movement = new Movement();
            $movement->fill($data);
            $movement->account_id = $account->id;
            $movement->type = MovementCategories::where('id', $data['movement_category_id'])->pluck('type');

            $movement->save();



        }else {
            $error = "You can't list movements from an account that doesn't belong to you!";
            return Response::make(view('home', compact('error')), 403);
        }
    }

    public function editMovement(Movement $movement){

    }

    public function updateMovement(Movement $movement){

    }

    public function destroyMovement(Movement $movement){

    }
}
