<?php
#app/Http/Admin/Controllers/ShopCategoryController.php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserSubscription;
use App\Models\Plans;
use App\Models\ShopLanguage;
use Illuminate\Http\Request;
use Validator;
use Stripe;
use App\Admin\Admin;
use App\Admin\Models\AdminUser;
use App\Models\ShopUser;

class CancelPlanController extends Controller
{
    public $languages;

    public function __construct()
    {
        $this->languages = ShopLanguage::getList();

    }

    public function index()
    {  
        $user = Admin::user();
        $admin = AdminUser::find($user->id);
        $customer  = ShopUser::where('username',$user->username)->first();
        $plan = UserSubscription::where('user_id',$customer->id)->orderBy('id', 'DESC')->first();

        if ($plan === null) {
            return 'no data';
        }

        $plandetail =  Plans::where('plan_id',$plan['stripe_plan_id'])->first();

        $data = [
            'title' => 'View plan',
            'sub_title' => '',
            'title_description' => '',
            'icon' => 'fa fa-pencil-square-o',            
            'plan' => $plan,
            'planname' => $plandetail->name,
            'url_action' => route('admin_cancel_plan.edit', ['id' => $plan['id']]),
        ];
        return view('admin.screen.cancelplan')
            ->with($data);
        
    }

/**
 * Form create new order in admin
 * @return [type] [description]
 */
    public function create()
    {
       
       
    }

/**
 * Post create new order in admin
 * @return [type] [description]
 */
    public function postCreate()
    {      

       

    }

/**
 * Form edit
 */
    public function edit($id)
    {
       
        $plan = UserSubscription::find($id);
        if ($plan === null) {
            return 'no data';
        }
          //Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
          $stripe = new Stripe\StripeClient(env('STRIPE_SECRET'));
            $cancel =  $stripe->subscriptions->cancel($plan->stripe_subscription_id,
              []
            );
         
         if($cancel['status']=='canceled')
         {
             $dataUpdate = [                    
                 'status' => $cancel['status'],
                 'plan_period_start' => date("Y-m-d H:i:s", $cancel['current_period_start']),
                'plan_period_end' => date("Y-m-d H:i:s", $cancel['current_period_end']),            
            ];

            $plan->update($dataUpdate);  

            return redirect()->route('admin_cancel_plan.index')->with('success', 'Subscription canceled successfully!'); 
         }
         else
         {
             return redirect()->route('admin_cancel_plan.index')->with('error', 'Something went wrong!');
         }

    }

/**
 * update status
 */
    public function postEdit($id)
    {
        

    }

/*
Delete list Item
Need mothod destroy to boot deleting in model
 */
    public function deleteList()
    {
        
    }

}
