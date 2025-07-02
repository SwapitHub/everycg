@extends($templatePath.'.shop_layout')

@section('main')

 <div class="col-md-10 orders-col orders-inner overflow_add  width_int">
    <div class="loader-div">
        <h2 class="Orders_List">{{ $title }}</h2>
        @if (count($orders) ==0)
            <div class="col-md-12 text-danger">
                Order list is empty
            </div>
        @else
        <table class="cart-table table box table-bordered user_cart_table">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Total</th>
                    <th>View Detail</th>
                </tr>
            </thead>
            <tbody>
                 @foreach($orders as $order)
                    @php
                        $n = (isset($n)?$n:0);
                        $n++;
                        // $order = App\Models\ShopProduct::find($item->id);
                    @endphp
                    <tr class="row_cart">
                        <td>{{ $n }}</td>
                        <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                        <td>{{ $order->email }}</td>
                        <td>${{ number_format($order->total) }}</td>
                        <td><a href="{{ route('member.order',['id'=>$order->id]) }}">View Order </a></td>
                    </tr>
                    @endforeach
                
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection


