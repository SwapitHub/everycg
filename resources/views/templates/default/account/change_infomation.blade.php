@extends($templatePath.'.shop_layout')

@section('main')

<div class="col-md-10 profile-col profile-inner width_int">
            <div class="user_profile_edt">
                 @if(session()->has('message'))
                        <div class="alert alert-success" style="margin-top:15px">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                <h2 class="profile-title">User Profile</h2>
                <div class="chekout-inner Checkout_Form user_profile">
                    <form class="checkout_form_data" method="POST" action="{{ route('member.post_change_infomation') }}">
                       
                        <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" value="{{ (old('first_name'))?old('first_name'):$dataUser['first_name']}}">
                            @if($errors->has('first_name'))
                                    <span class="help-block">{{ $errors->first('first_name') }}</span>
                            @endif
                            </div>
                        <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" value="{{ (old('last_name'))?old('last_name'):$dataUser['last_name']}}">
                             @if($errors->has('last_name'))
                                    <span class="help-block">{{ $errors->first('last_name') }}</span>
                            @endif
                            </div>
                        <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" value="{{ (old('phone'))?old('phone'):$dataUser['phone']}}">
                             @if($errors->has('phone'))
                                <span class="help-block">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                      <!--   <div class="form-group {{ $errors->has('address1') ? ' has-error' : '' }}">
                            <label class="form-label">Address1</label>
                            <input type="text" name="address1" value="{{ (old('address1'))?old('address1'):$dataUser['address1']}}">
                             @if($errors->has('address1'))
                                    <span class="help-block">{{ $errors->first('address1') }}</span>
                                @endif
                        </div> -->                                               
                    @php
                        $country = (old('country'))?old('country'):$dataUser['country'];
                    @endphp
                      <!--   <div class="form-group {{ $errors->has('country') ? ' has-error' : '' }}">
                            <label class="form-label">Country</label>
                            <select class="country-select country" name="country"> 
                            <option>__{{ trans('account.country') }}__</option>
                                @foreach ($countries as $k => $v)
                                    <option value="{{ $k }}" {{ ($country ==$k) ? 'selected':'' }}>{{ $v }}</option>
                                @endforeach                              
                            </select>
                             @if ($errors->has('country'))
                                    <span class="help-block">
                                        {{ $errors->first('country') }}
                                    </span>
                                @endif
                        </div> -->
                        <div class="form-group {{ $errors->has('postcode') ? ' has-error' : '' }}">
                            <label class="form-label">Postcode</label>
                            <input type="text" name="postcode" value="{{ (old('postcode'))?old('postcode'):$dataUser['postcode']}}">
                            @if($errors->has('postcode'))
                                    <span class="help-block">{{ $errors->first('postcode') }}</span>
                                @endif
                        </div>
                        <div class="mb-3 checkout_form_dv">
                            <button class="btn btn-primary checkout_form_btn" type="submit">Update
                            </button>
                        </div>
                         @csrf
                    </form>

                     <form class="puslish-product" action="{{ route('admin.login') }}" method="post">
                      @csrf
                      <input type="hidden" name="username" value="{{ $dataUser->username }}">
                       <input type="hidden" name="password" value="{{ $dataUser->userpwd }}">
                        <button class="btn btn-primary" type="submit">PUBLISH YOUR WORK</button>
                      </form>

                </div>

            </div>
        </div>
@endsection


