<?php

namespace App\Http\Controllers\SellerAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
//Class needed for login and Logout logic
use Illuminate\Foundation\Auth\AuthenticatesUsers;

//Auth facade
use Auth;
use Session;

class LoginController extends GeneralController  
{
    //Where to redirect seller after login.
    //protected $redirectTo = '/seller_home';

    //Trait
    use AuthenticatesUsers;

    protected function redirectTo()
    {
        $user = Auth::guard('web_seller')->user();
        $verTime =  $user->updated_at;
        $time = time();
         $actualTime = date("Y-m-d H:i:s", strtotime('-10 minutes', $time));
        $A = strtotime($verTime); 
        $B = strtotime($actualTime);
        if($B>$A) { return '/seller_home'; }
        else  {  
            Auth::guard('web_seller')->logout();
            Session::flash('message', 'Your Application is being processed. We will send you and email after this process is completed. Thank you'); 
            return '/seller_login';

        }

    }

    //Custom guard for seller
    protected function guard()
    {
      return Auth::guard('web_seller');
    }

    //Shows seller login form
    public function showLoginForm()
    {
       return view('seller.auth.login');  
    }
}
