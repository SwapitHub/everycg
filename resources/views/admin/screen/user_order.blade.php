@extends('admin.layout')

@section('main')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title">{{ $title_description??'' }}</h2>

                <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a href="{{ route('admin_uorders.index') }}" class="btn  btn-flat btn-default" title="List"><i
                                class="fa fa-list"></i><span class="hidden-xs"> {{trans('admin.back_list')}}</span></a>
                    </div>
                </div>
            </div>

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
                        <th></th>
                     </tr>
                  </thead>
                  <tbody class="table_data_tbody">
                      <?php $i=1; foreach ($order['details'] as $key => $detail) {
                    $product = \App\Models\ShopProduct::find($detail['product_id']); 
                    $files = \App\Models\ProductFiles::where('pro_id',$detail['product_id'])->get();
                    ?>
                         <tr class="table_data_b">
                            <td>{{ $i++ }}</td>
                            <td>{{ $detail['sku'] }}</td>
                            <td>{{ $detail['name'] }}</td>
                            <td class="table_data_images"><img
                               src="{{ asset($product->image) }}"
                               alt="Easy Polo Black Edition 9"></td>
                            <td>{{ $detail['qty'] }}</td>
                            <td> ${{ $detail['total_price'] }}</td>
                              <td><li class="dropdown">
                               <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="color: #ff7f33;font-weight: 700;font-size: 16px;">
                                Download <i class="fa fa-solid fa-caret-down" style="color: #c5c5c5;"></i>
                               </a>
                               <ul class="dropdown-menu">  
                                 <!-- User image -->
                                 <?php  
                                 foreach ($files as $file) {
                                 $filename = substr($file->name, strrpos($file->name, '/') + 1); ?>
                                 <li class="user-header">
                                 <a data-src="{{ $file->id }}" href="{{ asset($file->name) }}">{{ $filename }}</a>
                                 </li>  
                                 <?php } ?>                             
                               
                               </ul>
                             </li></td>                     
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
          

       
    </div>
</div>
@endsection

   @push('scripts')
  <script>
        $(document).ready(function() {
        $('.Order_Details_user_get .user-header a').click(function(e){
          e.preventDefault();
          var file = $(this).data('src');
          var src = $(this).attr('href');
           $.ajax({
              url:'{{ route('member.download_count') }}',
              type:'POST',
              data:{file:file,"_token": "{{ csrf_token() }}"},
              success: function(data){               
               window.location.href = src;
              }
            });
            //return false;
        });
    });

</script>

   @endpush
