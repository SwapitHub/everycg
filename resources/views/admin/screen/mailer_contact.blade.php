@extends('admin.layout')

@section('main')
   <div class="row">
      <div class="col-md-12">
         <div class="box">
                <div class="box-header with-border">
                    <h2 class="box-title">{{ $title_description??'' }}</h2>

                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 5px">
                            <a href="{{ route('admin_customer.index') }}" class="btn  btn-flat btn-default" title="List"><i class="fa fa-list"></i><span class="hidden-xs"> {{trans('admin.back_list')}}</span></a>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="{{ $url_action }}" method="post" accept-charset="UTF-8" class="form-horizontal" id="form-main"  enctype="multipart/form-data">


                    <div class="box-body">
                        <div class="fields-group">

                            <div class="form-group   {{ $errors->has('first_name') ? ' has-error' : '' }}">
                                <label for="first_name" class="col-sm-2  control-label">Contact Name</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text"   id="name" name="name" value="{{ old('name',$customer['name']??'')}}" class="form-control first_name" placeholder="" />
                                    </div>
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                {{ $errors->first('name') }}
                                            </span>
                                        @endif
                                </div>
                            </div>
							
							 <div class="form-group   {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-sm-2  control-label">Company Name</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text"   id="company" name="company" value="{{ old('company',$customer['Company']??'') }}" class="form-control company" placeholder="" />
                                    </div>
                                        @if ($errors->has('company'))
                                            <span class="help-block">
                                                {{ $errors->first('company') }}
                                            </span>
                                        @endif
                                </div>
                            </div>
							


                            <div class="form-group   {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-sm-2  control-label">{{ trans('customer.email') }}</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-envelope-open-o fa-fw"></i></span>
                                        <input type="text"   id="email" name="email" value="{{ old('email',$customer['email']??'') }}" class="form-control email" placeholder="" />
                                    </div>
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                {{ $errors->first('email') }}
                                            </span>
                                        @endif
                                </div>
                            </div>
							
							<div class="form-group   {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-sm-2  control-label">State</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text"   id="state" name="state" value="{{ old('state',$customer['state']??'') }}" class="form-control state" placeholder="" />
                                    </div>
                                        @if ($errors->has('state'))
                                            <span class="help-block">
                                                {{ $errors->first('state') }}
                                            </span>
                                        @endif
                                </div>
                            </div>

                            <div class="form-group  ">
                                <label for="status" class="col-sm-2  control-label">{{ trans('customer.status') }}</label>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="status"  {{ old('status',(empty($customer['status'])?0:1))?'checked':''}}>

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
