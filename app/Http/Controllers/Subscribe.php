<?php
#app/Http/Controller/ShopCart.php
namespace App\Http\Controllers;
use App\Models\Plans;
use Auth;
use Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Subscribe extends GeneralController
{

	public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
    	$plans = Plans::where('status',1)->get();
    	return view($this->templatePath . '.plans',
        array(
            'title' => 'Subscription Plans',
            'keyword' => 'plans',
            'description' => 'subscription plans',
            'plans' => $plans,
        ));
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
	
}