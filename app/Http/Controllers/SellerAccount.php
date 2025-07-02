<?php
#app/Http/Controller/ShopAccount.php
namespace App\Http\Controllers;


use App\Models\ShopOrderStatus;
use App\Models\Seller;
use App\Models\Affiliatelink;
use App\Models\AffiliateSuccess;
use App\Models\Marketplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mail;

class SellerAccount extends GeneralController
{	
	 public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $user = Auth::guard('web_seller')->user();
        $Afffees = Marketplace::Where('id',2)->first();
       
        return view('seller.home')
            ->with(
                [
                'title' => 'Dashboard',
                'user' => $user,
                'Afffees' => $Afffees->fees ?? 0,                
                ]
            );
    }

    public function generateLink(Request $request)
    {	
    	$expire = date('Y-m-d', strtotime("+30 days"));		
    	$user = Auth::guard('web_seller')->user()->id; 

		Affiliatelink::create([
            'userid' => $user,
            'link' => $request['link'],
            'expire_date' => $expire,
            'created_at' => date('Y-m-d H:i:s')
        ]);      
    }

    public function RegisterMsg(Request $request)
    {	
    	return view('seller.register_success')
            ->with(
                [
                'title' => 'Register Success'
                ]
            );
    }

    public function earnHistory(Request $request)
    {   
        $user = Auth::guard('web_seller')->user()->id;
        $earns = AffiliateSuccess::where('userid',$user)->orderBy('id','desc')->get();
        return view('seller.earns')
            ->with(
                [
                'title' => 'Earnings History',
                'earns' => $earns
                ]
            );
    }


    public function getProfile(Request $request)
    {   
        $user = Auth::guard('web_seller')->user();        
        return view('seller.profile')
            ->with(
                [
                'title' => 'Edit Profile',
                'user' => $user
                ]
            );
    }

    public function postProfile(Request $request)
    {
         $user = Auth::guard('web_seller')->user(); 
        $validator = $request->validate([
            'name' => 'required'          
        ]);

        if(!empty($request['password_old']))
        {
             $v = Validator::make(
                $request->all(), 
                [
                    'password_old' => 'required',
                    'password' => 'required|string|min:8',
                ]
            );
            if ($v->fails()) {
                return redirect()->back()->withErrors($v->errors());
            }

            $password = $request['password'];
            $password_old = $request['password_old'];
            if (trim($password_old) == '') {
                
                return redirect()->back()
                    ->with(
                        [
                            'password_old_error' => trans('account.password_old_required')
                        ]
                    );
            } else {
                if (!\Hash::check($password_old, $user->password)) {
                    return redirect()->back()
                        ->with(
                            [
                                'password_old_error' => trans('account.password_old_notcorrect')
                            ]
                        );
                }
            }

            $pwdUpdate = [
                'password' => bcrypt($password)
            ];
             $user->update($pwdUpdate);
           
        }        
       
        $data = request()->all();

         $dataUpdate = [
            'name' => $data['name']
        ];

        $user->update($dataUpdate);       

        return redirect()->back()->with(['message' => 'Profile updated successfully!']);
    }

    public function verifyAccount($token)
    {   
        $verifyUser = Seller::where('token', $token)->first(); 
        $message = 'Sorry your email cannot be identified.'; 

        if(!is_null($verifyUser) ){
                        

            if(!$verifyUser->user_verify) {
                $Update = [
                    'user_verify' => 1,
                    'updated_at' => date('Y-m-d H:i:s')           
                ];
                $verifyUser->update($Update);
               // $message = "Your e-mail is verified. You can now login.";

                $expire = date('Y-m-d', strtotime("+30 days")); 
                Affiliatelink::create([
                    'userid' => $verifyUser->id,
                    'link' => route('product.all').'?refer='.$verifyUser->affiliate_code,
                    'expire_date' => $expire,
                    'created_at' => date('Y-m-d H:i:s')
                ]);    

                Mail::send('mail.sellerWelcome', ['token' => $verifyUser->affiliate_code], function($message) use($verifyUser){
                  $message->to($verifyUser['email']);
                  $message->subject('Welcome Mail');
                });

            } else {
                $message = "Your e-mail is already verified. You can now login.";
            }

        }  

      return redirect()->route('seller.login')->with('message', $message);

    }


}
