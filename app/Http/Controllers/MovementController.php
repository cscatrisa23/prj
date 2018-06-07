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
            $movements = $account->movements()->orderBy('date', 'desc')->orderByDesc('created_at', 'desc')->paginate(10);

            return view('movements.list', compact('movements', 'account'));
        }
        $error = "You can't list movements from an account that doesn't belong to you!";
        return Response::make(view('home', compact('error')), 403);
    }

    public function create(Account $account){

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
            $movement->movement_category_id=$data['movement_category_id'];
            $movement->type = MovementCategories::where('id', $data['movement_category_id'])->value('type');
            $movement->account_id = $account->id;
            $movement->created_at= Carbon::now();

            if ($request->has('description')){
                $movement['description'] = $data['description'];
            }

            if (count($account->movements()->where('date','<', $movement->date)->get())>0){
                $movement->start_balance = $account->movements()->orderBy('id', 'desc')->orderBy('date')->orderByDesc('created_at', 'desc')->where('date', '<=', $movement->date)->first()->end_balance;
            }else{
                $movement->start_balance= $account->start_balance;
            }
            if ($movement->type == "expense"){
                $movement->end_balance = $movement->start_balance - $movement->value;
            }else{
                $movement->end_balance = $movement->start_balance + $movement->value;
            }

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
                $movement->save();

                Storage::putFileAs('documents/'.$account->id.'/',$file,$movement->id.'.'.$fileExtension);
            }

            $movementsAfter = $account->movements()->where('id', '!=', $movement->id)->where('date','>', $movement->date)->orderBy('date')->orderBy('created_at')->get();
            $last_end_balance = $movement->end_balance;
            foreach ($movementsAfter as $movementAfter){
                $movementAfter->start_balance = $last_end_balance;
                if ($movementAfter->type == "expense"){
                    $movementAfter->end_balance = $movementAfter->start_balance - $movementAfter->value;
                }else{
                    $movementAfter->end_balance = $movementAfter->start_balance + $movementAfter->value;
                }
                $last_end_balance= $movementAfter->end_balance;
                $movementAfter->save();
            }

            $account->current_balance = $last_end_balance;
            $account->save();
            $movement->save();

            return redirect()->route('movement.list',$account->id)->with('status', 'Movement added with success!');
        }else {
            $error = "You can't list movements from an account that doesn't belong to you!";
            return Response::make(view('home', compact('error')), 403);
        }
    }

    public function edit(Movement $movement){

        $account = Account::findOrFail($movement->account_id);

        if (Auth::user()->id == $account->user->id) {
                $movementCategories = MovementCategories::all();
                $user = $account->user;
                return view('movements.edit', compact('movementCategories', 'account', 'user', 'movement'))->with('token');
            }
            $error = "You can't edit a movement to an account that doesn't belong to you!";
            return Response::make(view('home', compact('error')), 403);
    }

    public function update(Movement $movement, Request $request){

        $account = Account::findOrFail($movement->account_id);

        if(Auth::user()->id == $account->owner_id){
            $data = $request->validate([
                'movement_category_id' => 'required|exists:movement_categories,id',
                'date' => 'required|date|before:tomorrow',
                'value' => 'required|numeric|min:0.01',
                'description' => 'nullable|string|max:255',
                'document_file' => 'nullable|file|mimes:pdf,png,jpeg|required_with:document_description',
                'document_description'=> 'nullable|string|max:255',
            ]);

            $movement->date=$data['date'];
            $movement->value=$data['value'];
            $movement->movement_category_id=$data['movement_category_id'];
            $movement->type = MovementCategories::where('id', $data['movement_category_id'])->value('type');


            if ($request->has('description')){
                $movement->description = $data['description'];
            }

            if ($movement->type == "expense"){
                $movement->end_balance = $movement->start_balance - $movement->value;
            }else{
                $movement->end_balance = $movement->start_balance + $movement->value;
            }

            if ($request->has('document_file')) {
                $file = $data['document_file'];
                $fileExtension = $file->getClientOriginalExtension();
                $originalFilename = $file->getClientOriginalName();

                if ($movement->document_id != null) {
                    Storage::delete('documents/' . $account->id . '/' . $movement->id . '.' . $movement->document->type);
                    Document::where('id', $movement->document->id)->update([
                        'type' => $fileExtension,
                        'original_name' => $originalFilename,
                        'description' => $data['document_description']
                    ]);
                    $movement->save();
                } else {
                    $document = new Document([
                        'type' => $fileExtension,
                        'original_name' => $originalFilename,
                        'description' => $data['document_description'],
                        'created_at' => Carbon::now()
                    ]);
                    $document->save();
                    $movement->document_id = $document->id;
                    $movement->save();
                }
                Storage::putFileAs('documents/' . $account->id . '/', $file, $movement->id . '.' . $fileExtension);
            }
            $movement->save();

            return redirect()->route('movement.list',$account->id)->with('status', 'Movement updated with success!');
        }else{
            $error = "You can't list movements from an account that doesn't belong to you!";
            return Response::make(view('home', compact('error')), 403);
        }
    }

    public function destroy(Movement $movement){

        $account = Account::findOrFail($movement->account_id);

        if (Auth::user()->can('deleteMovement', $account)){

            $docDelete = Document::find($movement->document_id);
            Storage::delete('documents/'. $movement->account_id.'/'.$movement->id .'.'.$docDelete['type']);
            $movement->delete();

            return redirect()->back()->with('status', 'You have successfully deleted the movement \''. $movement->id.'\'');
        }
        $error = "You can't delete movements from an account that doesn't belong to you!";
        return Response::make(view('home', compact('error')), 403);
    }
}
