<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class ApiAuthentication {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $authToken = $request->header('auth-token');
        if ($authToken != '') {
            $check = User::where('auth_token', $authToken)->count();
            if ($check == 0) {
                return response()->json(['message' => 'Unauthorized Access', 'data' => []], config('constant.api_response.UNAUTHORIZED'));
            }
            return $next($request);
        } else {
            return response()->json(['message' => 'Unauthorized Access', 'data' => []], config('constant.api_response.UNAUTHORIZED'));
        }

        return $next($request);
    }

}
