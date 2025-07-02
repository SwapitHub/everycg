@extends($templatePath.'.shop_layout')

@section('main')
  <div class=" login-col width_int">
            <div class="background-image-form"
            style="background: url('{{ asset('images/background-.jpg') }}');">
                <div class="main-login-inner form-common-main ">
                     @if (Session::has('message'))
                       <div class="alert alert-info">{{ Session::get('message') }}</div>
                    @endif
                    <div class="login-logo"><img
                        src="{{ asset('images/logo-forms.png') }}">
                    </div>
                    <h1 class="login-title">Login</h1>
                    <form  class="login_form" action="{{ route('postLogin') }}" method="post">
                          {!! csrf_field() !!}
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}"><label for="email">Email Address</label><input name="email" type="text" class="form-control {{ ($errors->has('email'))?"input-error":"" }}" value="{{ old('email') }}">
                         @if ($errors->has('email'))
                            <span class="help-block">
                                {{ $errors->first('email') }}
                            </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}"><label for="password">Password</label><input name="password" type="password" id="pass_log_id" class="form-control {{ ($errors->has('password'))?"input-error":"" }}" value=""><span class="password__show"><i class="fa fa-eye-slash toggle-password"></i></span>
                         @if ($errors->has('password'))
                            <span class="help-block">
                                {{ $errors->first('password') }}
                            </span>
                            @endif
                     </div>
                        <div class="form-group"><button type="submit" class="btn btn-primary"> Login</button></div>
                    </form>
                </div>
            </div>
        </div>
@endsection



@push('scripts')
<script>

$("body").on('click', '.toggle-password', function() {
  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $("#pass_log_id");
  if (input.attr("type") === "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }

});
</script>
@endpush
