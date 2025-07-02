@extends($templatePath.'.shop_layout')

@section('main')

   <div class="col-md-10 register-col width_int">
            <div class="background-image-form"
                style="background: url('{{ asset('images/background-.jpg') }}');">
                <div class="form-common-main register-inner">
                     @if(session()->has('message'))
                    <div class="alert alert-success" style="margin-top:15px">
                        {{ session()->get('message') }}
                    </div>
                    @endif
                    <div class="register-logo"><img
                            src="{{ asset('images/logo-forms.png') }}">
                    </div>
                    <div class="form-common-main_data">
                        <h2>{{ $title }}</h2>
                        <form class="Register_from" action="{{ route('seller.postprofile')}}" method="post">
                             {!! csrf_field() !!}
                            <div class="mb-3 form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" placeholder="Name" value="{{ (old('name'))?old('name'):$user['name']}}" class="{{ ($errors->has('name'))?"input-error":"" }}">
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        {{ $errors->first('name') }}
                                    </span>
                                @endif
                            </div>
                          
                            <div class="mb-3 form-group {{ $errors->has('email') ? ' has-error' : '' }}"><label class="form-label">Email address</label><input
                                    type="email" class="form-control {{ ($errors->has('email'))?"input-error":"" }}" id="email" placeholder="Email" name="email"
                                    value="{{ (old('email'))?old('email'):$user['email']}}" readonly>
                                 @if ($errors->has('email'))
                                <span class="help-block">
                                    {{ $errors->first('email') }}
                                </span>
                                @endif
                            </div>
                            <div class="mb-3 form-group {{ $errors->has('password_old') ? ' has-error' : '' }}"><label class="form-label">Old Password</label><input name="password_old" type="password" placeholder="Old Password" value="" class="{{ ($errors->has('password_old'))?"input-error":"" }}">
                                 @if ($errors->has('password_old'))
                                    <span class="help-block">
                                        {{ $errors->first('password_old') }}
                                    </span>
                                    @endif
                                    @if(Session::has('password_old_error'))
                                    <span class="help-block">{{ Session::get('password_old_error') }}</span>
                                @endif
                                </div>
                            <div class="mb-3 form-group {{ $errors->has('password') ? ' has-error' : '' }}"><label class="form-label">New Password</label><input name="password" type="password" placeholder="New password" value="" class="{{ ($errors->has('password'))?"input-error":"" }}">
                             @if ($errors->has('password'))
                                <span class="help-block">
                                    {{ $errors->first('password') }}
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