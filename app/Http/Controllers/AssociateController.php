<?php

namespace App\Http\Controllers;

use App\AssociateMember;
use App\Rules\CantAssociateHimself;
use App\Rules\CantAssociateIfAlreadyAssociated;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AssociateController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }

    public function add(Request $request){
         $main_user = Auth::user();
         $data = $request->validate([
             'associated_user' => ['required', 'exists:users,id', new CantAssociateHimself, new CantAssociateIfAlreadyAssociated],
         ]);

         $association = new AssociateMember([
             'main_user_id' => $main_user->id,
             'associated_user_id' => $data['associated_user'],
             'created_at' => Carbon::now()
         ]);
         $association->save();
         return redirect()->back()->with('status', 'User associated with success');
    }

    public function remove($id){
        if(!$user = User::findOrFail($id)){
            $error="Invalid user. Failed to remove association";
            return redirect()->back()->with('error', $error);
        };
        $association = AssociateMember::where('main_user_id', Auth::user()->id)->where('associated_user_id', $id);
        if (count($association->get())) {
            $association->delete();
        } else {
            $error = "Invalid user.";
            return Response::make(view('home', compact('error')), 404);
        }
//        return redirect()->route('accounts.users',Auth::user()->id)->with('status', 'Account updated successfully!');

        return redirect()->back()->with('status', 'Association removed with success!');
    }
}
