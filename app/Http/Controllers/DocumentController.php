<?php

namespace App\Http\Controllers;

use App\Document;
use App\Movement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{

    public function __construct()
    {
       $this->middleware('auth');
    }

    public function create(Movement $movement)
    {
        if (Auth::user()->id == $movement->account->user->id && $movement->account->isOpen()) {
            return view('documents.add', compact('movement'))->with('token');
        }
        $error = "You can't add documents to a movement that doesn't belong to you!";
        return Response::make(view('home', compact('error')), 403);
    }

    public function store(Request $request)
    {
        $movement = Movement::findOrFail($request->route('movement'));
        $account = $movement->account;
        if (Auth::user()->id== $account->user->id && $account->isOpen()){
            $data = $request->validate([
                'document_file' => 'file|mimes:pdf,png,jpeg|required_with:document_description',
                'document_description' => 'nullable|string|max:255',
            ]);
            $file = $data['document_file'];
            $fileExtension = $file->getClientOriginalExtension();
            $originalFilename = $file->getClientOriginalName();

            if ($movement->document_id!=null) {
                Storage::delete('documents/'.$account->id.'/'.$movement->id.'.'.$movement->document->type);
                Document::where('id', $movement->document->id)->update([
                    'type' => $fileExtension,
                    'original_name' => $originalFilename,
                    'description' => $data['document_description']
                ]);
                $movement->save();
            }else {
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
            Storage::putFileAs('documents/'.$account->id.'/',$file,$movement->id.'.'.$fileExtension);
            return redirect()->route('movement.list', $account)->with('status', 'Document added with success!');
        }

        $error ="You can't add documents to a movement that doesn't belong to you!";
        return Response::make(view('home', compact('error')), 403);
    }

    public function delete(Document $document){
        $movement = $document->movement;
        $account = $movement->account;
        if (Auth::user()->id == $account->user->id) {
            Storage::delete('documents/'. $account->id.'/'. $movement->id.'.'. $movement->document->type);
            Movement::where('document_id', $movement->document_id)->update([
                'document_id' => null,
            ]);
            $document->delete();

            return redirect()->route('movement.list', $account)->with('status', 'Document deleted with success!');
        }
        $error = "You can't add movements to an account that doesn't belong to you!";
        return Response::make(view('home', compact('error')), 403);
    }

    public function view(Document $document)
    {
        $movement = $document->movement;
        $account = $movement->account;
        if (Auth::user()->id == $account->owner_id || Auth::user()->isAssociateOf($account->user)) {
            return Storage::download('documents/'.$movement->account_id.'/'.$movement->id.'.'.$document->type, $document->original_name);
        }
        $error = "You don't have enough permissions to download this document";
        return Response::make(view('home', compact('error')), 403);
    }
}
