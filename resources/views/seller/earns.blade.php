@extends($templatePath.'.shop_layout')

@section('main')

    <div class="col-md-10 width_int">        
          <div class="earn-history-outer">
            <h2>{{ $title }}</h2>
             @if (count($earns) ==0)
            <div class="col-md-12 text-danger">
                Earn list is empty
            </div>
        @else
        <div class="earns-table">
          <table class="cart-table table box table-bordered user_cart_table">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Product Price</th>
                    <th>Affiliate Commission</th>
                    <th>Sold On</th>                    
                </tr>
            </thead>
            <tbody>
                 @foreach($earns as $earn)
                    @php
                        $n = (isset($n)?$n:0);
                        $n++;                        
                    @endphp
                    <tr class="row_cart">
                        <td>{{ $n }}</td>
                        <td>${{ $earn->product_price }}</td>
                        <td>${{ $earn->commission }}</td>
                        <td>{{ $earn->created_at }}</td>
                        
                    </tr>
                    @endforeach
                
            </tbody>
        </table>
      </div>
        @endif
         
          </div>
     </div>
  
@endsection 

