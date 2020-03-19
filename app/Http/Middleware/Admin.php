<?php

namespace App\Http\Middleware;

use App\Exceptions\AccessDeniedException;
use App\Exceptions\ApiNotFoundException;
use App\ModelPermissions\Api;
use App\ModelPermissions\RelAdminRole;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws ApiNotFoundException
     * @throws AccessDeniedException
     */
    public function handle($request, Closure $next)
    {

        $ips = ['213.173.85.133', '85.132.89.45', '85.132.189,42'];

        /*if(!in_array($_SERVER["HTTP_CF_CONNECTING_IP"], $ips)){
            if(auht()->id() && auth()->user->login_from_other == 1){

            }else{
                //$request->redirect();
            }
        }*/
       // echo $request->ip(); die;

        //if();
        //$request->

        return $next($request);
        $method = $request->method();
        $url = $this->preparePermissionUrl($request);
        $hasApi = Api::with('relRolesApis')->where([
            ['name', '=', $url],
            ['method', '=', $method]
        ])->get();
       if(count($hasApi) === 0) {
           throw new ApiNotFoundException();
       }
       $ids = $hasApi[0]->relRolesApis->map(function ($data) {
           return $data->role_id;
       });
        $adminId = Auth::guard('admin')->user()->id;
       $hasPermission = RelAdminRole::where('admin_id', '=', $adminId)
        ->whereIn('role_id', $ids)->get();

      if(count($hasPermission) === 0) {
          throw new AccessDeniedException();
      }
      return $next($request);
    }

    private function preparePermissionUrl($request) {
        $langs = ['az', 'ru'];
        $path = $request->path();
        $segments = explode('/', $path);
        if(in_array($segments[0], $langs))  unset($segments[0]);
        foreach ($segments as $key => $value){
            if((string)(int)$value === $value) {
                $segments[$key] = '{id}';
            }
        }
        return implode('/', $segments);
    }
}
