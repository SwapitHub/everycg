@extends($templatePath.'.shop_layout')

@section('main')

          
        <div class="col-md-10 profile-col profile-inner width_int">
            <div class="card">
                 @if(session()->has('message'))
                    <div class="alert alert-success" style="margin-top:15px">
                        {{ session()->get('message') }}
                    </div>
                @endif
                <div class="card-body update-password">
                    <form method="POST" action="{{ route('member.post_change_password') }}">
                        @csrf

                        <div class="form-group row {{ Session::has('password_old_error') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ trans('account.password_old') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password_old" required>

                                @if(Session::has('password_old_error'))
                                    <span class="help-block">{{ Session::get('password_old_error') }}</span>
                                @endif

                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ trans('account.password_new') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if($errors->has('password'))
                                    <span class="help-block">{{ $errors->first('password') }}</span>
                                @endif

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ trans('account.password_confirm') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ trans('account.update_infomation') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
 
@endsection

@section('breadcrumb')
    <div class="breadcrumbs">
        <ol class="breadcrumb">
          <li><a href="{{ route('home') }}">{{ trans('front.home') }}</a></li>
          <li class="active">{{ $title }}</li>
        </ol>
      </div>
@endsection
