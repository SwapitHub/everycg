@extends('admin.layout')

@section('main')
<div class="row">
    <div class="col-md-12">
        <div class="box333">
      
        <div class="container-fluid">
            <!-- /.box-header -->
            <div class="agreement-content">
                <h1>EveryCG Royalty Payment Agreement</h1>
                <p>In order to make financial transactions, we are required to have full information about the payment receivers. This allows us to comply with legal regulations and provide efficient and convenient service to our users.
                </p>
            </div>
            <div class="agreement-works">
                <h4>How it works?</h4>
                <ul>
                    <li>To be eligible for royalty payments, designers on CGTrader are required to submit their personal or business information on their payment agreement.</li>
                    <li>Payment Agreement review could take <b>up to 1 week</b> from the upload or update date. Please disregard the message in red and do not edit the agreement until it's verified.</li>
                    <li>Once you will get your Payment agreement verified, collected royalties will be paid <b>until the 20th day of the following month.</b></li>
                    <li>To receive payments via Wire transfer you need to have <b>at least $200</b> in royalties, Paypal and Payoneer methods do not have any limit.</li>
                    <li>If you are not sure what you need to fill in a specific field on the Payment agreement form, please check this <a href="">HelpCenter article</a> for more information.</li>
                </ul>
                <h4>What's next?</h4>
                <p>Please fill the agreement and your payment details below. <b>Important note:</b> The ID number must be exactly the same as written on the attached document.</p>
            </div>

        </div>

            <div class="agreement-form">
                <form action="{{ $url_action }}" method="post" accept-charset="UTF-8" class="container-fluid form-newww" id="form-main"  enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-md-4  {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name"
                                class="text-left control-label">Name <span class="seo" title="SEO">*</span></label>
                           
                                <div class="input-groupn">
                                    <input type="text" id="name" name="name"
                                        value="{!! old('name',($agreement['name']??'')) !!}"
                                        class="form-control name w-100" placeholder="" />
                                </div>

                                @if ($errors->has('name'))
                                <span class="help-block">
                                    {{ $errors->first('name') }}
                                </span>
                                @endif
                            

                        </div>
                        <div class="form-group  col-md-4  {{ $errors->has('surname') ? ' has-error' : '' }}">
                            <label for="surname"
                                class="text-left control-label">Surname <span class="seo" title="SEO">*</span></label>
                      
                                <div class="input-groupn">
                                    <input type="text" id="surname" name="surname"
                                        value="{!! old('surname',($agreement['surname']??'')) !!}"
                                        class="form-control surname" placeholder="" />
                                </div>
                                @if ($errors->has('surname'))
                                <span class="help-block">
                                    {{ $errors->first('surname') }}
                                </span>
                                @endif
                           
                        </div>
                        <div class="form-group   col-md-4   {{ $errors->has('dob') ? ' has-error' : '' }}">
                            <label for="dob"
                                class="text-left  control-label">Date of Birth <span class="seo" title="SEO">*</span></label>
                            
                                <div class="input-groupn">
                                    
                                    <input type="date" id="dob" name="dob"
                                        value="{!! old('dob',($agreement['dob']??'')) !!}"
                                        class="form-control dob" placeholder="" />
                                </div>
                                @if ($errors->has('dob'))
                                <span class="help-block">
                                    {{ $errors->first('dob') }}
                                </span>
                                @endif
                            
                        </div>
                        <div class="form-group col-md-6  {{ $errors->has('idnumber') ? ' has-error' : '' }}">
                            <label for="idnumber"
                                class=" control-label">Identification Number <span class="seo" title="SEO">*</span></label>
                            
                                <div class="input-groupn">
                                  
                                    <input type="text" id="idnumber" name="idnumber"
                                        value="{!! old('idnumber',($agreement['idnumber']??'')) !!}"
                                        class="form-control idnumber" placeholder="" />
                                </div>
                                @if ($errors->has('idnumber'))
                                <span class="help-block">
                                    {{ $errors->first('idnumber') }}
                                </span>
                                @endif
                           
                        </div>
                        <div class="form-group  col-md-6 {{ $errors->has('vat') ? ' has-error' : '' }}">
                            <label for="vat"
                                class="  control-label">VAT</label>
                           
                                <div class="input-groupn">
                    
                                    <input type="text" id="vat" name="vat"
                                        value="{!! old('vat',($agreement['vat']??'')) !!}"
                                        class="form-control vat" placeholder="" />
                                </div>
                                @if ($errors->has('vat'))
                                <span class="help-block">
                                    {{ $errors->first('vat') }}
                                </span>
                                @endif
                           
                        </div>
                        <div class="form-group   col-md-4  {{ $errors->has('country') ? ' has-error' : '' }}">
                                <label for="country" class=" control-label">{{ trans('customer.admin.select_country') }}<span class="seo" title="SEO">*</span></label>
                                <div class="input-groupn">
                                    <select class="form-control country select2" style="width: 100%;" name="country" >
                                        <option value=""></option>
                                        @foreach ($countries as $k => $v)
                                            <option value="{{ $k }}" {{ (old('country',$agreement['country']??'') ==$k) ? 'selected':'' }}>{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                        @if ($errors->has('country'))
                                            <span class="help-block">
                                                {{ $errors->first('country') }}
                                            </span>
                                        @endif
                               
                            </div>

                            <div class="form-group   col-md-4   {{ $errors->has('city') ? ' has-error' : '' }}">
                            <label for="city"
                                class=" control-label">City <span class="seo" title="SEO">*</span></label>
                            
                                <div class="input-groupn">
                                    
                                    <input type="text" id="city" name="city"
                                        value="{!! old('city',($agreement['city']??'')) !!}"
                                        class="form-control city" placeholder="" />
                                </div>
                                @if ($errors->has('city'))
                                <span class="help-block">
                                    {{ $errors->first('city') }}
                                </span>
                                @endif
                          
                        </div>

                        <div class="form-group  col-md-4   {{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address"
                                class=" control-label">Street Address/ZIP <span class="seo" title="SEO">*</span></label>
                         
                                <div class="input-groupn">
                                    
                                    <input type="text" id="address" name="address"
                                        value="{!! old('address',($agreement['address']??'')) !!}"
                                        class="form-control address" placeholder="" />
                                </div>
                                @if ($errors->has('address'))
                                <span class="help-block">
                                    {{ $errors->first('address') }}
                                </span>
                                @endif
                            
                        </div>
                         <div class="form-group col-md-12 {{ $errors->has('attachment') ? ' has-error' : '' }}">
                            <label for="attachment"
                                class=" control-label">Please attach a copy of uour ID, passport or social security in order to recieve payments <span class="seo" title="SEO">*</span></label>
                           
                                <div class="input-groupn file-upload">
                                   
                                    <input type="file" id="attachment" name="attachment">
                                </div>
                                @if ($errors->has('attachment'))
                                <span class="help-block">
                                    {{ $errors->first('attachment') }}
                                </span>
                                @endif
                       
                        </div>

                        <div class="form-group col-md-12 {{ $errors->has('paymethod') ? ' has-error' : '' }}">
                            <label for="paymethod"
                                class=" control-label">Payment Method</label>
                          
                                <div class="input-groupn las-child">
                                     
                                    <input type="radio" id="paymethod" name="paymethod" value="Payoneer" <?php if(!empty($agreement['paymethod'])){ if($agreement['paymethod']=='Payoneer') echo 'checked'; }?> > Payoneer &nbsp;
                                    <input type="radio" id="paymethod" name="paymethod" value="Paypal" <?php if(!empty($agreement['paymethod'])){ if($agreement['paymethod']=='Paypal') echo 'checked'; }?> >  Paypal &nbsp;
                                    <input type="radio" id="paymethod" name="paymethod" value="Wire Transfer" <?php if(!empty($agreement['paymethod'])){ if($agreement['paymethod']=='Wire Transfer') echo 'checked'; }?> > Wire Transfer &nbsp;
                                    <div class="inner-input">
                                    <label class=" control-label w-100 float-none d-block">Paypal email address <span class="seo" title="SEO">*</span></label>
                                    <input type="text" name="paypalemail" class="form-control paypalemail" value="{!! old('paypalemail',($agreement['paypalemail']??'')) !!}">
                                </div>
                                </div>
                                @if ($errors->has('paymethod'))
                                <span class="help-block">
                                    {{ $errors->first('paymethod') }}
                                </span>
                                @endif
                          
                        </div>
                        <?php if(empty($agreement)) { ?>
                         <div class="box-footer col-md-12">
                            @csrf
                       

                        <div class="inner-inout-btn">
                            <div class="btn-group pull-right">
                                <button type="submit" class="btn btn-danger">Confirm and Sign the Agreement</button>
                            </div>

                           
                        </div>
                    </div>
                <?php } ?>
                     </div>
                </form>
            </div>
           

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
 $("body").on('change','#attachment',function() {          
                
    $('.filenm').remove();
    var val = $(this).val();
    val = val.substring(val.lastIndexOf("\\") + 1, val.length);
    $(this).after('<p class="filenm">'+val+'</p>');

});
</script>
@endpush