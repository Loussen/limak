<?php

namespace App\Http\Middleware;

use App\Exceptions\AccessDeniedException;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\ModelUser\User;

class Api
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws AccessDeniedException
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('LIMAK-TOKEN');

        if($token == '') {
            return response()->json(['error' => 'Unauthenticated.'], 401);
            ///throw new AccessDeniedException();
        }


        $User = User::where('api_key', '=', $token)->first();

        if($User !== null){
            $request->attributes->add(['user' => $User]);
            return $next($request);

        }

        return response()->json(['error' => 'Unauthenticated.'], 401);
        //throw new AccessDeniedException();
    }

}
