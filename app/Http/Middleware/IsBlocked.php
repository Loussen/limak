<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsBlocked
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

        if (Auth::guard('web') && Auth::user()->is_blocked == 1 ) {
            Auth::guard('web')->logout();
            return redirect()->route('login');
        }
        return $next($request);
    }
}
