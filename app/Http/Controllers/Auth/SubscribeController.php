<?php
#app/Http/Controller/ShopCart.php
namespace App\Http\Controllers\Auth;
use App\Models\Plans;
use App\Models\UserSubscription;
use Auth;
use Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GeneralController;

class SubscribeController extends GeneralController
{

	public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');

    }

   

    public function createProduct()
    {

     	/*Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
     	$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

     	$product = $stripe->products->create([
				'name' => 'STANDARD',
				'id'   => '5',
				'metadata' => [
					'name' => "standard"
				]
			]);
     	print_r($product);

			$product_id = $product->id;
			//define product price and recurring interval
			$price = $stripe->prices->create([
			'unit_amount' => 2000,
			'currency' => 'usd',
			'recurring' => ['interval' => 'month'],
			'product' => $product_id,
			]);

		print_r($price);*/
	}
	 public function getPlan($id)
    {
    	$plan = Plans::find($id);
    	session(['subscription' => $id]);  	
		 
    	if($plan->interval=='year'){
    	    $nextdate = date('d.m.Y', strtotime('+1 year'));    	 	
    		$content = 'You will pay $'.$plan->price.' paid upfront for a year. It is recurring payment.You can cancel at any time in your Customer Zone.The next payment will be scheduled in 12 months, on '.$nextdate;
    	}
    	else
    	{
    		$nextdate = date('d.m.Y', strtotime('+1 month'));    	 	
    		$content = 'You can cancel at any time in your Customer Zone. The next payment will be scheduled in 1 month, on '.$nextdate;

    	}

    	return view($this->templatePath . '.subscribe-pay',
        array(
            'title' => 'Payment form',
            'keyword' => '',
            'description' => '',
            'content' => $content,
            'plan' => $plan,
        ));
    	
    }

     public function subscribePay(Request $request)
    {
    	$user = Auth::user();
    	 $subid = session('subscription');
    	 $plan = Plans::find($subid);    	
    	 $priceCents = round($plan->price*100); 

         $validator = Validator::make($request->all(), [
	        'card' => 'required',
	        'month' => 'required',
	        'year' => 'required',
	        'cvc' => 'required'
      	]);

         $data = $request->all();

 			try {
		        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
		       	$token =  Stripe\Token::create ([
                      'card' => [
                        'number' => $data['card'],
                        'exp_month' => $data['month'],
                        'exp_year' => $data['year'],
                        'cvc' => $data['cvc'],
                      ],
                    ]); 

                $customer = Stripe\Customer::create(array( 
			        'email' => $user->email, 
			        'source'  => $token->id 
			    )); 

			    

		        $subsData = Stripe\Subscription::create(array( 
                "customer" => $customer->id, 
                "items" => array( 
                    array( 
                        "plan" => $plan->plan_id, 
                    ), 
                ), 
            )); 

		       $dataInsert = [
	            'user_id' => $user->id,       
	            'stripe_subscription_id' => $subsData['id'],   
	            'stripe_customer_id' => $subsData['customer'],   
	            'stripe_plan_id' => $subsData['plan']['id'],   
	            'plan_amount' => ($subsData['plan']['amount']/100),
	            'plan_amount_currency' => $subsData['plan']['currency'],
	            'plan_interval' => $subsData['plan']['interval'],
	            'plan_interval_count' => $subsData['plan']['interval_count'],
	            'payer_email' => $user->email,
	            'created' => date("Y-m-d H:i:s", $subsData['created']),
	            'plan_period_start' => date("Y-m-d H:i:s", $subsData['current_period_start']),
	            'plan_period_end' => date("Y-m-d H:i:s", $subsData['current_period_end']),
	            'status' => $subsData['status'],
	            'download_limit' => $plan['model_limit'],
        	];
            UserSubscription::create($dataInsert);

			return redirect()->route('home')->with(['success' => 'Plan subscribed successfully!']);

	   		} catch (Exception $ex) {
	   			print_r($ex->getMessage());
         return redirect()->route('home')->with(['error' => $ex->getMessage()]);
    }
    }
}