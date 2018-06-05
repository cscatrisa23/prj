<?php

namespace App\Http\Controllers;

use App\Account;
use App\Document;
use App\Movement;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use App\MovementCategories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $account = Account::findOrFail($request->route('account'));

        if(Auth::user()->id == $account->user->id){

            $data = $request->validate([
                'movement_category_id' => 'required|Exists:movement_categories,id',
                'date' => 'required|date|before:tomorrow',
                'value' => 'required|numeric|min:0.01',
                'description' => 'nullable|string|max:255',
                'document_file' => 'nullable|file|mimes:pdf,png,jpeg|required_with:document_description',
                'document_description'=> 'nullable|string|max:255',
            ]);

            $movement = new Movement();
            $movement->date=$data['date'];
            $movement->value=$data['value'];
            //$movement->description=$data['description'];
            $movement->movement_category_id=$data['movement_category_id'];
            $movement->type = MovementCategories::where('id', $data['movement_category_id'])->value('type');
            $movement->account_id = $account->id;
            $movement->start_balance = $account->current_balance;
            $movement->created_at= Carbon::now();

            if ($request->has('description')){
                $movement['description'] = $data['description'];
            }

            if ($movement->type == 'expense') {
                $movement->end_balance= $account->current_balance - $data['value'];
            } else {
                $movement->end_balance = $account->current_balance + $data['value'];
            }

            $movement->save();

            $account->current_balance = $movement->end_balance;
            $account->save();

            if(array_key_exists('document_file', $data)) {
                $file = $data['document_file'];
                $fileExtension = $file->getClientOriginalExtension();
                $originalFilename = $file->getClientOriginalName();

                $document = new Document([
                    'type' => $fileExtension,
                    'original_name' => $originalFilename,
                    'description' => $data['document_description'],
                    'created_at' => Carbon::now()
                ]);
                $document->save();
                $movement->document_id = $document->id;
                Storage::putFileAs('documents/'.$account->id.'/',$file,$movement->id.'.'.$fileExtension);
                $movement->save();

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

    public function update(Movement $movement, Request $request){

        $account = Account::findOrFail($request->route('account'));

        if(Auth::user()->id == $account->user->id){


        }else {
            $error = "You can't list movements from an account that doesn't belong to you!";
            return Response::make(view('home', compact('error')), 403);
        }
    }

    public function destroy(Movement $movement, Request $request){

        $account = Account::findOrFail($movement->account_id);

        if (Auth::user()->can('deleteMovement', $account )){

            $docDelete = Document::find($movement->document_id);
            Storage::delete('documents/'. $movement->account_id.'/'.$movement->id .'.'.$docDelete['type']);
            $movement->delete();

            return redirect()->back()->with('status', 'You have successfully deleted the movement \''. $movement->id.'\'');
        }
        $error = "You can't delete movements from an account that doesn't belong to you!";
        return Response::make(view('home', compact('error')), 403);
    }
}
