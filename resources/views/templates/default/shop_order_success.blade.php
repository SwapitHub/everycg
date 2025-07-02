@extends($templatePath.'.shop_layout')

@section('main')
<div class="col-md-10 thankyou-col thankyou-inner width_int table-responsive">
   <table class="thank_you">
      <thead>
         <tr>
            <th>
               <a class="logo" tabindex="-1" href="{{ route('home') }}"><img alt="Logo"
                  src="{{ asset(sc_store('logo')) }}" title="Logo" width="267"></a>
            </th>
         </tr>
      </thead>
      <tbody>
         <tr>
            <td>
               <h3 class="thank_you_title">Thank you for your order!</h3>
               <p class="thank_you_inf">Your order is confirmed! Review your order information below.</p>
            </td>
         </tr>
        <!--  <tr>
            <td class="pad">
               <div class="alignment">
                  <img alt="Alternate text" class="bigimg" src=""
                     title="Alternate text" width="680">
               </div>
            </td>
         </tr> -->
         <tr>
            <td>
               <table class="Product_table">
                  <thead>
                     <tr class="table_data_h">
                        <th>No.</th>
                        <th>Order ID.</th>
                        <th>SKU</th>
                        <th>Product Name</th>
                        <th>Product Image</th>
                        <th>Quantity</th>
                        <th>Price</th>
                     </tr>
                  </thead>
                  <tbody class="table_data_tbody">
                    <?php $i=1; foreach ($orderDetail['details'] as $key => $detail) {
                    $product = \App\Models\ShopProduct::find($detail['product_id']); ?>
                         <tr class="table_data_b">
                            <td>{{ $i++ }}</td>
                            <td>{{ $orderDetail['id'] }}</td>
                            <td>{{ $detail['sku'] }}</td>
                            <td>{{ $detail['name'] }}</td>
                            <td class="table_data_images"><img
                               src="{{ asset($product->image) }}"
                               alt="Easy Polo Black Edition 9"></td>
                            <td>{{ $detail['qty'] }}</td>
                            <th> ${{ $detail['total_price'] }}</th>
                         </tr>
                    <?php } ?>
                  </tbody>
               </table>
            </td>
         </tr>
         <tr>
            <td>
               <div class="Total_pr">Total:- ${{ $orderDetail['total'] }}</div>
            </td>
         </tr>
         <tr>
            <td>
               <div class="button-shop-more"><a href="{{ route('product.all') }}"> <i class="fa fa-arrow-left"></i> Shop
                  More</a>
               </div>
            </td>
         </tr>
      </tbody>
   </table>
</div>

@endsection



