<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin'])->only('list', 'blockUser', 'promoteUser', 'unblockUser', 'demoteUser');
    }

    public function list(Request $request){
        $users = $this->listFilter($request);
        return view('users.list', compact( 'users'));
    }

    public function listFilter(Request $request)
    {
        //Somente o campo nome preenchido
        if ($request->filled('name') && !$request->filled('type') && !$request->filled('status'))
            return User::where('name', 'like', "%{$request->query('name')}%")->get();

        //Somente o tipo preenchido
        if ($request->filled('type') && !$request->filled('name') && !$request->filled('status')){
            if ($request->query('type') == "admin")
                return User::where('admin', 1)->get();
            if ($request->query('type') == "normal")
                return User::where('admin', 0)->get();
        }

        //Somente o status preenchido
        if ($request->filled('status') && !$request->filled('type') && !$request->filled('name')){
            if ($request->query('status') == "blocked")
                return User::where('blocked', 1)->get();
            if ($request->query('status') == "unblocked")
                return User::where('blocked', 0)->get();
        }

        //Nome e status preenchido
        if ($request->filled('name') && $request->filled('status') && !$request->filled('type')){
            if ($request->query('status') == "blocked")
                return User::where('blocked', 1)->where('name', 'like', "%{$request->query('name')}%")->get();
            if ($request->query('status') == "unblocked")
                return User::where('blocked', 0)->where('name', 'like', "%{$request->query('name')}%")->get();
        }

        //Tipo e status
        if(!$request->filled('name')&& $request->filled('type') && $request->query('type')=='admin' && $request->filled('status')){
            //admin e blocked
            if ($request->query('status') == "blocked")
                return User::where('admin', 1)->where('blocked',1)->get();
            //admin e unblocked
            if ($request->query('status') == "unblocked")
                return User::where('admin', 1)->where('blocked',0)->get();
        }

        if(!$request->filled('name')&& $request->filled('type') && $request->query('type')=='normal' && $request->filled('status')){
            //normal e unblocked
            if ($request->query('status') == "unblocked")
                return User::where('admin', 0)->where('blocked',0)->get();
            //normal e blocked
            if ($request->query('status') == "blocked")
                return User::where('admin', 0)->where('blocked',1)->get();
        }
        //Nome e tipo preenchido
        if ($request->filled('name') && !$request->filled('status') && $request->filled('type')){
            if ($request->query('type') == "admin")
                return User::where('admin', 1)->where('name', 'like', "%{$request->query('name')}%")->get();
            if ($request->query('type') == "normal")
                return User::where('admin', 0)->where('name', 'like', "%{$request->query('name')}%")->get();
        }

        //Todos preenchidos
        if ($request->filled('name') && $request->filled('status') && $request->filled('type')){
            //nome + admin + blocked
            if($request->query('type')=='admin'){
                if($request->query('status')=='blocked'){
                    return User::where('name','like','%'.$request->query('name').'%')->where('admin', 1)->where('blocked', 1)->get();
                }
            }
            //nome + admin + unblocked
            if($request->query('type')=='admin'){
                if($request->query('status')=='unblocked'){
                    return User::where('name','like','%'.$request->query('name').'%')->where('admin', 1)->where('blocked', 0)->get();
                }
            }
            //nome + normal + blocked
            if($request->query('type')=='normal'){
                if($request->query('status')=='blocked'){
                    return User::where('name','like','%'.$request->query('name').'%')->where('admin', 0)->where('blocked', 1)->get();
                }
            }
            //nome + normal + unblocked
            if($request->query('type')=='normal'){
                if($request->query('status')=='unblocked'){
                    return User::where('name','like','%'.$request->query('name').'%')->where('admin', 0)->where('blocked', 0)->get();
                }
            }
        }

        //Nenhum dos campos preenchidos
        return User::all();
    }

    public function blockUser(User $user){
        $user->block();
        return redirect()->back();
    }

    public function unblockUser(User $user){
        $user->unblock();
        return redirect()->back();
    }
    public function promoteUser(User $user){
        $user->promote();
        return redirect()->back();
    }

    public function demoteUser(User $user){
        $user->demote();
        return redirect()->back();
    }

    public function changePasswordForm(){
        return view('auth.passwords.reset');
    }

    public function getProfiles(Request $request){
        $users = $this->profilesFilterByName($request);
        $associates = DB::table('associate_members')->where('main_user_id', Auth::user()->id)->get();
        $associate_of = DB::table('associate_members')->where('associated_user_id', Auth::user()->id)->get();

        return view('users.profiles', compact('users', 'associates', 'associate_of'));
    }

    public function profilesFilterByName(Request $request){
        $name = $request->query('name');
        if (empty($name)){
            return User::all();
        }

        return User::where('name', 'like', "%{$name}%")->get();
    }

    public function getAssociates(){
        $associates = DB::table('associate_members')->where('main_user_id', Auth::user()->id)->pluck('associated_user_id');
        $associatesUsers= User::find($associates);
        return view('me.listAssociates', compact( 'associatesUsers'));
    }

    public function getAssociate_of(){
        $associates = DB::table('associate_members')->where('associated_user_id', Auth::user()->id)->pluck('main_user_id');
        $associatesUsers= User::find($associates);
        return view('me.listAssociates', compact( 'associatesUsers'));
    }

    public function changePassword(User $user){

        $request = request();

        $validatedData=$request->validate([
            'old_password'=>'required',
            'password'=>'required|confirmed|min:6|different:old_password',
            'password_confirmation'=>'required|same:password',
        ]);
        $user_id=Auth::user()->id;
        $user->password=Hash::make($request->input('password'));
        $user->save();
    }
}
