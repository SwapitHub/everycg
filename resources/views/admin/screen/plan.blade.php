@extends('admin.layout')

@section('main')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title">{{ $title_description??'' }}</h2>

                <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a href="{{ route('admin_plan.index') }}" class="btn  btn-flat btn-default" title="List"><i
                                class="fa fa-list"></i><span class="hidden-xs"> {{trans('admin.back_list')}}</span></a>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{ $url_action }}" method="post" accept-charset="UTF-8" class="form-horizontal" id="form-main"
                enctype="multipart/form-data">


                <div class="box-body">
                    <div class="fields-group">
                       

                      

                        <div class="form-group   {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name"
                                class="col-sm-2  control-label">name <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" id="name" name="name"
                                        value="{!! old('name',($plan['name']??'')) !!}"
                                        class="form-control name" placeholder="" />
                                </div>
                                @if ($errors->has('name'))
                                <span class="help-block">
                                    {{ $errors->first('name') }}
                                </span>
                                @endif
                            </div>
                        </div>

                         <div class="form-group   {{ $errors->has('price') ? ' has-error' : '' }}">
                            <label for="price"
                                class="col-sm-2  control-label">price <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" id="price" name="price"
                                        value="{!! old('price',($plan['price']??'')) !!}"
                                        class="form-control price" placeholder="" />
                                </div>
                                @if ($errors->has('price'))
                                <span class="help-block">
                                    {{ $errors->first('price') }}
                                </span>
                                @endif
                            </div>
                        </div>


                         <div class="form-group   {{ $errors->has('interval') ? ' has-error' : '' }}">
                            <label for="price"
                                class="col-sm-2  control-label">Interval <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <select class="form-control interval select2" style="width: 100%;" name="interval">
                                        <option value=""></option>
                                        <option value="month" {{ (old('interval',$plan['interval']??'') =='month') ? 'selected':'' }}>month</option>
                                        <option value="year" {{ (old('interval',$plan['interval']??'') =='year') ? 'selected':'' }}>year</option>

                                       
                                    </select>
                                </div>
                                @if ($errors->has('interval'))
                                <span class="help-block">
                                    {{ $errors->first('interval') }}
                                </span>
                                @endif
                            </div>
                        </div>

                         <div class="form-group   {{ $errors->has('model_limit') ? ' has-error' : '' }}">
                            <label for="model_limit"
                                class="col-sm-2  control-label">Model Download Limit <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" id="model_limit" name="model_limit"
                                        value="{!! old('model_limit',($plan['model_limit']??'')) !!}"
                                        class="form-control model_limit" placeholder="" />
                                </div>
                                @if ($errors->has('model_limit'))
                                <span class="help-block">
                                    {{ $errors->first('model_limit') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <hr>

                         

                                            

                        <div class="form-group  ">
                            <label for="status" class="col-sm-2  control-label">status</label>
                            <div class="col-sm-8">
                                <input type="checkbox" name="status"
                                    {{ old('status',(empty($plan['status'])?0:1))?'checked':''}}>

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
<script src="{{ asset('packages/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('packages/ckeditor/adapters/jquery.js') }}"></script>
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
<script type="text/javascript">
    $('textarea.editor').ckeditor(
    {
        filebrowserImageBrowseUrl: '{{ route('admin.home').'/'.config('lfm.url_prefix') }}?type=page',
        filebrowserImageUploadUrl: '{{ route('admin.home').'/'.config('lfm.url_prefix') }}/upload?type=page&_token={{csrf_token()}}',
        filebrowserBrowseUrl: '{{ route('admin.home').'/'.config('lfm.url_prefix') }}?type=Files',
        filebrowserUploadUrl: '{{ route('admin.home').'/'.config('lfm.url_prefix') }}/upload?type=file&_token={{csrf_token()}}',
        filebrowserWindowWidth: '900',
        filebrowserWindowHeight: '500'
    }
);k
</script>

@endpush