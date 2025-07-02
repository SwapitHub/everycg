<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

use Hash;
use App\Models\ShopUser;
use App\Models\ShopProduct;
use App\Models\ShopOrderTotal;
use App\Models\ShopOrder;
use App\Models\UserCart;
use App\Models\ShopEmailTemplate;
use App\Models\ProductFiles;
use Auth;

class ShopCart extends Controller
{
    public function user()
    {
        $user = Auth::user();
        return response()->json(['user' => $user], 200);
    }


     public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'qty' => 'required'
        ]);

      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      }  

        $user = Auth::user();

        $data = request()->all();
        $product_id = $data['product_id'];        
        $qty = $data['qty'];
        $product = ShopProduct::find($product_id);
        
        $cartexist = UserCart::where('userid',$user->id)->where('pro_id',$product_id)->first();
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
                'userid' => $user->id,
                'created_at' => date('Y-m-d H:i:s'),
            );
        
            UserCart::create($dataCart);
        } 
        $cart = UserCart::where('userid',$user->id)->get();

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

     public function getCart()
    {
        $user = Auth::user();       
        $cart = UserCart::where('userid',$user->id)->get();

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

    public function cartCount()
    {
        $user = Auth::user();       
        $cart = UserCart::where('userid',$user->id)->sum('qty'); 
             
        return response()->json([
        'cartCount' => $cart], 200);            

    }

     public function updateCart(Request $request)
    {
         $validator = Validator::make($request->all(), [
        'qty' => 'required',
        'cartid' => 'required',
        ]);

      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      } 
        $data = request()->all(); 
        $user = Auth::user();   
        $cartexist = UserCart::where('userid',$user->id)->where('id',$data['cartid'])->first();
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
        $cart = UserCart::where('userid',$user->id)->get();

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

       $cartcnt = UserCart::where('userid',$user->id)->sum('qty'); 

       return response()->json([
        'Cart' => $result2, 'Total' => $total, 'cartCount' => $cartcnt, 'success' => 200], 200);         

    }

     public function deleteCartitem($id)
    {
        UserCart::destroy($id);
        $user = Auth::user();       
        $cart = UserCart::where('userid',$user->id)->get();
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
        'first_name' => 'required'
      ]);

      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      }

        $formdata = request()->all();

        $user = Auth::user();      
        $cart = UserCart::where('userid',$user->id)->get();
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
            $product = ShopProduct::find($cartItem->pro_id);
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

        $dataOrder['user_id'] = $user->id;
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


    public function completeOrder(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'orderid' => 'required'
          ]);

      if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], 401);
      }

      $user = Auth::user();  
      UserCart::where('userid', $user->id)->delete();

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
    
}