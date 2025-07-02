<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\GeneralController;
use App\Models\ShopCountry;
use Auth;
use Cart;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\UserCart;
use App\Models\ShopProduct;
use App\Models\UserVerify;
use App\Models\ShopUser;
use App\Models\Seller;
use App\Models\ShopSubscribe;
use Mail;

class LoginController extends GeneralController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/';
    protected function redirectTo()
    {
        $cart = Cart::content();
        $user = Auth::user();

        if(!empty($cart))
        {
            foreach($cart as $item)
            {
                $product = ShopProduct::find($item->id);
                $cartexist =  UserCart::where('userid',$user->id)->where('pro_id',$item->id)->first();
                if(!empty($cartexist))  
                    {                        
                        $dataUpdate = array(
                            'qty' => $cartexist->qty+$item->qty,
                            'price' => $product->getFinalPrice(),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        
                            $cartexist->update($dataUpdate); 
                        

                    }
                    else
                    {
                        $dataCart = array(
                        'pro_id' => $item->id,
                        'qty' => $item->qty,
                        'price' => $product->getFinalPrice(),
                        'userid' => $user->id,
                        'created_at' => date('Y-m-d H:i:s'),
                    );            
                    UserCart::create($dataCart);
                    }

            }
        }
         Cart::destroy();

        $userCrt =  UserCart::where('userid',$user->id)->get();
       
        
         if(!empty($userCrt))
         {
            
            foreach($userCrt as $itmmm)
            {              
                $form_attr = $data['form_attr'] ?? null;
                $product = ShopProduct::find($itmmm->pro_id);
                $options = array();
                $options = $form_attr;
                $datacrt = array(  
                    'id' => $itmmm->pro_id,
                    'name' => $product->name,
                    'qty' => $itmmm->qty,
                    'price' => $product->getFinalPrice(),
                );
                if ($options) {
                    $datacrt['options'] = $options;
                }
                Cart::add($datacrt);

            }
            
         }
        return '/';
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest')->except('logout');
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    }
    public function showLoginForm()
    {
        if (Auth::user()) {
            return redirect()->route('home');
        }
        return view($this->templatePath . '.shop_login',
            array(
                'title' => trans('front.login'),
                'countries' => ShopCountry::getArray(),
            )
        );
    }

     public function showRegisterForm()
    {
        if (Auth::user()) {
            return redirect()->route('home');
        }
        return view($this->templatePath . '.shop_register',
            array(
                'title' => 'Register',
                'countries' => ShopCountry::getArray(),
            )
        );
    }

    public function logout(Request $request)
    {
		
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect()->route('login');
    }

     public function verifyAccount($token)
    {        
        $verifyUser = UserVerify::where('token', $token)->first(); 
        $message = 'Sorry your email cannot be identified.'; 

        if(!is_null($verifyUser) ){
            $user = $verifyUser->user;             

            if(!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();
                $message = "Your e-mail is verified. You can now login.";
               $sUser =  ShopUser::where('id',$verifyUser->user_id)->first();
                $affUser = Seller::where('email',$sUser->email)->first();
                $vendorUpdate = [
                    'user_verify' => 1            
                ];
                $affUser->update($vendorUpdate);

                 $subs = [
                    'email' => $affUser->email,            
                    'created_at' => date('Y-m-d H:i:s'),            
                ];
                ShopSubscribe::create($subs);

            } else {
                $message = "Your e-mail is already verified. You can now login.";

            }

        }  

      return redirect()->route('login')->with('message', $message);

    }

}
