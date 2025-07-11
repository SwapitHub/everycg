<?php
#app/Http/Controller/ShopAccount.php
namespace App\Http\Controllers;

use App\Models\UserSubscription;
use App\Models\ShopCountry;
use App\Models\ShopOrder;
use App\Models\ShopOrderStatus;
use App\Models\ShopUser;
use App\Models\Countdownloads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Admin\Admin;
use App\Admin\Models\AdminPermission;
use App\Admin\Models\AdminRole;
use App\Admin\Models\AdminUser;

class ShopAccount extends GeneralController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index user profile
     *
     * @return  [type]  [return description]
     */
    public function index()
    {
        $user = Auth::user();
        return view($this->templatePath . '.account.index')
            ->with(
                [
                'title' => trans('account.my_profile'),
                'user' => $user,
                'layout_page' => 'shop_profile',
                ]
            );
    }

    /**
     * Form Change password
     *
     * @return  [type]  [return description]
     */
    public function changePassword()
    {
        $user = Auth::user();
        $id = $user->id;
        return view($this->templatePath . '.account.change_password')
        ->with(
            [
                'title' => trans('account.change_password'),
                'user' => $user,
                'layout_page' => 'shop_profile',
            ]
        );
    }

    /**
     * Post change password
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function postChangePassword(Request $request)
    {
        $user = Auth::user();
        $id = $user->id;
        $dataUser = ShopUser::find($id);
        $password = $request->get('password');
        $password_old = $request->get('password_old');
        if (trim($password_old) == '') {
            return redirect()->back()
                ->with(
                    [
                        'password_old_error' => trans('account.password_old_required')
                    ]
                );
        } else {
            if (!\Hash::check($password_old, $dataUser->password)) {
                return redirect()->back()
                    ->with(
                        [
                            'password_old_error' => trans('account.password_old_notcorrect')
                        ]
                    );
            }
        }
        $messages = [
            'required' => trans('validation.required'),
        ];
        $v = Validator::make(
            $request->all(), 
            [
                'password_old' => 'required',
                'password' => 'required|string|min:6|confirmed',
            ],
            $messages
        );
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }

        ShopUser::updateInfo(['password' => bcrypt($password)], $id);

        return redirect()->route('member.change_password') 
            ->with(['message' => 'Password updated successfully!']);
    }

    /**
     * Form change info
     *
     * @return  [type]  [return description]
     */
    public function changeInfomation()
    {
        $user = Auth::user();
        $id = $user->id;
        $dataUser = ShopUser::find($id);
        return view($this->templatePath . '.account.change_infomation')
            ->with(
                [
                    'title' => trans('account.change_infomation'),
                    'dataUser' => $dataUser,
                    'layout_page' => 'shop_profile',
                    'countries' => ShopCountry::getArray(),
                ]
            );
    }

    /**
     * Process update info
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function postChangeInfomation(Request $request)
    {
        $user = Auth::user();
        $id = $user->id;
        $data = request()->all();
        $dataUpdate = [
            'first_name' => $data['first_name'],
            //'address1' => $data['address1'],
        ];
        $validate = [
            'first_name' => 'required|string|max:100',
            //'address1' => 'required|string|max:255',
        ];
        if(sc_config('customer_lastname')) {
            $validate['last_name'] = 'required|max:100';
            $dataUpdate['last_name'] = $data['last_name']??'';
        }
        /*if(sc_config('customer_address2')) {
            $validate['address2'] = 'required|max:100';
            $dataUpdate['address2'] = $data['address2']??'';
        }*/
        if(sc_config('customer_phone')) {
            $validate['phone'] = 'required';
            $dataUpdate['phone'] = $data['phone']??'';
        }
        if(sc_config('customer_country')) {
            //$validate['country'] = 'required|min:2';
            //$dataUpdate['country'] = $data['country']??'';
        }
        if(sc_config('customer_postcode')) {
            $validate['postcode'] = 'nullable|min:5';
            $dataUpdate['postcode'] = $data['postcode']??'';
        }
       /* if(sc_config('customer_company')) {
            $validate['company'] = 'nullable';
            $dataUpdate['company'] = $data['company']??'';
        }  */ 
        /*if(sc_config('customer_sex')) {
            $validate['sex'] = 'required';
            $dataUpdate['sex'] = $data['sex']??'';
        }  */ 
       /* if(sc_config('customer_birthday')) {
            $validate['birthday'] = 'nullable|date|date_format:Y-m-d';
            if(!empty($data['birthday'])) {
                $dataUpdate['birthday'] = $data['birthday'];
            }
        } */
        /*if(sc_config('customer_group')) {
            $validate['group'] = 'nullable';
            $dataUpdate['group'] = $data['group']??1;
        }*/


        $messages = [
            'required' => trans('validation.required'),
        ];
        $v = Validator::make(
            $dataUpdate, 
            $validate, 
            $messages
        );
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }
        $vendorUser = AdminUser::where('username', $user->username)->first();

        $vendorUpdate = [
            'name' => $data['first_name'].' '.$data['last_name'],
            'contact' => $data['phone']
            
        ];
        $vendorUser->update($vendorUpdate);

        ShopUser::updateInfo($dataUpdate, $id);

        return redirect()->route('member.change_infomation') 
            ->with(['message' => 'Profile updated successfully!']);
    }

    
     public function changeAddress()
    {
        $user = Auth::user();
        $id = $user->id;
        $dataUser = ShopUser::find($id);
        return view($this->templatePath . '.account.change_address')
            ->with(
                [
                    'title' => 'Address',
                    'dataUser' => $dataUser,
                    'layout_page' => 'shop_profile',
                    'countries' => ShopCountry::getArray(),
                ]
            );
    }

    public function postchangeAddress(Request $request)
    {
        $user = Auth::user();
        $id = $user->id;
        $data = request()->all();
        $dataUpdate = [
           'address1' => $data['address1'],
        ];
        $validate = [            
            'address1' => 'required|string|max:255',
        ];

        $validate['address2'] = 'required|max:100';
        $dataUpdate['address2'] = $data['address2']??'';
        $validate['country'] = 'required|min:2';
        $dataUpdate['country'] = $data['country']??'';               
        $validate['postcode'] = 'nullable|min:5';
        $dataUpdate['postcode'] = $data['postcode']??'';

        $messages = [
            'required' => trans('validation.required'),
        ];
        $v = Validator::make(
            $dataUpdate, 
            $validate, 
            $messages
        );
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }
        
        ShopUser::updateInfo($dataUpdate, $id);

        return redirect()->route('member.change_address') 
            ->with(['message' => 'Address updated successfully!']);
    }


    /**
     * Render order list
     * @return [type] [description]
     */
    public function orderList()
    {
        $user = Auth::user();
        $id = $user->id;
        $orders = ShopOrder::with('orderTotal')->where('user_id', $id)->where('status', 5)->sort()->get();
        $statusOrder = ShopOrderStatus::pluck('name', 'id')->all();
        return view($this->templatePath . '.account.order_list')
            ->with(
                [
                'title' => trans('account.order_list'),
                'user' => $user,
                'orders' => $orders,
                'statusOrder' => $statusOrder,
                'layout_page' => 'shop_profile',
                ]
            );
    }

     public function orderDetail($id)
    {
        $user = Auth::user();
        $uid = $user->id;
        $order = ShopOrder::with('orderTotal')->where('user_id', $uid)->where('id', $id)->first();
        $statusOrder = ShopOrderStatus::pluck('name', 'id')->all();
        if($order){
            return view($this->templatePath . '.account.order_detail')
            ->with(
                [
                'title' => 'Order Detail #'.$id,
                'user' => $user,
                'order' => $order,
                'statusOrder' => $statusOrder,
                'layout_page' => 'shop_profile',
                ]
            );
        } else {
            return $this->itemNotFound();
        }
    }

     public function getDownload()
    {
        $user = Auth::user();
        $id = $user->id;
        $orders = ShopOrder::with('orderTotal')->where('user_id', $id)->where('status', 5)->sort()->get();
        return view($this->templatePath . '.account.downloads')
            ->with(
                [
                'title' => 'Downloads',
                'user' => $user,
                'orders' => $orders,
                'layout_page' => 'shop_profile',
                ]
            );
    }

     public function downloadCount(Request $request)
    {
        
         $user = Auth::user();
         $downloadData = array(
            'file_id' => $request['file'],
            'email' => $user->email,
            'created_at' => date('Y-m-d H:i:s'),
        );            
        Countdownloads::create($downloadData);

        if (Auth::user()) {
            $user = Auth::user();
            $crntdate = date('Y-m-d H:i:s');
            $plan = UserSubscription::where('plan_period_end', '>=', $crntdate)->where('user_id', $user->id)->where('status', 'active')->where('download_limit', '>', 0)->orderBy('id','DESC')->first();
            if(!empty($plan)) 
            {
                $plan->download_limit -= 1;
                $plan->save();
            }
            
        }
    }

}
