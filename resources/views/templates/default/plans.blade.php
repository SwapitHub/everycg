@extends($templatePath.'.shop_layout')
@section('content')
<style>
    input.cmn-toggle-round:checked+label:after {
  margin-left: 32px;
}

.switch {
  position: relative;
  left: 47.5%;
}

.cmn-toggle {
  position: absolute;
  margin-left: -9999px;
  visibility: hidden;
}

.cmn-toggle+label {
  display: block;
  position: relative;
  cursor: pointer;
  outline: none;
  user-select: none;
}

input.cmn-toggle-round+label {
  padding: 2px;
  width: 50px;
  height: 20px;
  background-color: #dddddd;
  border-radius: 60px;
}

input.cmn-toggle-round+label:before,
input.cmn-toggle-round+label:after {
  display: block;
  position: absolute;
  top: 1px;
  left: 1px;
  bottom: 1px;
  content: "";
}

input.cmn-toggle-round+label:before {
  right: 1px;
  background-color: #f1f1f1;
  border-radius: 60px;
  transition: background 0.4s;
}

input.cmn-toggle-round+label:after {
  width: 20px;
  background-color: #fff;
  border-radius: 100%;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
  transition: margin 0.4s;
}

input.cmn-toggle-round:checked+label:before {
  background-image: Linear-Gradient( to left, hsl(236, 72%, 79%), hsl(237, 63%, 64%));
}
.hide-year{
    display: none;
}
</style>
<div class="col-md-10 width_int news-col">
            <div class="loader-div">
                <div class="row news-inner">
                   <div class="banner-sect-category"
                    style="background: url('{{ asset('/data/page/bg.jpg') }}');">
                    <div class="breadcrumb"><a href="{{ route('home') }}">Home </a> <span> <i class="fa fa-angle-right"></i></span>
                        {{ $title }}</div>
                    <h1 class="cat-title">{{ $title }}</h1>
                 </div>
                   <div class="switch">
                    <span class="monthly-text active">Monthly commitment</span>
                        <div class="switch-inner">
                          <input id="cmn-toggle-1" class="cmn-toggle cmn-toggle-round" type="checkbox" />
                          <label for="cmn-toggle-1"></label>
                          </div> 
                        <span class="yearly-text">Yearly commitment</span>    
                                          
                    </div>
                 @if (!empty($plans))
                    @foreach ($plans as $plan)
                 <div class="col-md-3 col-6 hide-{{ $plan->interval }}">
                    <div class="plan-outer">
                        <h4>{{ $plan->name }}</h4>
                        <div class="plan-price"><span>$</span><span class="pprice">{{ $plan->price }}</span>  </div>
                        <div class="interval-sec">/ {{ $plan->interval }}</div>
                        <p class="interval">Billed Monthly</p>
                        <a href="{{route('purchase-plan',['id'=>$plan->id])}}">Subscribe</a>
                    </div>
                 </div> 
                  @endforeach
                  @endif                  
                    
                </div>
                
            </div>
        </div>



@endsection



@push('styles')
{{-- switch --}}
<link rel="stylesheet" href="{{ asset('admin/plugin/bootstrap-switch.min.css')}}">



@endpush

@push('scripts')
{{-- switch --}}
<script src="{{ asset('admin/plugin/bootstrap-switch.min.js')}}"></script>
<script type="text/javascript">
    $('.cmn-toggle').attr('checked',false);
   $('.cmn-toggle').click(function() {
   var checked = $(this).is(':checked');
            if (checked) {
                $('.hide-month').hide();
                $('.hide-year').show();
                $('p.interval').text('Billed Yearly');
                $('.monthly-text').removeClass('active');
                $('.yearly-text').addClass('active');
               
            } else {
               $('.hide-year').hide();
               $('.hide-month').show();
               $('p.interval').text('Billed Monthly');
               $('.yearly-text').removeClass('active');
                $('.monthly-text').addClass('active');
             
            }
});

</script>



@endpush
