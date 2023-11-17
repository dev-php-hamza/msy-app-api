<?php

namespace App\Http\Middleware;

use Closure;
use App\AppIntegration;
use App\Helper;

class ModifyHeader
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
        if ($request->header('X-Api-Key') !== null) {
            $token = $request->header('X-Api-Key');
            
            $salt = Helper::crypt($token, 'd');

            $appIntegrations = AppIntegration::whereAuthToken($token)->whereSalt($salt)->first();
            if (isset($appIntegrations) && !is_null($appIntegrations) && !empty($appIntegrations) && $appIntegrations != '') {
                return $next($request);
            }
            return response()->json(Helper::makeResponse(null,'invalidToken','Token is not valid',200,false));
        }

        if ($request->header('X-Api-Key') === null) {
            return response()->json(Helper::makeResponse(null,'tokenNotFound','Authorization token is not found',200,false));
        }

        return $next($request);
    }
}
