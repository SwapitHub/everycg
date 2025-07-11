<?php

namespace App\Http\Middleware; 

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
  
class IsVerifyEmail
{
    /**

     * Handle an incoming request.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \Closure  $next

     * @return mixed

     */

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
         if(Auth::guard()->check() && Auth::user()->is_email_verified != '1'){
       // if (!Auth::user()->is_email_verified) { 
            auth()->logout();
            return redirect()->route('login')
                    ->with('message', 'You need to confirm your account. We have sent you an activation code, please check your email.');
          }   

        return $response;

    }

}