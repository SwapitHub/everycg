<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Hash;
use App\Models\ShopUser;
use App\Models\ShopOrder;
use App\Models\UserCart;
use App\Models\ShopProduct;
use Auth;
use App\Admin\Admin;
use App\Admin\Models\AdminPermission;
use App\Admin\Models\AdminRole;
use App\Admin\Models\AdminUser;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {            
             $user = $request->user();
             
            $cart = UserCart::where('sessionid', '=', $request->sessionid)->get();
            //$cart =  UserCart::where('userid', '=', $user->id)->get();
            if(!empty($cart))
            {
                 foreach($cart as $item)
                 {
                    $product = ShopProduct::find($item->pro_id);
                    $cartexist =  UserCart::where('userid',$user->id)->where('pro_id',$item->pro_id)->first();

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
                        'pro_id' => $item->pro_id,
                        'qty' => $item->qty,
                        'price' => $product->getFinalPrice(),
                        'userid' => $user->id,
                        'created_at' => date('Y-m-d H:i:s'),
                    );
            
                    UserCart::create($dataCart);
                    }
                 }
             }


             $data['token'] = $user->createToken('MyApp')->accessToken;
             $data['name']  = $user->name;
             return response()->json($data, 200);
         }
       return response()->json(['error'=>'Email or password is incorrect!'], 401); 
    }
    public function register(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'first_name' => 'required',
        'email' => 'required|email|unique:shop_user',
        'password' => 'required|min:8',
      ]);
      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()->first()], 422);
      }
      $user = $request->all();
       $usrname = strtolower($user['first_name'] . rand(pow(10, 8 - 1), pow(10, 8) -1));
      $user['userpwd']  = $user['password'];
      $user['password'] = Hash::make($user['password']);
      $user['username'] = $usrname;
      

      $dataInsert = [
            'name' => $user['first_name'],
            'username' => $usrname,
            'password' => bcrypt($user['password']),
        ];
      $adminuser = AdminUser::createUser($dataInsert);

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

      $user = ShopUser::create($user);
      $success['token'] =  $user->createToken('MyApp')-> accessToken; 
      $success['first_name'] =  $user->first_name;
      return response()->json(['success'=>$success]); 
    }
    public function userDetail()
    {
        $user = Auth::user();
        return response()->json(['user' => $user], 200);
    }
    
    public function orderList()
    {
        $user = Auth::user();
        $id = $user->id;
        $orders = ShopOrder::with('orderTotal')->where('user_id', $id)->where('status', 5)->sort()->get();
        return response()->json(['orders' => $orders], 200);
    }

 
    public function logOut(){   
        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return response()->json(['success' =>'logout_success'],200); 
        }else{
            return response()->json(['error' =>'something_went_wrong'], 500);
        }
    }

    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'address1' => 'required|string|max:255',
            'phone' => 'required',
            'country' => 'required',
            'postcode' => 'required',
        ]);

      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      }
        //$user = Auth::user(); 
        $id = Auth::user()->id;
        $user = ShopUser::find($id);

        $data = request()->all();
        $dataUpdate = array(
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'address1' => $data['address1'],
            'phone' => $data['phone'],
            'country' => $data['country'],
            'postcode' => $data['postcode']
            );  
     
        
       //$res = $user->update($dataUpdate); 
      ShopUser::updateInfo($dataUpdate, $id);

         return response()->json([
        'message' => 'Profile updated successfully!'], 200); 

        
    }
}