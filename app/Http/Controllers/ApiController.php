<?php
#app/Http/Controller/ShopFront.php
namespace App\Http\Controllers;
use App\Models\ShopAttributeGroup;
use App\Models\ShopBrand;
use App\Models\ShopBanner;
use App\Models\ShopCategory;  
use App\Models\ShopProduct;
use App\Models\ShopVendor;
use App\Models\ShopUser;
use App\Models\AdminStore;
use App\Models\ShopOrder;  
use App\Models\AdminStoreDescription;
use Illuminate\Http\Request;
use App\Models\ShopPage;
use App\Models\ShopEmailTemplate;
use App\Models\Tags;
use App\Models\ShopBlockContent;
use App\Models\ShopNews;
use App\Models\UserCart;
use App\Models\ProductFiles;
use App\Models\ShopCountry;
use App\Models\Contactus;
use App\Models\Widget;
use App\Models\Faq;
use App\Models\License;
use Illuminate\Database\Eloquent\jsonPaginate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;
use Cart;
use Stripe;

class ApiController extends GeneralController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function allCategory()
    {
        $itemsList = ShopCategory::where('status',1)->where('top',1)->get();
        return response()->json($itemsList, 200);
    }
     public function categoryDetail($id)
    {
        $category = (new ShopCategory)->where('status',1)->where('top',1)
            ->with('descriptions')
            ->find($id);
        if ($category) {
            return response()->json($category, 200);
        } else {
            return response()->json([], 404);
        }
    }  
    public function allProduct()
    {
        $products = DB::table('shop_product')->leftJoin('shop_product_description', 'shop_product_description.product_id', 'shop_product.id')->where('status',1)
            ->select('id','name','price','image','alias','content','stock')
            ->jsonPaginate();

            $result2 = array();
    foreach ($products as $product) {
       
            $data = array(
                "id" => $product->id,         
                "name" => $product->name,         
                "price" => $product->price,         
                "image" => $product->image,         
                "alias" => $product->alias,
                "stock" => $product->stock,               
                "content" => $product->content       
            );
            array_push($result2,$data);
        
    }
    return response()->json([
                    'data' => $result2
                ], 200);

        
    }

    public function allProductNew()
    {
        $products = DB::table('shop_product')->leftJoin('shop_product_description', 'shop_product_description.product_id', 'shop_product.id')->where('status',1)
            ->select('id','name','price','image','alias','content','stock')
            ->jsonPaginate();

            $result2 = array();
    foreach ($products as $product) {
       
            $data = array(
                "id" => $product->id,         
                "name" => $product->name,         
                "price" => $product->price,         
                "image" => $product->image,         
                "alias" => $product->alias, 
                "stock" => $product->stock,              
                "content" => $product->content       
            );
            array_push($result2,$data);
        
    }
    return response()->json($result2, 200);

        
    }

     public function productDetail($alias)
    {
        $product = (new ShopProduct)->getProduct($id = null, $alias);
        $categories = $product->categories->keyBy('id')->toArray();
        $arrCategoriId = array_keys($categories);
        $productsToCategory = (new ShopProduct)->getProductsToCategory($arrCategoriId, $limit = sc_config('product_relation'), $opt = 'random');

        $ptag = '1,2';
        $ptag1  = explode(',', $ptag);
        $tags = Tags::productTags($ptag1);
        $result2 = array();
           $data = array(
                "id" => $product->id,         
                "name" => $product->name,         
                "price" => $product->price,         
                "image" => $product->image,         
                "alias" => $product->alias,         
                "content" => $product->content,
                "stock" => $product->stock,      
                'images'=>  $product->images,
                'tags'=>  $tags,
                'related' =>$productsToCategory
            );
        array_push($result2,$data);
       //$mergedArray = array_merge($product->toArray(), $tags->toArray());
       if ($result2) {
            return response()->json($result2, 200);
        } else {
            return response()->json('Product not found', 404);
        }
    }

      public function productDetailNew($alias)
    {
        $product = (new ShopProduct)->getProduct($id = null, $alias);
        $product->view += 1;
        $product->date_lastview = date('Y-m-d H:i:s');
        $product->save();
        $categories = $product->categories->keyBy('id')->toArray();
        $arrCategoriId = array_keys($categories);
        $productsToCategory = (new ShopProduct)->getProductsToCategory($arrCategoriId, $limit = sc_config('product_relation'), $opt = 'random');

        $ptag1  = explode(',', $product->tags);
        $tags = Tags::productTags($ptag1);
        $result2 = array();
           $data = array(
                "id" => $product->id,         
                "name" => $product->name,         
                "price" => $product->price,         
                "image" => $product->image,         
                "alias" => $product->alias,         
                "content" => $product->content,
                "stock" => $product->stock,      
                'images'=>  $product->images,
                'tags'=>  $tags,
                'related' =>$productsToCategory
            );
        array_push($result2,$data);
       //$mergedArray = array_merge($product->toArray(), $tags->toArray());
       if ($result2) {
            return response()->json($result2, 200);
        } else {
            return response()->json('Product not found', 404);
        }
        
    }
    public function productToCategory($id)
    {
        $category = ShopCategory::where('alias',$id)->first();
         $products = (new ShopProduct)->getProductsToCategory($category->id);
            $result2 = array();
            foreach ($products as $product) {               
                $data = array(
                    "id" => $product->id,         
                    "name" => $product->name,         
                    "price" => $product->price,         
                    "image" => $product->image,         
                    "alias" => $product->alias, 
                    "stock" => $product->stock,              
                    "content" => strip_tags($product->content)        
                );
                array_push($result2,$data);                
            }
            return response()->json($result2, 200);
    }

    public function productToCategoryNew($alias)
    {
        $category = ShopCategory::where('alias',$alias)->first();
         $products = (new ShopProduct)->getProductsToCategory($category->id);
            $result2 = array();
            foreach ($products as $product) {               
                $data = array(
                    "id" => $product->id,         
                    "name" => $product->name,         
                    "price" => $product->price,         
                    "image" => $product->image,         
                    "alias" => $product->alias, 
                    "stock" => $product->stock,              
                    "content" => strip_tags($product->content)        
                );
                array_push($result2,$data);                
            }
            return response()->json($result2, 200);
    }

    public function siteInfo()
    {
         $infos = AdminStore::first();
          return response()->json($infos, 200);
    }

    public function homeInfo()
    {
          $infos = ShopPage::where('alias', 'home')
            ->where('status', 1)
            ->first();
          return response()->json($infos, 200);
    }

    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'qty' => 'required',
            'sessionid' => 'required',
        ]);

      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      }  
        $data = request()->all();
        $product_id = $data['product_id'];          
        $qty = $data['qty'];
        $product = ShopProduct::find($product_id);

         
        
        $cartexist = UserCart::where('sessionid',$data['sessionid'])->where('pro_id',$product_id)->first();
        if(!empty($cartexist))
        {
            if ($product->stock < $cartexist->qty+$qty) {
                 return response()->json(['error'=> '1', 'msg' => trans('cart.over', ['item' => $product->name, 'avail' => $product->stock])], 409);
               
                exit();
             }
             $dataUpdate = array(
                'qty' => $cartexist->qty+$qty,
                'price' => $product->getFinalPrice(),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $cartexist->update($dataUpdate); 

        }
        else
        {
            if ($product->stock < $qty) {
                 return response()->json(['error'=> '1', 'msg' => trans('cart.over', ['item' => $product->name, 'avail' => $product->stock])], 409);
                exit();
             }

            $dataCart = array(
                'pro_id' => $product_id,
                'qty' => $qty,
                'price' => $product->getFinalPrice(),
                'sessionid' => $data['sessionid'],
                'created_at' => date('Y-m-d H:i:s'),
            );
        
            UserCart::create($dataCart);
        } 
        $cart = UserCart::where('sessionid',$data['sessionid'])->get();

        $result2 = array();  
        $total=0;          
         foreach($cart as $item)
         {         
            $subtotal = $item->price*$item->qty;
            $total +=$subtotal;
            $product = ShopProduct::find($item->pro_id);                      
            $cartdata = array(
                "id" => $item->id,         
                "sku" => $product->sku,         
                "name" => $product->name,         
                "qty" => $item->qty,         
                "price" => $product->price,         
                "subtotal" => $subtotal  
            );
            array_push($result2,$cartdata);                
       
        }
       return response()->json([
        'Cart' => $result2, 'Total' => $total], 200);          
        

    }

     public function getCart(Request $request)
    {
         $validator = Validator::make($request->all(), [
        'sessionid' => 'required',
        ]);

      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      } 
      $data = request()->all(); 
        $cart = UserCart::where('sessionid',$data['sessionid'])->get();

        $result2 = array();  
        $total=0;          
         foreach($cart as $item)
        {         
            $subtotal = $item->price*$item->qty;
            $total +=$subtotal;
            $product = ShopProduct::find($item->pro_id);                      
            $cartdata = array(
                "id" => $item->id, 
                "sku" => $product->sku,          
                "name" => $product->name,         
                "qty" => $item->qty,         
                "price" => $product->price,         
                "subtotal" => $subtotal  
            );
            array_push($result2,$cartdata);             
       }

       return response()->json([
        'Cart' => $result2, 'Total' => $total], 200);        

    }

    public function updateCart(Request $request)
    {
         $validator = Validator::make($request->all(), [
        'sessionid' => 'required',
        'qty' => 'required',
        'cartid' => 'required',
        ]);

      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      } 
      $data = request()->all(); 


        $cartexist = UserCart::where('sessionid',$data['sessionid'])->where('id',$data['cartid'])->first();
        if(!empty($cartexist))
        { 
            $product = ShopProduct::find($cartexist->pro_id);
            if ($product->stock < $data['qty']) {
                 return response()->json(['error'=> '1', 'msg' => trans('cart.over', ['item' => $product->name, 'avail' => $product->stock])], 409);
                exit();
            }    

             $dataUpdate = array(
                'qty' => $data['qty'],
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $cartexist->update($dataUpdate); 

        }
        $cart = UserCart::where('sessionid',$data['sessionid'])->get();

        $result2 = array();  
        $total=0;          
         foreach($cart as $item)
        {         
            $subtotal = $item->price*$item->qty;
            $total +=$subtotal;
            $product = ShopProduct::find($item->pro_id);                      
            $cartdata = array(
                "id" => $item->id,  
                 "sku" => $product->sku,         
                "name" => $product->name,         
                "qty" => $item->qty,         
                "price" => $product->price,         
                "subtotal" => $subtotal  
            );
            array_push($result2,$cartdata);             
       }

       return response()->json([
        'Cart' => $result2, 'Total' => $total], 200);         

    }

    public function deleteCartitem(Request $request)
    {
         $validator = Validator::make($request->all(), [
        'sessionid' => 'required',
        'cartid' => 'required',
        ]);

      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      } 
      $data = request()->all();

        UserCart::destroy($data['cartid']);  
        $cart = UserCart::where('sessionid',$data['sessionid'])->get();
        if (count($cart) > 0) {
            $result2 = array();  
            $total=0;          
             foreach($cart as $item)
            {         
                $subtotal = $item->price*$item->qty;
                $total +=$subtotal;
                $product = ShopProduct::find($item->pro_id);                      
                $cartdata = array(
                    "id" => $item->id, 
                     "sku" => $product->sku,          
                    "name" => $product->name,         
                    "qty" => $item->qty,         
                    "price" => $product->price,         
                    "subtotal" => $subtotal  
                );
                array_push($result2,$cartdata);             
           }

           return response()->json(['message' => 'Cart item removed successfully!',
            'Cart' => $result2, 'Total' => $total], 200); 
        }
        else
        {
            return response()->json([], 200);

        }               

    }

     public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'first_name' => 'required',
        'sessionid' => 'required'
      ]);

      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      }

        $formdata = request()->all();
      
        $cart = UserCart::where('sessionid',$formdata['sessionid'])->get();
        if (count($cart) > 0) {
            $result2 = array();  
            $total=0;     
            $arrCartDetail = [];
        foreach ($cart as $cartItem) {
            $product = ShopProduct::find($cartItem->pro_id);
            if ($product->stock < $cartItem['qty']) {
                return response()->json(['error'=> '1', 'msg' => trans('cart.over', ['item' => $product->name, 'avail' => $product->stock])], 409);
                exit();
            }    
            $subtotal = $cartItem->price*$cartItem->qty;
            $total +=$subtotal;
            
            $arrDetail['product_id'] = $cartItem->pro_id;
            $arrDetail['adminid'] = $product->adminid ?? 0;
            $arrDetail['name'] = $product->name;
            $arrDetail['price'] = $cartItem->price;
            $arrDetail['qty'] = $cartItem->qty;
            $arrDetail['attribute'] = null;
            $arrDetail['total_price'] = $subtotal;
            $arrCartDetail[] = $arrDetail;
        }     
        $dataTotal= [];
        $arrTotal['title'] = 'Received';
        $arrTotal['code'] = 'received';
        $arrTotal['value'] = 0;
        $arrTotal['text'] = 0;
        $arrTotal['sort'] = "200";
        $dataTotal[] = $arrTotal;

        $arrTotal2['title'] = 'Total';
        $arrTotal2['code'] = 'total';
        $arrTotal2['value'] =$total;
        $arrTotal2['text'] = $total;
        $arrTotal2['sort'] = "100";
        $dataTotal[] = $arrTotal2;

        $arrTotal3['title'] = 'Coupon/Discount';
        $arrTotal3['code'] = 'discount';
        $arrTotal3['value'] = 0;
        $arrTotal3['text'] =0;
        $arrTotal3['sort'] = "20";
        $dataTotal[] = $arrTotal3;

        $arrTotal4['title'] = 'Tax';
        $arrTotal4['code'] = 'tax';
        $arrTotal4['value'] = 0;
        $arrTotal4['text'] =0;
        $arrTotal4['sort'] = "2";
        $dataTotal[] = $arrTotal4;

        $arrTotal5['title'] = 'Sub Total';
        $arrTotal5['code'] = 'subtotal';
        $arrTotal5['value'] = $total;
        $arrTotal5['text'] =$total;
        $arrTotal5['sort'] = "1";
        $dataTotal[] = $arrTotal5;

        $dataOrder['user_id'] = 0;
        $dataOrder['subtotal'] = $total;
        $dataOrder['shipping'] = 0;
        $dataOrder['discount'] = 0;
        $dataOrder['received'] = 0;
        $dataOrder['payment_status'] = 0;
        $dataOrder['shipping_status'] = 0;
        $dataOrder['status'] = 1;
        $dataOrder['currency'] = 'USD';
        $dataOrder['exchange_rate'] = 1;
        $dataOrder['total'] = $total;
        $dataOrder['balance'] = $total;
        $dataOrder['first_name'] = $formdata['first_name'];
        $dataOrder['last_name'] = $formdata['last_name'];
        $dataOrder['email'] = $formdata['email'];
        $dataOrder['address1'] = $formdata['address1'];
        $dataOrder['address2'] = $formdata['address2'];
        $dataOrder['country'] = $formdata['country'];
        $dataOrder['phone'] = $formdata['phone'];
        $dataOrder['postcode'] = $formdata['postcode']??null;
        $dataOrder['company'] = $formdata['company']??null;
        $dataOrder['payment_method'] = 'Card';
        $dataOrder['shipping_method'] = 'ShippingStandard';
        $dataOrder['comment'] = $formdata['comment'];
        $dataOrder['ip'] = $request->ip();
        $dataOrder['created_at'] = date('Y-m-d H:i:s');

         $createOrder = (new ShopOrder)->createOrder($dataOrder, $dataTotal, $arrCartDetail);

         

        if ($createOrder['error'] == 1) {
            sc_report($createOrder['msg']);
        }
        return response()->json(['order'=>$createOrder, 'total'=>$total], 200);
           
        }
        else
        {
            return response()->json([], 204);

        }                  

    }

    public function getSlider()
    {
        $itemsList = ShopBanner::where('status',1)->get();
        return response()->json($itemsList, 200);
    }

    public function stripePost(Request $request)
    {
         $validator = Validator::make($request->all(), [
        'card_number' => 'required',
        'exp_month' => 'required',
        'exp_year' => 'required',
        'cvc' => 'required',
        'amount' => 'required',
      ]);

      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      }
        $data = $request->all();


       Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
       $token =  Stripe\Token::create ([
                      'card' => [
                        'number' => $data['card_number'],
                        'exp_month' => $data['exp_month'],
                        'exp_year' => $data['exp_year'],
                        'cvc' => $data['cvc'],
                      ],
                    ]);             
       
       $payment =  Stripe\Charge::create ([
                "amount" =>  $data['amount'] * 100,
                "currency" => "inr",
                "source" => $token['id'],
                "description" => "Product Purchase" 
        ]);
       if($payment['id'])
       {
            return response()->json([
        'success' => 'payment_id '.$payment['id']], 200);
       }
       else{

         return response()->json([
        'error' => 'Payment error'], 500);            
       }       

    } 

    public function completeOrder(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'orderid' => 'required',
            'sessionid' => 'required'
          ]);

      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      }

      UserCart::where('sessionid', $request['sessionid'])->delete();

        $orderID = $request['orderid']; 
        $data = ShopOrder::with('details')->find($orderID)->toArray();
            $checkContent = (new ShopEmailTemplate)->where('group', 'order_success_to_admin')->where('status', 1)->first();

       $msg = '<html><head></head><body style="margin:0px; background:#eae9ea; width:100%; padding:0; text-decoration:none;">
