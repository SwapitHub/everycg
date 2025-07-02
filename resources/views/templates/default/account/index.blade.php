@extends($templatePath.'.shop_layout')

@section('main')
<div class="col-md-10 profile-col profile-inner width_int">
   <div class="user_profile_edt full-width">
      <div class="mem-top-sect">
         <p>Hello <b>{{ $user->first_name}} {{ $user->last_name}}!</b> Not you? <a href="{{ route('logout') }}" onclick="event.preventDefault();  document.getElementById('logout-form').submit();">Logout</a> </p>
         <p>From your account dashboard you can view your <b>recent orders,</b> manage your <b>billing address,</b> and edit your password and account </b>
      </div>  
      <div class="row">
         <div class="col-sm-6 col-md-4 card-box-mem ">
            <a class="anchor-inner" href="{{ route('member.order_list') }}">
               <i class="fa fa-file-text" aria-hidden="true"></i>  
               <p>orders</p>
            </a>
         </div>
         <div class="col-sm-6 col-md-4 card-box-mem ">
            <a class="anchor-inner" href="{{ route('member.download') }}">
               <i class="fa fa-download" aria-hidden="true"></i>
               <p>Downloads</p>
            </a>
         </div>
         <div class="col-sm-6 col-md-4 card-box-mem ">
            <a class="anchor-inner" href="{{ route('member.change_address') }}">
               <i class="fa fa-map-marker" aria-hidden="true"></i>
               <p>Address</p>
            </a>
         </div>
         <div class="col-sm-6 col-md-4 card-box-mem ">
            <a class="anchor-inner" href="/sc_admin/account-setting">
               <i class="fa fa-user-circle-o" aria-hidden="true"></i>
               <p>Account Details</p>
            </a>
         </div>
         <div class="col-sm-6 col-md-4 card-box-mem ">
            <a class="anchor-inner" href="{{ route('logout') }}" onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
               <i class="fa fa-sign-out" aria-hidden="true"></i>
               <p>Logout</p>
            </a>
         </div>
      </div>
   </div>
</div>

@endsection


@push('scripts')
<script>
   $(document).ready(function(){
      var username = $("form#vendor-login input[name='username']").val();
      var password = $("form#vendor-login input[name='password']").val();
      $.ajax({
          url:'{{ route('admin.login') }}',
          type:'POST',
          data:{username:username,password:password,"_token": "{{ csrf_token() }}"},
          success: function(data){
               console.log('success');
          }
      });

   });
   
</script>

@endpush


