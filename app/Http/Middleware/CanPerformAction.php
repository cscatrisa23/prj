<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Auth\Access\AuthorizationException;
use App\User;

class canPerformAction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->route('user')->id!=$request->user()->id)  {
            return $next($request);
        }

        $request->session()->flash('errors', 'You don\'t have the permission demote/block/unblock yourself!');
        $users = User::all();
        return Response::make(view('users.list', compact( 'users')), 403);
//        return redirect('home');
    }

}
