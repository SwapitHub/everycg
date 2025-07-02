<?php
#app/Http/Admin/Controllers/ShopCategoryController.php
namespace App\Admin\Controllers;
use App\Http\Controllers\Controller;
use App\Models\ShopLanguage;
use Illuminate\Http\Request;
use Validator;
use App\Admin\Admin;
use App\Admin\Models\AdminUser;
use App\Models\ShopCountry;
use App\Models\ShopUser;

class AccountController extends Controller
{
    public $languages;
    public function __construct()
    {
        $this->languages = ShopLanguage::getList();
    }
    public function index()
    {
        $user = Admin::user();        
         $data = [
            'url_action' => route('admin_accsett.edit'),
            'countries' => (new ShopCountry)->getList(),
            'user' => $user ?? [],
        ];
        
      return view('admin.screen.account_setting')->with($data);;
    }

   
/**
 * Post create new order in admin
 * @return [type] [description]
 */
    public function postEdit(Request $request)
    {
        $user = Admin::user();
        $admin = AdminUser::find($user->id);

        $data = request()->all();
        $langFirst = array_key_first(sc_language_all()->toArray()); //get first code language active
       $validator = Validator::make($data, [ 
            'email' => 'required|email|unique:admin_user,email,' .$user->id. ',id',    
            'birthday' => 'required',       
            'name' => 'required',
            'location' => 'required',
            'language' => 'required',
            
        ], [
            'name.required' => 'The name field is required.',
            
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }

        $langs = implode(',', $data['language']);

        $dataUpdate = [
            'email' => $data['email'],
            'birthday' => $data['birthday'],
            'name' => $data['name'],
            'location' => $data['location'],
            'language' => $langs,
            'website' => $data['website'],
        ];
        if($data['email'] != $admin->email)
        {
             $dataUpdate['emailverfy'] = 0;
        }

        if($request->hasFile('avatar')){ 
            $image = $request->file('avatar');
            $imageName = uniqid().$image->getClientOriginalName();
            $image->move(public_path('data/avatar'),$imageName);
            
            $dataUpdate['avatar'] = '/data/avatar/'.$imageName;
        }
        AdminUser::updateInfo($dataUpdate, $user->id);

            $customer  = ShopUser::where('username',$user->username)->first();
                $vendorUpdate = [
                    'email' => $data['email']                               
                ];
                $customer->update($vendorUpdate);
       
        return redirect()->route('admin_accsett.index')->with('success', 'Setting Updated successfully!');
    }

     public function sendmail(Request $request)
    {
        $user = Admin::user();
        $data = request()->all();
         $validator = Validator::make($data, [ 
            'email' => 'required|email|unique:admin_user,email,' .$user->id. ',id'  
        ]);
         $valid=1;

        if ($validator->fails()) {
            $valid=0;
        }
       
       if($valid){
        $email = $data['email'];
        $enemail = rtrim(strtr(base64_encode($email), '+/', '-_'), '=');

        $msg = '<html><head></head><body style="margin:0px; background:#eae9ea; width:100%; padding:0; text-decoration:none;">
            <div style="margin:0 auto; width:600px; background:#fff;">
            <table style="width:100%; padding:0px 40px; float:left; margin:0px; background:#fff;">
            <tr style="float:left; margin-top:10px;">
            <td style=" width:100%"></td>
            </tr>
            <tr>
            <td style="border-bottom:1px solid#d7d7d7;"> <p style=" width:75%; margin:20px 0 20px 0; float:left; color:#000; font-size:20px; font-family:arial; font-weight:bold;">Email Verify</p>
            
                
            </td>
            </tr>

            <tr>
            <td><p>We\'re excited to have you get started. First, you need to confirm your account. Just press the button below.</p>
            <a href="'.route('mail.verify',['id'=>$enemail]).'">Confirm Account</a><p>If you have any questions, just contact us, we\'re always happy to help out. <br><br>
                Cheers,<br> Every CG<br></p>';   

        
         $msg .= '</td></tr></table><!---cont-->
            <div class="footer" style="width:100%; float:left; padding-bottom:30px;">
            <div class="foot_logo" style="margin:0; padding:0;display:inline-block;text-align:center;width:100%;">
            </div>
            </div>
            </div>
            </body></html>'; 

            $header = "From: Every Cg <".sc_store('email')."> \r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
            $to = $data['email'];
            $subject = 'Verify mail';
            mail($to,$subject,$msg,$header);           


       }
       echo $valid;
    }


 
  
}