<div style="margin:0 auto; width:600px; background:#fff;">
<table style="width:100%; padding:0px 40px; float:left; margin:0px; background:#fff;">
<tr style="float:left; margin-top:10px;">
<td style=" width:100%"></td>
</tr>
<tr>
<td style="border-bottom:1px solid#d7d7d7;"> <p style=" width:75%; margin:20px 0 20px 0; float:left; color:#000; font-size:20px; font-family:arial; font-weight:bold;">File Links</p>
</td>
</tr>

<tr>
<td><p>Here are your file links:</p>';
    

         foreach ($data['details'] as $key => $detail) {
            $files = ProductFiles::where('pro_id',$detail['product_id'])->get();
            if($files)
            {
                foreach ($files as $file) {
                    $filename = substr($file->name, strrpos($file->name, '/') + 1);
                    $msg .= '<a href="'.asset($file->name).'"  download>'.$filename.'</a><br/>';
                }
                
            }            
         }
         $msg .= '</td></tr></table><!---cont-->
<div class="footer" style="width:100%; float:left; padding-bottom:30px;">
<div class="foot_logo" style="margin:0; padding:0;display:inline-block;text-align:center;width:100%;">
</div>
</div>
</div>
</body></html>';          

            

        

        if (sc_config('order_success_to_admin') || sc_config('order_success_to_customer')) {
            $data = ShopOrder::with('details')->find($orderID)->toArray();
            $checkContent = (new ShopEmailTemplate)->where('group', 'order_success_to_admin')->where('status', 1)->first();
            $checkContentCustomer = (new ShopEmailTemplate)->where('group', 'order_success_to_customer')->where('status', 1)->first();
            if ($checkContent || $checkContentCustomer) {
                $orderDetail = '';
                $orderDetail .= '<tr>
                                    <td>' . trans('email.order.sort') . '</td>
                                    <td>' . trans('email.order.sku') . '</td>
                                    <td>' . trans('email.order.name') . '</td>
                                    <td>' . trans('email.order.price') . '</td>
                                    <td>' . trans('email.order.qty') . '</td>
                                    <td>' . trans('email.order.total') . '</td>
                                </tr>';
                foreach ($data['details'] as $key => $detail) {
                    $orderDetail .= '<tr>
                                    <td>' . ($key + 1) . '</td>
                                    <td>' . $detail['sku'] . '</td>
                                    <td>' . $detail['name'] . '</td>
                                    <td>' . sc_currency_render($detail['price'], '', '', '', false) . '</td>
                                    <td>' . number_format($detail['qty']) . '</td>
                                    <td align="right">' . sc_currency_render($detail['total_price'], '', '', '', false) . '</td>
                                </tr>';
                }
                $dataFind = [
                    '/\{\{\$title\}\}/',
                    '/\{\{\$orderID\}\}/',
                    '/\{\{\$firstName\}\}/',
                    '/\{\{\$lastName\}\}/',
                    '/\{\{\$toname\}\}/',
                    '/\{\{\$address\}\}/',
                    '/\{\{\$address1\}\}/',
                    '/\{\{\$address2\}\}/',
                    '/\{\{\$email\}\}/',
                    '/\{\{\$phone\}\}/',
                    '/\{\{\$comment\}\}/',
                    '/\{\{\$orderDetail\}\}/',
                    '/\{\{\$subtotal\}\}/',
                    '/\{\{\$shipping\}\}/',
                    '/\{\{\$discount\}\}/',
                    '/\{\{\$total\}\}/',
                ];
                $dataReplace = [
                    trans('order.send_mail.new_title') . '#' . $orderID,
                    $orderID,
                    $data['first_name'],
                    $data['last_name'],
                    $data['first_name'].' '.$data['last_name'],
                    $data['address1'] . ' ' . $data['address2'],
                    $data['address1'],
                    $data['address2'],
                    $data['email'],
                    $data['phone'],
                    $data['comment'],
                    $orderDetail,
                    sc_currency_render($data['subtotal'], '', '', '', false),
                    sc_currency_render($data['shipping'], '', '', '', false),
                    sc_currency_render($data['discount'], '', '', '', false),
                    sc_currency_render($data['total'], '', '', '', false),
                ];

                if (sc_config('order_success_to_admin') && $checkContent) {
                    $content = $checkContent->text;
                    $content = preg_replace($dataFind, $dataReplace, $content);
                    $data_mail = [
                        'content' => $content,
                    ];
                    $config = [
                        'to' => sc_store('admin_email'),
                        'subject' => trans('order.send_mail.new_title') . '#' . $orderID,
                    ];
                    sc_send_mail('mail.order_success_to_admin', $data_mail, $config, []);
                }
                if (sc_config('order_success_to_customer') && $checkContentCustomer) {
                    $contentCustomer = $checkContentCustomer->text;
                    $contentCustomer = preg_replace($dataFind, $dataReplace, $contentCustomer);
                    $data_mail_customer = [
                        'content' => $contentCustomer,
                    ];
                    $config = [
                        'to' => $data['email'],
                        'replyTo' => sc_store('admin_email'),
                        'subject' => trans('order.send_mail.new_title'),
                    ];
                    sc_send_mail('mail.order_success_to_customer', $data_mail_customer, $config, []);
                }
            }

        }

        $data_mail_customer = [
                'content' => $msg,
                ];
            $config = [
                'to' => $data['email'],
                'replyTo' => sc_store('admin_email'),
                'subject' => 'File links',
            ];
            sc_send_mail('mail.order_success_to_customer', $data_mail_customer, $config, []);

        ShopOrder::find($orderID)->update(['status' => 5]);
                //Add history
                $dataHistory = [
                    'order_id' => $orderID,
                    'content' => 'Transaction '.$orderID,
                    'user_id' =>  0,
                    'order_status_id' => 1,
                ];
            (new ShopOrder)->addOrderHistory($dataHistory);

        return response()->json([
        'success' => 'orderid '.$orderID], 200);
    }

    public function tagProducts($tag)
    {   
        $tg = Tags::where('alias',$tag)->first();
        $products = DB::table('shop_product')->leftJoin('shop_product_description', 'shop_product_description.product_id', 'shop_product.id')->where('status',1)->whereRaw('find_in_set('.$tg->id.',shop_product.tags)')
            ->select('id','name','price','image','alias','content')
            ->jsonPaginate();

            $result2 = array();
    foreach ($products as $product) {
       
            $data = array(
                "id" => $product->id,         
                "name" => $product->name,         
                "price" => $product->price,         
                "image" => $product->image,         
                "alias" => $product->alias,         
                "content" => $product->content       
            );
            array_push($result2,$data);
        
    }
    return response()->json($result2
                , 200);

        
    }

     public function licenseProducts($license)
    {
        $products = DB::table('shop_product')->leftJoin('shop_product_description', 'shop_product_description.product_id', 'shop_product.id')->where('status',1)->whereRaw('find_in_set('.$license.',shop_product.license)')
            ->select('id','name','price','image','alias','content')
            ->jsonPaginate();

            $result2 = array();
    foreach ($products as $product) {
       
            $data = array(
                "id" => $product->id,         
                "name" => $product->name,         
                "price" => $product->price,         
                "image" => $product->image,         
                "alias" => $product->alias,         
                "content" => $product->content       
            );
            array_push($result2,$data);
        
    }
    return response()->json([
                    'data' => $result2
                ], 200);

        
    }

    public function blockContent()
    {
        $itemsList = ShopBlockContent::where('status',1)->get();
        return response()->json($itemsList, 200);
    }

    public function search($id)
    {
        $sortBy = null;
        $sortOrder = 'asc';
        $filter_sort =  ''; 
          $filterArr = [
            'price_desc' => ['price', 'desc'],
            'price_asc' => ['price', 'asc'],
            'sort_desc' => ['sort', 'desc'],
            'sort_asc' => ['sort', 'asc'],
            'id_desc' => ['id', 'desc'],
            'id_asc' => ['id', 'asc'],
        ];
        if (array_key_exists($filter_sort, $filterArr)) {
            $sortBy = $filterArr[$filter_sort][0];
            $sortOrder = $filterArr[$filter_sort][1];
        }      
        
        $keyword = $id ?? '';
       $products =  (new ShopProduct)->getSearch($keyword, $limit = sc_config('product_list'), $sortBy, $sortOrder);

       $result2 = array();
        foreach ($products as $product) {
       
            $data = array(
                "id" => $product->id,         
                "name" => $product->name,         
                "price" => $product->price,         
                "image" => $product->image,         
                "alias" => $product->alias,         
                "content" => $product->content       
            );
            array_push($result2,$data);        
        }

    return response()->json($result2, 200);
    }


    public function postContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',
            'content' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
          ]);

      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      }
               
      
        $data = $request->all();
        $data['content'] = str_replace("\n", "<br>", $data['content']);

        $dataContact = array(
                'name' => $data['name'],
                'title' => $data['title'],
                'content' => $data['content'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'created_at' => date('Y-m-d H:i:s'),
            );
        
          Contactus::create($dataContact);



        if (sc_config('contact_to_admin')) {
            $checkContent = (new ShopEmailTemplate)
                ->where('group', 'contact_to_admin')
                ->where('status', 1)
                ->first();
            if ($checkContent) {
                $content = $checkContent->text;
                $dataFind = [
                    '/\{\{\$title\}\}/',
                    '/\{\{\$name\}\}/',
                    '/\{\{\$email\}\}/',
                    '/\{\{\$phone\}\}/',
                    '/\{\{\$content\}\}/',
                ];
                $dataReplace = [
                    $data['title'],
                    $data['name'],
                    $data['email'],
                    $data['phone'],
                    $data['content'],
                ];
                $content = preg_replace($dataFind, $dataReplace, $content);
                $data_email = [
                    'content' => $content,
                ];

                $config = [
                    'to' => sc_store('admin_email'),
                    'replyTo' => $data['email'],
                    'subject' => $data['title'],
                ];
                sc_send_mail('mail.contact_to_admin', $data_email, $config, []);
            }
        }

         return response()->json([
        'success' => 'Mail sent successfully!'], 200);
    }

     public function getNews()
    {
        $itemsList = ShopNews::where('status',1)->get();
        $result2 = array();
        foreach($itemsList as $news){
           $data = array(
                "id" => $news->id,         
                "title" => $news->title,          
                "image" => $news->image,         
                "alias" => $news->alias,         
                "content" => $news->content,
                'date'=>  date('M d, Y', strtotime($news->created_at)),
                'time'=>  date('h:i A', strtotime($news->created_at))
            );
            array_push($result2,$data);
        }

        if ($result2) {
            return response()->json($result2, 200);
        } else {
            return response()->json([], 404);
        }
        return response()->json($itemsList, 200);
    }

     public function newsdetail($id)
    {
        $news = (new ShopNews)->where('status',1)->where('alias',$id)->first();
        $rltdids  = explode(',', $news->related);
        $related = ShopNews::relatedNews($rltdids);  

        $result = array();
        foreach($related as $rel) {
           $data2 = array(
                "id" => $rel->id,         
                "title" => $rel->title,          
                "image" => $rel->image,         
                "alias" => $rel->alias,         
                "content" => $rel->content,
                'date'=>  date('M d, Y', strtotime($rel->created_at)),
                'time'=>  date('h:i A', strtotime($rel->created_at)),
            );
           array_push($result,$data2);
       }
        

        $result2 = array();
           $data = array(
                "id" => $news->id,         
                "title" => $news->title,          
                "image" => $news->image,         
                "alias" => $news->alias,         
                "content" => $news->content,
                'date'=>  date('M d, Y', strtotime($news->created_at)),
                'time'=>  date('h:i A', strtotime($news->created_at)),
                'related'=>  $result
            );
        array_push($result2,$data);
        if ($result2) {
            return response()->json($result2, 200);
        } else {
            return response()->json([], 404);
        }
    }

    public function getCounties()
    {   
        $itemsList = ShopCountry::get();
        return response()->json($itemsList, 200);
    }

     public function cartCount(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'sessionid' => 'required'
          ]);

      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      }
                
        $cart = UserCart::where('sessionid',$request['sessionid'])->sum('qty');      
       
       return response()->json([
        'cartCount' => $cart], 200);      

    }

     public function getPage($alias)
    {
          $infos = ShopPage::where('alias', $alias)
            ->where('status', 1)
            ->first();
          return response()->json($infos, 200);
    }
   
   public function featuredProduct()
    {
        $products = DB::table('shop_product')->leftJoin('shop_product_description', 'shop_product_description.product_id', 'shop_product.id')->where('status',1)->where('featured',1)
            ->select('id','name','price','image','alias','content','stock')
            ->jsonPaginate();

            $result2 = array();
    foreach ($products as $product) {
       
            $data = array(
                "id" => $product->id,         
                "name" => $product->name,         
                "price" => $product->price,         
                "stock" => $product->stock,         
                "image" => $product->image,         
                "alias" => $product->alias,         
                "content" => $product->content       
            );
            array_push($result2,$data);
        
    }
    return response()->json($result2, 200);

        
    }

    public function order($orderid)
    {
       
        $order = ShopOrder::with('orderTotal')->with('details')->where('id', $orderid)->first();

        $ord = array(
             "id" => $order['id'],         
                "subtotal" => $order['subtotal'],         
                "shipping" => $order['shipping'],         
                "discount" => $order['discount'],         
                "status" => $order['status'],         
                "total" => $order['total'],    
                "currency" => $order['currency'],    
                "first_name" => $order['first_name'],    
                "last_name" => $order['last_name'],    
                "address1" => $order['address1'],    
                "address2" => $order['address2'],   
                "country" => $order['country'],   
                "company" => $order['company'],   
                "postcode" => $order['postcode'],   
                "phone" => $order['phone'],   
                "email" => $order['email'],   
                "comment" => $order['comment'],   
                "created_at" => date('M d, Y h:i A', strtotime($order->created_at)),
        );

         $result2 = array();

         foreach ($order['details'] as $key => $detail) {
            $product = ShopProduct::find($detail['product_id']);          
       
         $data = array(
                "id" => $detail['id'],         
                "order_id" => $detail['order_id'],         
                "product_id" => $detail['product_id'],         
                "name" => $detail['name'],         
                "price" => $detail['price'],         
                "qty" => $detail['qty'],    
                "total_price" => $detail['total_price'],    
                "sku" => $detail['sku'],    
                "currency" => $detail['currency'],    
                "created_at" => $detail['created_at'],    
                "image" => $product['image']    
            );
            array_push($result2,$data);        
    
         }

        return response()->json(['order' => $ord, 'products' => $result2], 200);
    }


    public function getYearMonth()
    {
       $result2 = array();
        for ($i = date('Y'); $i <= date('Y')+10; $i++)
        { 
            
            $data = array(
                "year" => $i                   
            );
            array_push($result2,$data);        
        }

        $result = array();
        for ($i = 1; $i <= 12; $i++) 
        { 
            $num_padded = sprintf("%02d", $i);  
            $data1 = array(
                "month" => $num_padded
                    
            );
            array_push($result,$data1);        
        }       

       return response()->json(['years' => $result2, 'months' => $result], 200);
    }

     public function getWidget($alias)
    {
      $widget = Widget::where('alias',$alias)->where('status',1)->first();
       return response()->json($widget, 200);
    }

    public function getFaq()
    {
      $faqs = Faq::where('status',1)->orderBy('sort', 'ASC')->get();
       return response()->json($faqs, 200);

    }

    public function getTags()
    {
       $tags = Tags::where('status',1)->get();
       return response()->json($tags, 200);

    }

    public function getLicense()
    {
       $license = License::where('status',1)->get();
       return response()->json($license, 200);

    }



}