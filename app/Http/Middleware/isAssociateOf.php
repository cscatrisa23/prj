<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Response;

class isAssociateOf
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next)
    {
        if ($request->route('user') && ($request->route('user')->id == Auth::user()->id ||
                count(DB::table('associate_members')->where('associated_user_id', Auth::user()->id)->where('main_user_id', $request->route('user')->id)->get())>0))
            return $next($request);
        $error = "You don't have the permission to see ".$request->route('user')->name."'s movemments!'";
        return Response::make(view('home', compact('error')), 403);

    }
}
