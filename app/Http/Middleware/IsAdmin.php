<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Response;

class IsAdmin
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
        if ($request->user() && $request->user()->isAdministrator()) {
            return $next($request);
        }
        $error = "You don't have the permission to see the list of Users!'";
        return Response::make(view('home', compact('error')), 403);
        //return redirect('/home');
    }
}
