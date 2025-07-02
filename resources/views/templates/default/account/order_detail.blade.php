@extends($templatePath.'.shop_layout')

@section('main')
<div class="col-md-10 orderdetails-col ordersdetail-inner width_int">
   <div class="order-detail-main">
      <h2>Order Information</h2>
      <div class="detail-title"><span>Order ID:-</span>{{ $order->id }}</div>
      <div class="detail-title"><span>Customer Name:-</span>{{ $order->first_name }} {{ $order->last_name }}</div>
      <div class="detail-title"><span>Email:-</span>{{ $order->email }}</div>
      <div class="detail-title"><span>Address:-</span>{{ $order->address1 }} {{ $order->address2 }}</div>
      <div class="detail-title"><span>Phone:-</span>{{ $order->phone }}</div>
      <div class="detail-title"><span>Date and Time:-</span><?php echo date('M d, Y', strtotime($order->created_at)) .' '.  date('h:i A', strtotime($order->created_at)); ?></div>
      <div class="detail-title"><span></span></div>
   </div>
   <div class="overflow_add ">
      <table class="cart-table table box table-bordered Order_Details_user_get">
         <h2>Order Details</h2>
         <tr>
            <td>
               <table class="Product_table">
                  <thead>
                     <tr class="table_data_h">
                        <th>No.</th>
                        <th>SKU</th>
                        <th>Product Name</th>
                        <th>Product Image</th>
                        <th>Quantity</th>
                        <th>Price</th>
                     </tr>
                  </thead>
                  <tbody class="table_data_tbody">
                      <?php $i=1; foreach ($order['details'] as $key => $detail) {
                    $product = \App\Models\ShopProduct::find($detail['product_id']); ?>
                         <tr class="table_data_b">
                            <td>{{ $i++ }}</td>
                            <td>{{ $detail['sku'] }}</td>
                            <td>{{ $detail['name'] }}</td>
                            <td class="table_data_images"><img
                               src="{{ asset($product->image) }}"
                               alt="Easy Polo Black Edition 9"></td>
                            <td>{{ $detail['qty'] }}</td>
                            <td> ${{ $detail['total_price'] }}</td>
                         </tr>
                    <?php } ?>
                  </tbody>
               </table>
            </td>
         </tr>
         <tr>
            <td>
               <div class="Total_pr">Total:- ${{ $order['total'] }}</div>
            </td>
         </tr>
         
      </table>
   </div>
</div>

@endsection


