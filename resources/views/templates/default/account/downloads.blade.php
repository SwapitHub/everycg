@extends($templatePath.'.shop_layout')

@section('main')

 <div class="col-md-10 orders-col orders-inner overflow_add  width_int">
    <div class="loader-div">
        <h2 class="Orders_List">{{ $title }}</h2>
        @if (count($orders) ==0)
            <div class="col-md-12 text-danger">
                Download list is empty
            </div>
        @else
        <table class="cart-table table box table-bordered user_cart_table">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Product</th>
                    <th>Expires</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>

                <?php $i=1; foreach ($orders as $order) {
                     foreach ($order['details'] as $key => $detail) {
                       
                    $files = \App\Models\ProductFiles::where('pro_id',$detail['product_id'])->get();
                    foreach ($files as $file) {
                    $filename = substr($file->name, strrpos($file->name, '/') + 1); ?>
                 
                    <tr class="row_cart">
                        <td>{{ $i++ }}</td>
                        <td>{{ $detail['name'] }} </td>
                        <td>Never</td>
                        <td><a href="{{ asset($file->name) }}">{{ $filename }}</a></td>                        
                    </tr>
                   <?php } } } ?>
                
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection


