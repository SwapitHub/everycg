<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\GeneralController;
use App\Models\ShopEmailTemplate;
use App\Models\ShopUser;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Admin\Admin;
use App\Admin\Models\AdminPermission;
use App\Admin\Models\AdminRole;
use App\Admin\Models\AdminUser;
use App\Models\UserVerify;
use Mail; 
use Illuminate\Support\Str;
use App\Models\Seller;

class RegisterController extends GeneralController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = '/home';
   protected $redirectTo = '/member/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validate = [
            'reg_first_name' => 'required|string|max:100',
            'reg_last_name' => 'required|string|max:100',
            'reg_email' => 'required|string|email|max:255|unique:' . (new ShopUser)->getTable() . ',email',
            'reg_password' => 'required|string|min:6|confirmed',
            //'reg_address1' => 'required|string|max:255',
        ];
        /*if(sc_config('customer_lastname')) {
            $validate['reg_last_name'] = 'required|max:100';
        }
        if(sc_config('customer_address2')) {
            $validate['reg_address2'] = 'required|max:100';
        }
        if(sc_config('customer_phone')) {
            $validate['reg_phone'] = 'required|regex:/^0[^0][0-9\-]{7,13}$/';
        }
        if(sc_config('customer_country')) {
            $validate['reg_country'] = 'required|min:2';
        }
        if(sc_config('customer_postcode')) {
            $validate['reg_postcode'] = 'nullable|min:5';
        }
        if(sc_config('customer_company')) {
            $validate['reg_company'] = 'nullable';
        }   
        if(sc_config('customer_sex')) {
            $validate['reg_sex'] = 'required';
        }   
        if(sc_config('customer_birthday')) {
            $validate['reg_birthday'] = 'nullable|date|date_format:Y-m-d';
        } 
        if(sc_config('customer_group')) {
            $validate['reg_group'] = 'nullable';
        }  */
        return Validator::make($data, $validate);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\ShopUser
     */
    protected function create(array $data)
    {
         $usrname = strtolower($data['reg_first_name'] . rand(pow(10, 8 - 1), pow(10, 8) -1));
        $dataMap = [
            'first_name' => $data['reg_first_name'],
            'last_name' => $data['reg_last_name'],
            'email' => $data['reg_email'],
            'password' => bcrypt($data['reg_password']),
            'phone' => $data['reg_phone']??null,
            'username' => $usrname,
            'userpwd' => $data['reg_password'],
            //'address1' => $data['reg_address1'],
           // 'address2' => $data['reg_address2']??'',
            //'country' => $data['reg_country']??'VN',
            //'group' => $data['reg_group']??1,
            //'sex' => $data['reg_sex']??0,
            //'postcode' => $data['reg_postcode']??null,
        ];
        
        /*if(!empty($data['reg_birthday'])) {
            $dataMap['birthday'] = $data['reg_birthday'];
        }
*/  
       
        $dataInsert = [
            'name' => $data['reg_first_name'].' '.$data['reg_last_name'],
            'username' => $usrname,
            'password' => bcrypt($data['reg_password']),
            'email' => $data['reg_email'],
        ];
         $adminuser = AdminUser::createUser($dataInsert);

         $user = ShopUser::createCustomer($dataMap);

          $roles = array(7);
        $permission = array(2,19,5,23);
        //Insert roles
        if ($roles) {
            $adminuser->roles()->attach($roles);
        }
        //Insert permission
        if ($permission) {
            $adminuser->permissions()->attach($permission);
        }      

        $affUser = Seller::where('email', '=', $data['reg_email'])->first();
        if ($affUser === null) {
                Seller::create([
                    'name' => $data['reg_first_name'].' '.$data['reg_last_name'],
                    'email' => $data['reg_email'],
                    'password' => bcrypt($data['reg_password']),
                    'affiliate_code' => mt_rand(10000000,99999999),
                ]);
            }     
        
        /*if ($user) {
            if (sc_config('welcome_customer')) {

                $checkContent = (new ShopEmailTemplate)->where('group', 'welcome_customer')->where('status', 1)->first();
                if ($checkContent) {
                    $content = $checkContent->text;
                    $dataFind = [
                        '/\{\{\$title\}\}/',
                        '/\{\{\$first_name\}\}/',
                        '/\{\{\$last_name\}\}/',
                        '/\{\{\$email\}\}/',
                        '/\{\{\$phone\}\}/',
                        '/\{\{\$password\}\}/',
                        '/\{\{\$address1\}\}/',
                        '/\{\{\$address2\}\}/',
                        '/\{\{\$country\}\}/',
                    ];
                    $dataReplace = [
                        trans('email.welcome_customer.title'),
                        $dataMap['first_name'],
                        $dataMap['last_name'],
                        $dataMap['email'],
                        $dataMap['phone'],
                        $dataMap['password'],
                        $dataMap['address1'],
                        $dataMap['address2'],
                        $dataMap['country'],
                    ];
                    $content = preg_replace($dataFind, $dataReplace, $content);
                    $data_mail = [
                        'content' => $content,
                    ];

                    $config = [
                        'to' => $data['reg_email'],
                        'subject' => trans('email.welcome_customer.title'),
                    ];

                    sc_send_mail('mail.welcome_customer', $data_mail, $config, []);
                }

            }
        } else {

        }*/

        $token = Str::random(64);
        UserVerify::create([
              'user_id' => $user->id, 
              'token' => $token
            ]);  

        Mail::send('mail.emailVerificationEmail', ['token' => $token], function($message) use($data){
              $message->to($data['reg_email']);
              $message->subject('Email Verification Mail');
          });
        return $user;
    }
    public function showRegistrationForm()
    {
        return redirect()->route('register');
        // return view('auth.register');
    }

    protected function registered(Request $request, $user)
    {
        redirect()->route('home')->with(['message' => trans('account.register_success')]);
    }

    public function register(Request $request)
    {

       //Validates data
        $this->validator($request->all())->validate();  

       //Create seller
        $seller = $this->create($request->all());

        //Authenticates seller
        //$this->guard()->login($seller);

       //Redirects sellers
        return redirect($this->redirectTo);
    }
}
