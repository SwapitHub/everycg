@extends($templatePath.'.shop_layout')

@section('main')

<div class="col-md-10 profile-col profile-inner width_int">
            <div class="user_profile_edt">
                 @if(session()->has('message'))
                        <div class="alert alert-success" style="margin-top:15px">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                <h2 class="profile-title">User Address</h2>
                <div class="chekout-inner Checkout_Form user_profile">
                    <form class="checkout_form_data" method="POST" action="{{ route('member.post_change_address') }}">                       
                      
                       
                       <div class="form-group {{ $errors->has('address1') ? ' has-error' : '' }}">
                            <label class="form-label">Address1</label>
                            <input type="text" name="address1" value="{{ (old('address1'))?old('address1'):$dataUser['address1']}}">
                             @if($errors->has('address1'))
                                    <span class="help-block">{{ $errors->first('address1') }}</span>
                                @endif
                        </div>   
                        <div class="form-group {{ $errors->has('address2') ? ' has-error' : '' }}">
                            <label class="form-label">Address2</label>
                            <input type="text" name="address2" value="{{ (old('address2'))?old('address2'):$dataUser['address2']}}">
                             @if($errors->has('address2'))
                                    <span class="help-block">{{ $errors->first('address2') }}</span>
                                @endif
                        </div>                                         
                    @php
                        $country = (old('country'))?old('country'):$dataUser['country'];
                    @endphp
                         <div class="form-group {{ $errors->has('country') ? ' has-error' : '' }}">
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
                        </div> 
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

                    

                </div>

            </div>
        </div>
@endsection


