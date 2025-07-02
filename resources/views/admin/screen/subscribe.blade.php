@extends('admin.layout')

@section('main')
   <div class="row">
      <div class="col-md-12">
         <div class="box">
                <div class="box-header with-border">
                    <h2 class="box-title">{{ $title_description??'' }}</h2>

                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 5px">
                            <a href="{{ route('admin_subscribe.index') }}" class="btn  btn-flat btn-default" title="List"><i class="fa fa-list"></i><span class="hidden-xs"> {{trans('admin.back_list')}}</span></a>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="{{ $url_action }}" method="post" accept-charset="UTF-8" class="form-horizontal" id="form-main"  enctype="multipart/form-data">


                    <div class="box-body">
                        <div class="fields-group">


                    <div class="box-body">
                        <div class="fields-group">
						
						 @if ($wantsTo != 'add')
							 <div class="form-group   {{ $errors->has('first_name') ? ' has-error' : '' }}">
                                <label for="first_name" class="col-sm-2  control-label">First Name</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="first_name" name="first_name" value="{!! old()?old('first_name'):$subscribe['first_name']??'' !!}" class="form-control" placeholder="" />
                                    </div>
                                        @if ($errors->has('first_name'))
                                            <span class="help-block">
                                                {{ $errors->first('first_name') }}
                                            </span>
                                        @endif
                                </div>
                            </div> 
							<div class="form-group   {{ $errors->has('first_name') ? ' has-error' : '' }}">
                                <label for="last_name" class="col-sm-2  control-label">Last Name</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="last_name" name="last_name" value="{!! old()?old('last_name'):$subscribe['last_name']??'' !!}" class="form-control" placeholder="" />
                                    </div>
                                        @if ($errors->has('last_name'))
                                            <span class="help-block">
                                                {{ $errors->first('last_name') }}
                                            </span>
                                        @endif
                                </div>
                            </div>
								<div class="form-group   {{ $errors->has('company') ? ' has-error' : '' }}">
                                <label for="last_name" class="col-sm-2  control-label">Company </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="company" name="company" value="{!! old()?old('company'):$subscribe['company']??'' !!}" class="form-control" placeholder="" />
                                    </div>
                                        @if ($errors->has('company'))
                                            <span class="help-block">
                                                {{ $errors->first('company') }}
                                            </span>
                                        @endif
                                </div>
                            </div>
							<div class="form-group   {{ $errors->has('website') ? ' has-error' : '' }}">
                                <label for="last_name" class="col-sm-2  control-label">Website </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="website" name="website" value="{!! old()?old('website'):$subscribe['website']??'' !!}" class="form-control" placeholder="" />
                                    </div>
                                        @if ($errors->has('website'))
                                            <span class="help-block">
                                                {{ $errors->first('website') }}
                                            </span>
                                        @endif
                                </div>
                            </div>
							<div class="form-group   {{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="last_name" class="col-sm-2  control-label">Title </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="title" name="title" value="{!! old()?old('title'):$subscribe['title']??'' !!}" class="form-control" placeholder="" />
                                    </div>
                                        @if ($errors->has('title'))
                                            <span class="help-block">
                                                {{ $errors->first('title') }}
                                            </span>
                                        @endif
                                </div>
                            </div>
							<div class="form-group   {{ $errors->has('seniority') ? ' has-error' : '' }}">
                                <label for="last_name" class="col-sm-2  control-label">Seniority </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="seniority" name="seniority" value="{!! old()?old('seniority'):$subscribe['seniority']??'' !!}" class="form-control" placeholder="" />
                                    </div>
                                        @if ($errors->has('seniority'))
                                            <span class="help-block">
                                                {{ $errors->first('seniority') }}
                                            </span>
                                        @endif
                                </div>
                            </div>
							<div class="form-group   {{ $errors->has('first_phone') ? ' has-error' : '' }}">
                                <label for="last_name" class="col-sm-2  control-label">Phone No </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="number" id="first_phone" name="first_phone" value="{!! old()?old('first_phone'):$subscribe['first_phone']??'' !!}" class="form-control" placeholder="" />
                                    </div>
                                        @if ($errors->has('first_phone'))
                                            <span class="help-block">
                                                {{ $errors->first('first_phone') }}
                                            </span>
                                        @endif
                                </div>
                            </div>
							<div class="form-group   {{ $errors->has('mobile_phone') ? ' has-error' : '' }}">
                                <label for="last_name" class="col-sm-2  control-label">Alt Phone No </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="number" id="mobile_phone" name="mobile_phone" value="{!! old()?old('mobile_phone'):$subscribe['mobile_phone']??'' !!}" class="form-control" placeholder="" />
                                    </div>
                                        @if ($errors->has('mobile_phone'))
                                            <span class="help-block">
                                                {{ $errors->first('mobile_phone') }}
                                            </span>
                                        @endif
                                </div>
                            </div>
							<div class="form-group   {{ $errors->has('linkedin_url') ? ' has-error' : '' }}">
                                <label for="last_name" class="col-sm-2  control-label">Linkedin url </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="url" id="linkedin_url" name="linkedin_url" value="{!! old()?old('linkedin_url'):$subscribe['linkedin_url']??'' !!}" class="form-control" placeholder="" />
                                    </div>
                                        @if ($errors->has('linkedin_url'))
                                            <span class="help-block">
                                                {{ $errors->first('linkedin_url') }}
                                            </span>
                                        @endif
                                </div>
                            </div>
							<div class="form-group   {{ $errors->has('city') ? ' has-error' : '' }}">
                                <label for="last_name" class="col-sm-2  control-label">City </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="city" name="city" value="{!! old()?old('city'):$subscribe['city']??'' !!}" class="form-control" placeholder="" />
                                    </div>
                                        @if ($errors->has('city'))
                                            <span class="help-block">
                                                {{ $errors->first('city') }}
                                            </span>
                                        @endif
                                </div>
                            </div>
							<div class="form-group   {{ $errors->has('state') ? ' has-error' : '' }}">
                                <label for="last_name" class="col-sm-2  control-label">State </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="state" name="state" value="{!! old()?old('state'):$subscribe['state']??'' !!}" class="form-control" placeholder="" />
                                    </div>
                                        @if ($errors->has('state'))
                                            <span class="help-block">
                                                {{ $errors->first('state') }}
                                            </span>
                                        @endif
                                </div>
                            </div>
							<div class="form-group   {{ $errors->has('country') ? ' has-error' : '' }}">
                                <label for="last_name" class="col-sm-2  control-label">Country </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text" id="country" name="country" value="{!! old()?old('country'):$subscribe['country']??'' !!}" class="form-control" placeholder="" />
                                    </div>
                                        @if ($errors->has('country'))
                                            <span class="help-block">
                                                {{ $errors->first('country') }}
                                            </span>
                                        @endif
                                </div>
                            </div>
						 @endif
            
                       

                            <div class="form-group   {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-sm-2  control-label">{{ trans('subscribe.email') }}</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="email" id="email" name="email" value="{!! old()?old('email'):$subscribe['email']??'' !!}" class="form-control" placeholder="" />
                                    </div>
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                {{ $errors->first('email') }}
                                            </span>
                                        @endif
                                </div>
                            </div>
							
							

                            <div class="form-group   {{ $errors->has('uploaded_file') ? ' has-error' : '' }}">
                                <label for="uploaded_file" class="col-sm-2  control-label">Upload Excel file</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="file" id="email" name="uploaded_file"  class="form-control" placeholder="" />
                                    </div> 
                                    @if ($errors->has('uploaded_file'))
                                            <span class="help-block">
                                                {{ $errors->first('uploaded_file') }}
                                            </span>
                                        @endif                                      
                                </div>
                            </div>



                            <div class="form-group  ">
                                <label for="status" class="col-sm-2  control-label">{{ trans('subscribe.status') }}</label>
                                <div class="col-sm-8">
                                <input type="checkbox" name="status"  {{ old('status',(empty($subscribe['status'])?0:1))?'checked':''}}>

                                </div>
                            </div>

                        </div>
                    </div>


                    <!-- /.box-body -->

                    <div class="box-footer">
                            @csrf
                        <div class="col-md-2">
                        </div>

                        <div class="col-md-8">
                            <div class="btn-group pull-right">
                                <button type="submit" class="btn btn-primary">{{ trans('admin.submit') }}</button>
                            </div>

                            <div class="btn-group pull-left">
                                <button type="reset" class="btn btn-warning">{{ trans('admin.reset') }}</button>
                            </div>
                        </div>
                    </div>

                    <!-- /.box-footer -->
                </form>
            </div>
        </div>
    </div>


@endsection

@push('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('admin/AdminLTE/bower_components/select2/dist/css/select2.min.css')}}">

{{-- switch --}}
<link rel="stylesheet" href="{{ asset('admin/plugin/bootstrap-switch.min.css')}}">

@endpush

@push('scripts')
<!-- Select2 -->
<script src="{{ asset('admin/AdminLTE/bower_components/select2/dist/js/select2.full.min.js')}}"></script>

{{-- switch --}}
<script src="{{ asset('admin/plugin/bootstrap-switch.min.js')}}"></script>

<script type="text/javascript">
    $("[name='top'],[name='status']").bootstrapSwitch();
</script>

<script type="text/javascript">

$(document).ready(function() {
    $('.select2').select2()
});

</script>

@endpush
