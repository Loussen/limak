<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard('admin')->check() && explode('/', $request->path())[0] === 'admin' && explode('/', $request->path())[1] !== 'logout') {
            return redirect('/admin/welcome-admin');
        }
        if (Auth::guard('web')->check() &&  explode('/', $request->path())[0] !== 'admin' && explode('/', $request->path())[0] !== 'logout') {
            return redirect('/');
        }

        return $next($request);
    }
}
