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
        $this->middleware(['auth']);
    }

    public function listMovements(Account $account){
        if (Auth::user()->can('viewMovements', $account)) {
            $movements = $account->movements()->orderBy('date', 'desc')->paginate(10);

            return view('movements.list', compact('movements', 'account'));
        }
        $error = "You can't list movements from an account that doesn't belong to you!";
        return Response::make(view('home', compact('error')), 403);
    }

    public function create(Account $account){

        //if (Auth::user()->can('addMovements', $account)) {
        if (Auth::user()->id == $account->user->id) {
            $movementCategories = MovementCategories::all();
            $user = $account->user;
            return view('movements.create', compact('movementCategories', 'account', 'user'))->with('token');
        }
        $error = "You can't create a movement to an account that doesn't belong to you!";
        return Response::make(view('home', compact('error')), 403);
    }

    protected function store(Request $request)
    {
        $account = $request->route('account');
        if (!Account::findOrFail($account->id)){
            $error = "You can't create a movement to an account that doesn't belong to you!";
            return Response::make(view('home', compact('error')), 404);
        }

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
            $movementCategories  = MovementCategories::all();
            $movement->account_id = $account->id;
            $movement->type = MovementCategories::where('id', $data['movement_category_id'])->pluck('type');

            $movement->save();

            foreach($movementCategories as $moveCat) {
                if ($moveCat->id == $data['movement_category_id']) {
                    $moveCat->type;
                }
            }

            if ($data['type'] == 'expense'){
                $data['end_balance'] = $data['start_balance'] - $data['value'];
            } elseif ($data['type'] == 'revenue'){
                $data['end_balance'] = $data['start_balance'] + $data['value'];
            }

            return redirect()->route('movement.list',Auth::user()->id)->with('success', 'Movement added successfully!');

        }else {
            $error = "You can't list movements from an account that doesn't belong to you!";
            return Response::make(view('home', compact('error')), 403);
        }
    }

    public function edit(Movement $movement){

        if (Auth::user()->can('addMovements', $movement)) {
            $movementCategories = MovementCategories::all();
            $user = $movement->account->user;
            return view('movements.create', compact('movementCategories', 'account', 'user'))->with('token');
        }
        $error = "You can't list movements from an account that doesn't belong to you!";
        return Response::make(view('home', compact('error')), 403);
    }

    public function update(Movement $movement){

    }

    public function destroy(Movement $movement){

    }
}
