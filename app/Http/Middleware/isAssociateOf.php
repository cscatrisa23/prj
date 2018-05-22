<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\AuthorizationException;


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
        throw new AuthorizationException();

    }
}
