<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
class CheckStatus
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
        $response = $next($request);
        //If the status is not approved redirect to login 
     
        if(Auth::guard('web_seller')->check() && Auth::guard('web_seller')->user()->user_verify != '1'){
            Auth::guard('web_seller')->logout();
            return redirect('/seller_login')->with('error', 'Please verify your account via the mail link sent to you!');
        }
        return $response;
    }
}