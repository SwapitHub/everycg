<?php
#app/Http/Controller/ShopCart.php
namespace App\Http\Controllers;

use App\Models\ShopEmailTemplate;
use App\Models\ShopAttributeGroup;
use App\Models\ShopCountry;
use App\Models\ShopOrder;
use App\Models\ShopOrderTotal;
use App\Models\ShopProduct;
use App\Models\ShopOrderDetail;
use App\Models\Marketplace;
use App\Models\Transfers;
use App\Models\AffiliateSuccess;
use Cart;
use Auth;
use Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductFiles;
use App\Models\UserCart;
use App\Admin\Models\AdminUser;

class ShopCart extends GeneralController
{
    const ORDER_STATUS_NEW = 1;
    const PAYMENT_UNPAID = 1;
    const SHIPPING_NOTSEND = 1;

    public function __construct()
    {
        parent::__construct();

    }
    /**
     * Get list cart: screen get cart
     * @return [type] [description]
     */
    public function getCart()
    {
        session()->forget('paymentMethod'); //destroy paymentMethod
        session()->forget('shippingMethod'); //destroy shippingMethod

        //Shipping
        $moduleShipping = sc_get_extension('shipping');
        $sourcesShipping = sc_get_all_plugin('Extensions', 'shipping');
        $shippingMethod = array();
        foreach ($moduleShipping as $module) {
            if (array_key_exists($module['key'], $sourcesShipping)) {
                $moduleClass = sc_get_class_extension_config('shipping', $module['key']);
                $shippingMethod[$module['key']] = (new $moduleClass)->getData();
            }
        }

        //Payment
        $modulePayment = sc_get_extension('payment');
        $sourcesPayment = sc_get_all_plugin('Extensions', 'payment');
        $paymentMethod = array();
        foreach ($modulePayment as $module) {
            if (array_key_exists($module['key'], $sourcesPayment)) {
                $moduleClass = $sourcesPayment[$module['key']].'\AppConfig';
                $paymentMethod[$module['key']] = (new $moduleClass)->getData();
            }
        }        

        //Total
        $moduleTotal = sc_get_extension('total');
        $sourcesTotal = sc_get_all_plugin('Extensions', 'total');
        $totalMethod = array();
        foreach ($moduleTotal as $module) {
            if (array_key_exists($module['key'], $sourcesTotal)) {
                $moduleClass = $sourcesTotal[$module['key']].'\AppConfig';
                $totalMethod[$module['key']] = (new $moduleClass)->getData();
            }
        } 


        //====================================================
 

        $extensionDiscount = $totalMethod['Discount'] ?? '';
        if (!empty(session('Discount'))) {
            $hasCoupon = true;
        } else {
            $hasCoupon = false;
        }
        $user = auth()->user();
        if ($user) {
            $addressDefaul = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'address1' => $user->address1,
                'address2' => $user->address2,
                'postcode' => $user->postcode,
                'company' => $user->company,
                'country' => $user->country,
                'phone' => $user->phone,
                'comment' => '',
            ];
        } else {
            $addressDefaul = [
                'first_name' => '',
                'last_name' => '',
                'postcode' => '',
                'company' => '',
                'email' => '',
                'address1' => '',
                'address2' => '',
                'country' => '',
                'phone' => '',
                'comment' => '',
            ];
        }
        $shippingAddress = session('shippingAddress') ? session('shippingAddress') : $addressDefaul;
        $objects = ShopOrderTotal::getObjectOrderTotal();
        return view(
            $this->templatePath . '.shop_cart',
            [
                'title' => trans('front.cart_title'),
                'description' => '',
                'keyword' => '',
                'cart' => Cart::content(),
                'shippingMethod' => $shippingMethod,
                'paymentMethod' => $paymentMethod,
                'totalMethod' => $totalMethod,
                'dataTotal' => ShopOrderTotal::processDataTotal($objects),
                'hasCoupon' => $hasCoupon,
                'extensionDiscount' => $extensionDiscount,
                'shippingAddress' => $shippingAddress,
                'uID' => $user->id ?? 0,
                'layout_page' => 'shop_cart',
                'countries' => ShopCountry::getArray(),
                'attributesGroup' => ShopAttributeGroup::pluck('name', 'id')->all(),
            ]
        );
    }

    /**
     * Process Cart, prepare for the checkout screen
     */
    public function processCart()
    {
        if (Cart::count() == 0) {
            return redirect()->route('cart');
        }

        //Not allow for guest
        if (!sc_config('shop_allow_guest') && !auth()->user()) {
            return redirect()->route('login');
        }

        $messages = [
            'max' => trans('validation.max.string'),
            'required' => trans('validation.required'),
        ];
        $validate = [
            'first_name' => 'required|max:100',
            'address1' => 'required|max:100',
            'email' => 'required|string|email|max:255',
            'shippingMethod' => 'required',
            'paymentMethod' => 'required',
        ];
        if(sc_config('customer_lastname')) {
            $validate['last_name'] = 'required|max:100';
        }
        if(sc_config('customer_address2')) {
            $validate['address2'] = 'required|max:100';
        }
        if(sc_config('customer_phone')) {
            $validate['phone'] = 'required';
        }
        if(sc_config('customer_country')) {
            $validate['country'] = 'required|min:2';
        }
        if(sc_config('customer_postcode')) {
            $validate['postcode'] = 'required|min:7';
        }
        if(sc_config('customer_company')) {
            $validate['company'] = 'required|min:3';
        }        
        $v = Validator::make(
            request()->all(), 
            $validate, 
            $messages
        );
        if ($v->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($v->errors());
        }

        //Set session shippingMethod
        session(['shippingMethod' => request('shippingMethod')]);
        //Set session paymentMethod
        session(['paymentMethod' => request('paymentMethod')]);
        //Set session shippingAddress
        session(
            [
                'shippingAddress' => [
                    'first_name' => request('first_name'),
                    'last_name' => request('last_name'),
                    'email' => request('email'),
                    'country' => request('country'),
                    'address1' => request('address1'),
                    'address2' => request('address2'),
                    'phone' => request('phone'),
                    'postcode' => request('postcode'),
                    'company' => request('company'),
                    'comment' => request('comment'),
                ],
            ]
        );
        return redirect()->route('checkout');
    }

    /**
     * Checkout screen
     * @return [view]
     */
    public function getCheckout()
    {
        if (
            !session('shippingMethod') ||
            !session('paymentMethod') ||
            !session('shippingAddress')
        ) {
            return redirect()->route('cart');
        }
        //====================================================
        $paymentMethod = session('paymentMethod');
        $shippingMethod = session('shippingMethod');
        $shippingAddress = session('shippingAddress');

        //Shipping
        $classShippingMethod = sc_get_class_extension_config('Shipping', $shippingMethod);
        $shippingMethodData = (new $classShippingMethod)->getData();

        //Payment
        $classPaymentMethod = sc_get_class_extension_config('Payment', $paymentMethod);
        if($paymentMethod=='Paypal' OR $paymentMethod=='Cash')
        {
            $paymentMethodData = (new $classPaymentMethod)->getData();
            
        }
        else
        {
             $paymentMethodData = [
                    'title' => $paymentMethod,
                    'code' => $paymentMethod,
                    'image' => '',
                    'permission' => 'true',
                    'version' => '2.1',
                    'auth' => 'Naruto',
                    'link' => 'https://s-cart.org',
                ];
        }      
        

        $objects = ShopOrderTotal::getObjectOrderTotal();
        $dataTotal = ShopOrderTotal::processDataTotal($objects);

        //Set session dataTotal
        session(['dataTotal' => $dataTotal]);

        return view(
            $this->templatePath . '.shop_checkout',
            [ 
                'title' => trans('front.checkout_title'),
                'description' => '',
                'keyword' => '',
                'cart' => Cart::content(),
                'dataTotal' => $dataTotal,
                'paymentMethodData' => $paymentMethodData,
                'shippingMethodData' => $shippingMethodData,
                'shippingAddress' => $shippingAddress,
                'attributesGroup' => ShopAttributeGroup::getList(),
            ]
        );
    }

    /**
     * add to cart by post, always use page product detail
     * @return [type]           [description]
     */
    public function addToCart()
    {
        $data = request()->all();
        $product_id = $data['product_id'];
        $form_attr = $data['form_attr'] ?? null;
        $qty = 1 ;
        $product = ShopProduct::find($product_id);
        //if ($product->stock >= $qty) { 
            $options = array();
            $options = $form_attr;
            $dataCart = array(  
                'id' => $product_id,
                'name' => $product->name,
                'qty' => $qty,
                'price' => $product->getFinalPrice(),
            );
            if ($options) {
                $dataCart['options'] = $options;
            }
            Cart::add($dataCart);

            if (Auth::user()) {
            $user = Auth::user();
                $cartexist = UserCart::where('userid',$user->id)->where('pro_id',$product_id)->first();
                if(!empty($cartexist))
                {  
                    $dataUpdate = array(
                    'qty' => $cartexist->qty+$qty,
                    'price' => $product->getFinalPrice(),
                    'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $cartexist->update($dataUpdate); 
                }
                else {            
                    $userCart = array(
                        'pro_id' => $product_id,
                        'qty' => $qty,
                        'price' => $product->getFinalPrice(),
                        'userid' => $user->id,
                        'created_at' => date('Y-m-d H:i:s'),
                    );            
                    UserCart::create($userCart);
                }
                
             }
            return redirect()->route('cart')
                ->with(
                    ['message' => trans('cart.success', ['instance' => 'cart'])]
                );
        /*} else {
            return redirect()->back()
                ->with(
                    ['error' => trans('cart.over', ['item' => $product->name, 'avail' => $product->stock ])]
                );
        }*/

    }

    /**
     * Add new order
     */
    public function addOrder(Request $request)
    {
        if (Cart::count() == 0) {
            return redirect()->route('home');
        }
        //Not allow for guest
        if (!sc_config('shop_allow_guest') && !auth()->user()) {
            return redirect()->route('login');
        } //
        $data = request()->all();
        if (!$data) {
            return redirect()->route('cart');
        } else {
            $dataTotal = session('dataTotal') ?? [];
            $shippingAddress = session('shippingAddress') ?? [];
            $payment_method = session('paymentMethod') ?? '';
            $shipping_method = session('shippingMethod') ?? '';
        }

        $uID = auth()->user()->id ?? 0;
        //Process total
        $subtotal = (new ShopOrderTotal)->sumValueTotal('subtotal', $dataTotal); //sum total
        $shipping = (new ShopOrderTotal)->sumValueTotal('shipping', $dataTotal); //sum shipping
        $discount = (new ShopOrderTotal)->sumValueTotal('discount', $dataTotal); //sum discount
        $received = (new ShopOrderTotal)->sumValueTotal('received', $dataTotal); //sum received
        $total = (new ShopOrderTotal)->sumValueTotal('total', $dataTotal);
        session(['totalPrice' => $total]);
        //end total

        $dataOrder['user_id'] = $uID;
        $dataOrder['subtotal'] = $subtotal;
        $dataOrder['shipping'] = $shipping;
        $dataOrder['discount'] = $discount;
        $dataOrder['received'] = $received;
        $dataOrder['payment_status'] = self::PAYMENT_UNPAID;
        $dataOrder['shipping_status'] = self::SHIPPING_NOTSEND;
        $dataOrder['status'] = self::ORDER_STATUS_NEW;
        $dataOrder['currency'] = sc_currency_code();
        $dataOrder['exchange_rate'] = sc_currency_rate();
        $dataOrder['total'] = $total;
        $dataOrder['balance'] = $total + $received;
        $dataOrder['first_name'] = $shippingAddress['first_name'];
        $dataOrder['last_name'] = $shippingAddress['last_name'];
        $dataOrder['email'] = $shippingAddress['email'];
        $dataOrder['address1'] = $shippingAddress['address1'];
        $dataOrder['address2'] = $shippingAddress['address2'];
        $dataOrder['country'] = $shippingAddress['country'];
        $dataOrder['phone'] = $shippingAddress['phone'];
        $dataOrder['postcode'] = $shippingAddress['postcode']??null;
        $dataOrder['company'] = $shippingAddress['company']??null;
        $dataOrder['payment_method'] = $payment_method;
        $dataOrder['shipping_method'] = $shipping_method;
        $dataOrder['comment'] = $shippingAddress['comment'];
        $dataOrder['user_agent'] = $request->header('User-Agent');
        $dataOrder['ip'] = $request->ip();
        $dataOrder['created_at'] = date('Y-m-d H:i:s');

        $arrCartDetail = [];
        foreach (Cart::content() as $cartItem) { 
            $prod = ShopProduct::find($cartItem->id);
            $arrDetail['product_id'] = $cartItem->id;
            $arrDetail['adminid'] = $prod->adminid ?? 0;
            $arrDetail['name'] = $cartItem->name;
            $arrDetail['price'] = sc_currency_value($cartItem->price);
            $arrDetail['qty'] = $cartItem->qty;
            $arrDetail['attribute'] = ($cartItem->options) ? json_encode($cartItem->options) : null;
            $arrDetail['total_price'] = sc_currency_value($cartItem->price) * $cartItem->qty;
            $arrCartDetail[] = $arrDetail;
        }

        //Set session info order
        session(['dataOrder' => $dataOrder]);
        session(['arrCartDetail' => $arrCartDetail]);

        //Create new order
        $createOrder = (new ShopOrder)->createOrder($dataOrder, $dataTotal, $arrCartDetail);

        if ($createOrder['error'] == 1) {
            return redirect()->route('cart')->with(['error' => $createOrder['msg']]);
        }
        //Set session orderID
        session(['orderID' => $createOrder['orderID']]);
        if($payment_method=='stripe')
        {  
            return view(
            $this->templatePath . '.stripe',
            array(
                'title' => trans('front.checkout_title'),
                'description' => '',
                'keyword' => '',
            )
        );
        }
        else if($payment_method=='Paypal' OR $payment_method=='Cash')
        {
            $paymentMethod = sc_get_class_extension_controller('Payment', session('paymentMethod'));
            return (new $paymentMethod)->processOrder();
        }
        

    }

    /**
     * [addToCartAjax description]
     * @param Request $request [description]
     */
    public function addToCartAjax(Request $request)
    {
        if (!$request->ajax()) {
            return redirect()->route('cart');
        }
        $instance = request('instance') ?? 'default';
        $cart = \Cart::instance($instance);

        $id = request('id');
        $product = ShopProduct::find($id);
        $html = '';
        switch ($instance) {
            case 'default':
                if ($product->attributes->count() || $product->kind == SC_PRODUCT_GROUP) {
                    //Products have attributes or kind is group,
                    //need to select properties before adding to the cart
                    return response()->json(
                        [
                            'error' => 1,
                            'redirect' => $product->getUrl(),
                            'msg' => '',
                        ]
                    );
                }

                if ($product->allowSale()) {
                    $cart->add(
                        array(
                            'id' => $id,
                            'name' => $product->name,
                            'qty' => 1,
                            'price' => $product->getFinalPrice(),
                        )
                    );
                } else {
                    return response()->json(
                        [
                            'error' => 1,
                            'msg' => trans('cart.dont_allow_sale'),
                        ]
                    );
                }
                break;

            default:
                //Wishlist or Compare...
                ${'arrID' . $instance} = array_keys($cart->content()->groupBy('id')->toArray());
                if (!in_array($id, ${'arrID' . $instance})) {
                    try {
                        $cart->add(
                            array(
                                'id' => $id,
                                'name' => $product->name,
                                'qty' => 1,
                                'price' => $product->getFinalPrice(),
                            )
                        );
                    } catch (\Exception $e) {
                        return response()->json(
                            [
                                'error' => 1,
                                'msg' => $e->getMessage(),
                            ]
                        );
                    }

                } else {
                    return response()->json(
                        [
                            'error' => 1,
                            'msg' => trans('cart.exist', ['instance' => $instance]),
                        ]
                    );
                }
                break;
        }

        $carts = \Cart::getListCart($instance);
        if ($instance == 'default' && $carts['count']) {
            $html .= '<div><div class="shopping-cart-list">';
            foreach ($carts['items'] as $item) {
                $html .= '<div class="product product-widget"><div class="product-thumb">';
                $html .= '<img src="' . $item['image'] . '" alt="">';
                $html .= '</div>';
                $html .= '<div class="product-body">';
                $html .= '<h3 class="product-price">' . $item['price'] . ' <span class="qty">x' . $item['qty'] . '</span></h3>';
                $html .= '<h2 class="product-name"><a href="' . $item['url'] . '">' . $item['name'] . '</a></h2>';
                $html .= '</div>';
                $html .= '<a href="' . route("cart.remove", ['id' => $item['rowId']]) . '"><button class="cancel-btn"><i class="fa fa-trash"></i></button></a>';
                $html .= '</div>';
            }
            $html .= '</div></div>';
            $html .= '<div class="shopping-cart-btns">
                    <a href="' . route('cart') . '"><button class="main-btn">' . trans('front.cart_title') . '</button></a>
                    <a href="' . route('checkout') . '"><button class="primary-btn">' . trans('front.checkout_title') . ' <i class="fa fa-arrow-circle-right"></i></button></a>
                  </div>';
        }
        return response()->json(
            [
                'error' => 0,
                'count_cart' => $carts['count'],
                'instance' => $instance,
                'subtotal' => $carts['subtotal'],
                'html' => $html,
                'msg' => trans('cart.success', ['instance' => ($instance == 'default') ? 'cart' : $instance]),
            ]
        );

    }

    /**
     * [updateToCart description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateToCart(Request $request)
    {
        if (!$request->ajax()) {
            return redirect()->route('cart');
        }
        $id = request('id');
        $rowId = request('rowId');
        $product = ShopProduct::find($id);
        $new_qty = request('new_qty');
      
             if (Auth::user()) {
            $user = Auth::user();
                 $cartexist = UserCart::where('userid',$user->id)->where('pro_id',$id)->first();
                 $dataUpdate = array(
                    'qty' => $new_qty ? $new_qty : 0,
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                  $cartexist->update($dataUpdate);

                 if(!$new_qty) $cartexist->delete();
               
             }
           
            Cart::update($rowId, ($new_qty) ? $new_qty : 0);
            return response()->json(
                [
                    'error' => 0,
                ]
            );
       

    }

    /**
     * [wishlist description]
     * @return [type] [description]
     */
    public function wishlist()
    {

        $wishlist = Cart::instance('wishlist')->content();
        return view($this->templatePath . '.shop_wishlist',
            array(
                'title' => trans('front.wishlist'),
                'description' => '',
                'keyword' => '',
                'wishlist' => $wishlist,
                'layout_page' => 'shop_wishlist',
            )
        );
    }

    /**
     * [compare description]
     * @return [type] [description]
     */
    public function compare()
    {
        $compare = Cart::instance('compare')->content();
        return view($this->templatePath . '.shop_compare',
            array(
                'title' => trans('front.compare'),
                'description' => '',
                'keyword' => '',
                'compare' => $compare,
                'layout_page' => 'product_compare',
            )
        );
    }

    /**
     * [clearCart description]
     * @return [type] [description]
     */
    public function clearCart()
    {
        Cart::destroy();
       if (Auth::user()) {
            $user = Auth::user();
             UserCart::where('userid', $user->id)->delete();
        }
        return redirect()->route('cart');
    }

    /**
     * Remove item from cart
     * @author Naruto
     */
    public function removeItem($id = null, $pid= null)
    {  
        if ($id === null) {
            return redirect()->route('cart');
        }

        if (array_key_exists($id, Cart::content()->toArray())) {
            Cart::remove($id);
            if (Auth::user()) {
            $user = Auth::user();
                 UserCart::where('userid', $user->id)->where('pro_id', $pid)->delete();
            }
        }
        return redirect()->route('cart');
    }

    /**
     * [removeItem_wishlist description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function removeItemWishlist($id = null)
    {
        if ($id === null) {
            return redirect()->route('wishlist');
        }

        if (array_key_exists($id, Cart::instance('wishlist')->content()->toArray())) {
            Cart::instance('wishlist')->remove($id);
        }
        return redirect()->route('wishlist');
    }

    /**
     * [removeItemCompare description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function removeItemCompare($id = null)
    {
        if ($id === null) {
            return redirect()->route('compare');
        }

        if (array_key_exists($id, Cart::instance('compare')->content()->toArray())) {
            Cart::instance('compare')->remove($id);
        }
        return redirect()->route('compare');
    }

    /**
     * Complete order
     *
     * @return  [type]  [return description]
     */
     public function completeOrder()
    {  

        $orderID = session('orderID') ??0;
        if($orderID == 0){
            return redirect()->route('home', ['error' => 'Error Order ID!']);
        }

        $affilDisc = session('affilDisc');
        if($affilDisc>0)
        {
            AffiliateSuccess::create([
                'userid' => session('affilUser'),
                'commission' => $affilDisc,
                'product_price' => session('affproPrice'),
                'created_at' => date('Y-m-d H:i:s')
            ]);      
        }
        Cart::destroy(); // destroy cart

        session()->forget('paymentMethod'); //destroy paymentMethod
        session()->forget('shippingMethod'); //destroy shippingMethod
        session()->forget('totalMethod'); //destroy totalMethod
        session()->forget('otherMethod'); //destroy otherMethod
        session()->forget('Discount'); //destroy Discount
        session()->forget('dataTotal'); //destroy dataTotal
        session()->forget('dataOrder'); //destroy dataOrder
        session()->forget('arrCartDetail'); //destroy arrCartDetail
        session()->forget('orderID'); //destroy arrCartDetail
        session()->forget('affilDisc');
        session()->forget('affproPrice');

         $data = ShopOrder::with('details')->find($orderID)->toArray();
         $msg = '<table style="width:100%;max-width:800px;margin:auto;border-collapse: collapse;border:1px solid #e5e5e5;background-color: white;background:white;" bgcolor="white">
<tbody>
    <tr style="background-color:#333333;backgroung:#333333;width:100%;margin:auto;" bgcolor="#333333">
    <td style="width:100%;">
        <p style="color: white;padding: 30px 40px;font-size: 30px;margin:auto;">Thanks for shopping with us</p>
    </td>
</tr>
 <tr>
    <td>
        <p style="padding:50px 40px 20px;color:#636363;margin:auto;">Hi '.$data['first_name'].' '.$data['last_name'].',</p>
    </td>
 </tr>
 <tr>
    <td>
        <p style="padding:0px 40px 20px;color:#636363;margin:auto;">We have finished processing your order.</p>
    </td>
 </tr>
 <tr>
    <td>
        <p style="padding:0px 40px 20px;color:black;font-weight:bold;font-size:20px;margin:auto;">Downloads</p>
    </td>
 </tr>

 <tr>
  <td style="padding:10px 40px 10px;width:100%;margin:auto;">
    <table style="border-collapse: collapse;border:1px solid #e5e5e5;width:100%;">
        <thead>
            <tr>
                <th style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;width:35%;"><b>Product</b></th>
                <th style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;width:25%;"><b>Expires</b></th>
                <th style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;width:40%;"><b>Downloads</b></th>
            </tr>
        </thead>
        <tbody>';
         foreach ($data['details'] as $key => $detail) {
            $files = ProductFiles::where('pro_id',$detail['product_id'])->get();
             if($files)
            {
                foreach ($files as $file) {
                    $filename = substr($file->name, strrpos($file->name, '/') + 1);
             $msg .= '<tr>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;">
                    <a style="color:#636363;text-decoration: underline;">'.$detail['name'].'</a>
                </td>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;">
                    <p style="color:#636363;">Never</p>
                </td>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;">
                    <a style="color:#636363;text-decoration: underline;" href="'.route("file.downloadcnt", ['id' => $file->id, 'email' => $data['email'] ]).'">'.$filename.'</a>
                </td>

            </tr>'; 
                }
            }
        }

            $msg .= '</tbody>
    </table>
  </td>
 </tr>

 <tr>
    <td>
        <b style="display:block;padding:30px 40px 20px;color:black;font-weight:bold;font-size:20px;">[Order #'.$orderID.'] ('.date('F d, Y', strtotime($data['created_at'])).')</b>
    </td>
 </tr>
 
 <tr>
    <td style="padding:10px 40px 10px;width:100%;">
      <table style="border-collapse: collapse;border:1px solid #e5e5e5;width:100%;">
          <thead>
              <tr>
                  <th style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;width:60%;"><b>Product</b></th>
                  <th style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;width:20%;"><b>Quantity</b></th>
                  <th style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;width:20%;"><b>price</b></th>
              </tr>
          </thead>
          <tbody>';
          foreach ($data['details'] as $key => $detail) {
            $format = ProductFiles::getFileExt($detail['product_id']);
              $msg .= '<tr>
                  <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;">
                      <p style="color:#636363;">'.$detail['name'].'</p>
                      <p style="color:#636363;"><b>Format: </b><span> '.$format.'</span></p>
                      
                  </td>
                  <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;">
                      <p style="color:#636363;">' . number_format($detail['qty']) . '</p>
                  </td>
                  <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;">
                      <p style="color:#636363;">$' . sc_currency_render($detail['total_price'], '', '', '', false) . '</p>
                  </td>
  
              </tr>';
          }

            $msg .= '<tr>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;border-top:4px solid #e5e5e5;border-right: 0px;">
                    <p style="color:#636363;"><b>Subtotal:</b></p>
                    
                </td>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;border-top:4px solid #e5e5e5;border-left: 0px;">
                  
                </td>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;border-top:4px solid #e5e5e5;">
                    <p style="color:#636363;">$'. sc_currency_render($data['subtotal'], '', '', '', false).'</p>
                </td>

            </tr>
           
            <tr>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;border-right: 0px;">
                    <p style="color:#636363;"><b>Total:</b></p>
                    
                </td>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;border-left: 0px;">
                  
                </td>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;">
                    <p style="color:#636363;">$'.sc_currency_render($data['total'], '', '', '', false).'</p>
                </td>

            </tr>
           
     
          </tbody>
      </table>
    </td>
   </tr>

</tbody>
</table>';   

        if (sc_config('order_success_to_admin') || sc_config('order_success_to_customer')) {
           
            $checkContent = (new ShopEmailTemplate)->where('group', 'order_success_to_admin')->where('status', 1)->first();
            $checkContentCustomer = (new ShopEmailTemplate)->where('group', 'order_success_to_customer')->where('status', 1)->first();
            if ($checkContent || $checkContentCustomer) {
                
               
               

                if (sc_config('order_success_to_admin') && $checkContent) {                 
                    $data_mail = [
                        'content' => $msg,
                    ];
                    $config = [
                        'to' => sc_store('admin_email'),
                        'subject' => trans('order.send_mail.new_title') . '#' . $orderID,
                    ];
                    $header = "From: Every Cg <".sc_store('email')."> \r\n";
                     $header .= "MIME-Version: 1.0\r\n";
                     $header .= "Content-type: text/html\r\n";
                    $to = sc_store('admin_email');
                    $subject = trans('order.send_mail.new_title') . '#' . $orderID;
                     mail($to,$subject,$msg,$header);
                    //sc_send_mail('mail.order_success_to_admin', $data_mail, $config, []);
                }
                if (sc_config('order_success_to_customer') && $checkContentCustomer) {
                    
                    $data_mail_customer = [
                        'content' => $msg,
                    ];
                    $config = [
                        'to' => $data['email'],
                        'replyTo' => sc_store('admin_email'),
                        'subject' => trans('order.send_mail.new_title'),
                    ];
                    $subject2 = trans('order.send_mail.new_title');
                     mail($data['email'],$subject2,$msg,$header);
                    //sc_send_mail('mail.order_success_to_customer', $data_mail_customer2, $config2, []);
                }
            }

        }
       

        return redirect()->route('order.success')->with('orderID', $orderID);
    }

    /**
     * Page order success
     *
     * @return  [type]  [return description]
     */
    public function orderSuccess(){

        if(!session('orderID')) {
            return redirect()->route('home');
        }
        $orderID  = session('orderID');
        $orderDetail = ShopOrder::with('details')->find($orderID)->toArray();
      
        return view(
            $this->templatePath . '.shop_order_success', 
            [
                'title' => trans('order.success.title'),
                'layout_page' =>'shop_order_success',
                'orderDetail' => $orderDetail,
            ]
        );
    }

    public function stripePost(Request $request)
    {
         $validator = Validator::make($request->all(), [
        'card' => 'required',
        'month' => 'required',
        'year' => 'required',
        'cvc' => 'required'
      ]);
    
        $data = $request->all();
        $dataTotal = session('totalPrice') ?? '';

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
       
       $payment =  Stripe\Charge::create ([
                "amount" =>  $dataTotal * 100,
                "currency" => "usd",
                "source" => $token['id'],
                "description" => "Product Purchase" 
        ]);       

         $orderID = session('orderID') ??0;
        if($orderID == 0){
            return redirect()->route('home', ['error' => 'Error Order ID!']);
        }

        $affilDisc = session('affilDisc');
        if($affilDisc>0)
        {
            AffiliateSuccess::create([
                'userid' => session('affilUser'),
                'commission' => $affilDisc,
                'product_price' => session('affproPrice'),
                'created_at' => date('Y-m-d H:i:s')
            ]);      
        }
        Cart::destroy(); // destroy cart
        
        if (Auth::user()) {
            $user = Auth::user();
             UserCart::where('userid', $user->id)->delete();
        }

        session()->forget('paymentMethod'); //destroy paymentMethod
        session()->forget('shippingPrice'); //destroy paymentMethod
        session()->forget('shippingMethod'); //destroy shippingMethod
        session()->forget('totalMethod'); //destroy totalMethod
        session()->forget('otherMethod'); //destroy otherMethod
        session()->forget('Discount'); //destroy Discount
        session()->forget('dataTotal'); //destroy dataTotal
        session()->forget('dataOrder'); //destroy dataOrder
        session()->forget('arrCartDetail'); //destroy arrCartDetail
        session()->forget('orderID'); //destroy arrCartDetail
        session()->forget('affilDisc'); 
        session()->forget('affproPrice');

    
       $data = ShopOrder::with('details')->find($orderID)->toArray();
         $msg = '<table style="width:100%;max-width:800px;margin:auto;border-collapse: collapse;border:1px solid #e5e5e5;background-color: white;background:white;" bgcolor="white">
<tbody>
    <tr style="background-color:#333333;backgroung:#333333;width:100%;margin:auto;" bgcolor="#333333">
    <td style="width:100%;">
        <p style="color: white;padding: 30px 40px;font-size: 30px;margin:auto;">Thanks for shopping with us</p>
    </td>
</tr>
 <tr>
    <td>
        <p style="padding:50px 40px 20px;color:#636363;margin:auto;">Hi '.$data['first_name'].' '.$data['last_name'].',</p>
    </td>
 </tr>
 <tr>
    <td>
        <p style="padding:0px 40px 20px;color:#636363;margin:auto;">We have finished processing your order.</p>
    </td>
 </tr>
 <tr>
    <td>
        <p style="padding:0px 40px 20px;color:black;font-weight:bold;font-size:20px;margin:auto;">Downloads</p>
    </td>
 </tr>

 <tr>
  <td style="padding:10px 40px 10px;width:100%;margin:auto;">
    <table style="border-collapse: collapse;border:1px solid #e5e5e5;width:100%;">
        <thead>
            <tr>
                <th style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;width:35%;"><b>Product</b></th>
                <th style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;width:25%;"><b>Expires</b></th>
                <th style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;width:40%;"><b>Downloads</b></th>
            </tr>
        </thead>
        <tbody>';
         foreach ($data['details'] as $key => $detail) {
            $files = ProductFiles::where('pro_id',$detail['product_id'])->get();
             if($files)
            {
                foreach ($files as $file) {
                    $filename = substr($file->name, strrpos($file->name, '/') + 1);
             $msg .= '<tr>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;">
                    <a style="color:#636363;text-decoration: underline;">'.$detail['name'].'</a>
                </td>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;">
                    <p style="color:#636363;">Never</p>
                </td>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;">
                    <a style="color:#636363;text-decoration: underline;" href="'.route("file.downloadcnt", ['id' => $file->id, 'email' => $data['email'] ]).'">'.$filename.'</a>
                </td>

            </tr>'; 
                }
            }
        }

            $msg .= '</tbody>
    </table>
  </td>
 </tr>

 <tr>
    <td>
        <b style="display:block;padding:30px 40px 20px;color:black;font-weight:bold;font-size:20px;">[Order #'.$orderID.'] ('.date('F d, Y', strtotime($data['created_at'])).')</b>
    </td>
 </tr>
 
 <tr>
    <td style="padding:10px 40px 10px;width:100%;">
      <table style="border-collapse: collapse;border:1px solid #e5e5e5;width:100%;">
          <thead>
              <tr>
                  <th style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;width:60%;"><b>Product</b></th>
                  <th style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;width:20%;"><b>Quantity</b></th>
                  <th style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;width:20%;"><b>price</b></th>
              </tr>
          </thead>
          <tbody>';
          foreach ($data['details'] as $key => $detail) {
            $format = ProductFiles::getFileExt($detail['product_id']);
              $msg .= '<tr>
                  <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;">
                      <p style="color:#636363;">'.$detail['name'].'</p>
                      <p style="color:#636363;"><b>Format: </b><span> '.$format.'</span></p>
                      
                  </td>
                  <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;">
                      <p style="color:#636363;">' . number_format($detail['qty']) . '</p>
                  </td>
                  <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;">
                      <p style="color:#636363;">$' . sc_currency_render($detail['total_price'], '', '', '', false) . '</p>
                  </td>
  
              </tr>';
          }

            $msg .= '<tr>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;border-top:4px solid #e5e5e5;border-right: 0px;">
                    <p style="color:#636363;"><b>Subtotal:</b></p>
                    
                </td>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;border-top:4px solid #e5e5e5;border-left: 0px;">
                  
                </td>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;border-top:4px solid #e5e5e5;">
                    <p style="color:#636363;">$'. sc_currency_render($data['subtotal'], '', '', '', false).'</p>
                </td>

            </tr>
           
            <tr>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;border-right: 0px;">
                    <p style="color:#636363;"><b>Total:</b></p>
                    
                </td>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;border-left: 0px;">
                  
                </td>
                <td style="padding:10px 8px;text-align:left;border:1px solid #e5e5e5;">
                    <p style="color:#636363;">$'.sc_currency_render($data['total'], '', '', '', false).'</p>
                </td>

            </tr>
           
     
          </tbody>
      </table>
    </td>
   </tr>

</tbody>
</table>';           

            

        

        if (sc_config('order_success_to_admin') || sc_config('order_success_to_customer')) {           
            $checkContent = (new ShopEmailTemplate)->where('group', 'order_success_to_admin')->where('status', 1)->first();
            $checkContentCustomer = (new ShopEmailTemplate)->where('group', 'order_success_to_customer')->where('status', 1)->first();
            if ($checkContent || $checkContentCustomer) {
                

                if (sc_config('order_success_to_admin') && $checkContent) {
                   
                    $data_mail = [
                        'content' => $msg,
                    ];
                    $config = [
                        'to' => sc_store('admin_email'),
                        'subject' => trans('order.send_mail.new_title') . '#' . $orderID,
                    ];
                    $header = "From: Every Cg <".sc_store('email')."> \r\n";
                     $header .= "MIME-Version: 1.0\r\n";
                     $header .= "Content-type: text/html\r\n";
                   $to = sc_store('admin_email');
                    $subject = trans('order.send_mail.new_title') . '#' . $orderID;
                     mail($to,$subject,$msg,$header);
                    //sc_send_mail('mail.order_success_to_admin', $data_mail, $config, []);
                }
                if (sc_config('order_success_to_customer') && $checkContentCustomer) {
                   
                    $data_mail_customer2 = [
                        'content' => $msg,
                    ];
                    $config2 = [
                        'to' => $data['email'],
                        'replyTo' => sc_store('admin_email'),
                        'subject' => trans('order.send_mail.new_title'),
                    ];
                     $subject2 = trans('order.send_mail.new_title');
                     mail($data['email'],$subject2,$msg,$header);
                    //sc_send_mail('mail.order_success_to_customer', $data_mail_customer2, $config2, []);
                }
            }

        }

      

        ShopOrder::find($orderID)->update(['status' => 5]);
                //Add history
                $dataHistory = [
                    'order_id' => $orderID,
                    'content' => 'Transaction '.$payment['id'],
                    'user_id' => auth()->user()->id ?? 0,
                    'order_status_id' => 1,
                ];
            (new ShopOrder)->addOrderHistory($dataHistory);

         return redirect()->route('order.success')->with('orderID', $orderID);

      } catch (Exception $ex) {
         return redirect()->route('cart')->with(['error' => $ex->getMessage()]);
    }

    } 
     public function stripeGet()  
    {
        
       
    }

    public function createConnected()
    {
       Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
       $result =  Stripe\Account::create ([
                    "country" => 'US',
                    "type" => "express",        
                    'capabilities' => [               
                        'transfers' => [
                          'requested' => true,
                        ],
                        'card_payments' => [
                          'requested' => true,
                        ],
                      ],  
                    //"business_type" => "individual",     
                   'business_profile' => array(
                        'url' => 'https://dev.demo-swapithub.com/ecomm/',
                    ),
                ]);  

     $linkres =  Stripe\AccountLink::create([
        "account" => $result->id,
        "refresh_url" => "https://dev.demo-swapithub.com/ecomm/",        
        "return_url" => "https://dev.demo-swapithub.com/ecomm/",        
        "type" => "account_onboarding",
        ]); 

        $user = Auth::user();
        $vendorUser = AdminUser::where('username', $user->username)->first();
        $vendorUpdate = [
            'account_id' => $result->id,
            'express_url' => $linkres->url
        ];
        $vendorUser->update($vendorUpdate);
        return redirect($linkres->url);
             
       
    }

    public function fundTransfer()
    {
         Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $orders = ShopOrderDetail::Where('fund_transfer',0)
        ->Where('adminid','!=', 1)
        ->selectRaw("SUM(total_price) as totalprice, adminid, id")
        ->groupBy('adminid')
        ->get();


        $platfees = Marketplace::Where('id',1)->first();

        if (count($orders) > 0) {

            foreach($orders as $order)
            {
                $vendorUser = AdminUser::find($order->adminid);
                $fees = round($platfees->fees * ($order->totalprice / 100),2);
                $tranfer = $order->totalprice-$fees; 

                $trfResult =  Stripe\Transfer::create([
                  'amount' => $tranfer * 100,
                  'currency' => 'usd',
                  'destination' => $vendorUser->account_id,
                ]);



                $createTranfer = array(
                    'tranferid' => $trfResult->id,
                    'balance_transaction' => $trfResult->balance_transaction,
                    'destination' => $trfResult->destination,
                    'created_at' => date('Y-m-d H:i:s'),
                );


                Transfers::create($createTranfer);

                ShopOrderDetail::where('adminid', $order->adminid)->update(['fund_transfer' => 1 ]);
            } 
        }
        echo 'success';

        /*$account = \Stripe\Account::retrieve('acct_1LZuXwPjaBWpybTO');
        echo $account->charges_enabled;*/
          
    }


}
