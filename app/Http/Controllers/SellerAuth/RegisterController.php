<?php

namespace App\Http\Controllers\SellerAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
//Validator facade used in validator method
use Illuminate\Support\Facades\Validator;
use Mail; 

//Seller Model
use App\Models\Seller;
use Illuminate\Support\Str;

//Auth Facade used in guard
use Auth;

class RegisterController extends GeneralController  
{

    protected $redirectPath = '/register_message';

    //shows registration form to seller
    public function showRegistrationForm()
    {
        return view('seller.auth.register');
    }

    //Handles registration request for seller
    public function register(Request $request)
    {

       //Validates data
        $this->validator($request->all())->validate();

       //Create seller
        $seller = $this->create($request->all());

        //Authenticates seller
        //$this->guard()->login($seller);

       //Redirects sellers
        return redirect($this->redirectPath);
    }

    //Validates user's Input
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:sellers',
            'password' => 'required|min:8|confirmed',
        ]);
    }

    //Create a new seller instance after a validation.
    protected function create(array $data)
    {
        $token = Str::random(64);
        $user = Seller::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'token' => $token,
            'password' => bcrypt($data['password']),
            'affiliate_code' => mt_rand(10000000,99999999),
        ]);

       

        Mail::send('mail.sellerEmailverify', ['token' => $token], function($message) use($data){
              $message->to($data['email']);
              $message->subject('Email Verification Mail');
          });

        return $user;
    }

    //Get the guard to authenticate Seller
    protected function guard()
    {
        return Auth::guard('web_seller');
    }
}
