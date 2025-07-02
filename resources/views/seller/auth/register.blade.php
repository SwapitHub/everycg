@extends($templatePath.'.shop_layout')

@section('main')

   <div class="col-md-10 register-col width_int">
            <div class="background-image-form"
                style="background: url('{{ asset('images/background-.jpg') }}');">
                <div class="form-common-main register-inner">
                    <div class="register-logo"><img
                            src="{{ asset('images/logo-forms.png') }}">
                    </div>
                    <div class="form-common-main_data">
                        <h2>Register</h2>
                        <form class="Register_from" action="{{ url('/seller_register')}}" method="post">
                             {!! csrf_field() !!}
                            <div class="mb-3 form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" class="{{ ($errors->has('name'))?"input-error":"" }}">
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        {{ $errors->first('name') }}
                                    </span>
                                @endif
                            </div>
                          
                            <div class="mb-3 form-group {{ $errors->has('email') ? ' has-error' : '' }}"><label class="form-label">Email address</label><input
                                    type="email" class="form-control {{ ($errors->has('email'))?"input-error":"" }}" id="email" placeholder="Email" name="email"
                                    value="{{ old('email') }}">
                                 @if ($errors->has('email'))
                                <span class="help-block">
                                    {{ $errors->first('email') }}
                                </span>
                                @endif
                            </div>
                            <div class="mb-3 form-group {{ $errors->has('password') ? ' has-error' : '' }}"><label class="form-label">Password</label><input name="password" type="password" placeholder="password" value="" class="{{ ($errors->has('password'))?"input-error":"" }}">
                                 @if ($errors->has('password'))
                                    <span class="help-block">
                                        {{ $errors->first('password') }}
                                    </span>
                                    @endif
                                </div>
                            <div class="mb-3 form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}"><label class="form-label">Confirm Password</label><input name="password_confirmation" type="password" placeholder="Confirm password" value="" class="{{ ($errors->has('password_confirmation'))?"input-error":"" }}">
                             @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    {{ $errors->first('password_confirmation') }}
                                </span>
                                @endif
                            </div>
                                <button class="btn btn-primary" type="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection