@extends($templatePath.'.shop_layout')

@section('main')

   <div class=" register-col width_int">
            <div class="background-image-form"
                style="background: url('{{ asset('images/background-.jpg') }}');">
                <div class="form-common-main register-inner">
                    <div class="register-logo"><img
                            src="{{ asset('images/logo-forms.png') }}">
                    </div>
                    <div class="form-common-main_data">
                        <h1>Register</h1>
                        <form class="Register_from" action="{{route('postRegister')}}" method="post">
                             {!! csrf_field() !!}
                            <div class="mb-3 form-group {{ $errors->has('reg_first_name') ? ' has-error' : '' }}">
                                <label class="form-label">First Name</label>
                                <input type="text" name="reg_first_name" placeholder="First Name" value="{{ old('reg_first_name') }}" class="{{ ($errors->has('reg_first_name'))?"input-error":"" }}">
                                @if ($errors->has('reg_first_name'))
                                    <span class="help-block">
                                        {{ $errors->first('reg_first_name') }}
                                    </span>
                                @endif
                            </div>
                             <div class="mb-3 form-group {{ $errors->has('reg_last_name') ? ' has-error' : '' }}">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="reg_last_name" placeholder="Last Name" value="{{ old('reg_last_name') }}" class="{{ ($errors->has('reg_last_name'))?"input-error":"" }}">
                                @if ($errors->has('reg_last_name'))
                                    <span class="help-block">
                                        {{ $errors->first('reg_last_name') }}
                                    </span>
                                @endif
                            </div>
                            <div class="mb-3 form-group {{ $errors->has('reg_email') ? ' has-error' : '' }}"><label class="form-label">Email address</label><input
                                    type="email" class="form-control {{ ($errors->has('reg_email'))?"input-error":"" }}" id="email" placeholder="Email" name="reg_email"
                                    value="{{ old('reg_email') }}">
                                 @if ($errors->has('reg_email'))
                                <span class="help-block">
                                    {{ $errors->first('reg_email') }}
                                </span>
                                @endif
                            </div>
                            <div class="mb-3 form-group {{ $errors->has('reg_password') ? ' has-error' : '' }}"><label class="form-label">Password</label><input name="reg_password" type="password" placeholder="password" value="" class="{{ ($errors->has('reg_password'))?"input-error":"" }}">
                                 @if ($errors->has('reg_password'))
                                    <span class="help-block">
                                        {{ $errors->first('reg_password') }}
                                    </span>
                                    @endif
                                </div>
                            <div class="mb-3 form-group {{ $errors->has('reg_password_confirmation') ? ' has-error' : '' }}"><label class="form-label">Confirm Password</label><input name="reg_password_confirmation" type="password" placeholder="Confirm password" value="" class="{{ ($errors->has('reg_password_confirmation'))?"input-error":"" }}">
                             @if ($errors->has('reg_password_confirmation'))
                                <span class="help-block">
                                    {{ $errors->first('reg_password_confirmation') }}
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


