@extends('admin.layout')

@section('main')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title">{{ $title_description??'' }}</h2>

                <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a href="{{ route('admin_widget.index') }}" class="btn  btn-flat btn-default" title="List"><i
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
                                class="col-sm-2  control-label">Name<span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" id="name" name="name"
                                        value="{!! old('name',($widget['name']??'')) !!}"
                                        class="form-control name" placeholder="" />
                                </div>
                                 @if ($errors->has('name'))
                                <span class="help-block">
                                    {{ $errors->first('name') }}
                                </span>
                                @endif
                            </div>
                        </div>                      

                        <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description"
                                class="col-sm-2  control-label">Description <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    
                                    <textarea id="description"
                                        name="description"  class="editor" >
                                        {!! old('name',($widget['description']??'')) !!}
                                    </textarea>
                                    
                                </div>
                                 @if ($errors->has('description'))
                                <span class="help-block">
                                    {{ $errors->first('description') }}
                                </span>
                                @endif
                            </div>
                        </div>
                     

                        <hr>               

                        <div class="form-group   {{ $errors->has('alias') ? ' has-error' : '' }}">
                            <label for="alias" class="col-sm-2  control-label">Url customize</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" id="alias" name="alias"
                                        value="{!! old('alias',($widget['alias']??'')) !!}" class="form-control"
                                        placeholder="" />
                                </div>
                                @if ($errors->has('alias'))
                                <span class="help-block">
                                    {{ $errors->first('alias') }}
                                </span>
                                @endif
                            </div>
                        </div>                        

                       

                        <div class="form-group   {{ $errors->has('sort') ? ' has-error' : '' }}">
                            <label for="sort" class="col-sm-2  control-label">Sort</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="number" style="width: 100px;" id="sort" name="sort"
                                        value="{!! old()?old('sort'):$widget['sort']??0 !!}" class="form-control sort"
                                        placeholder="" />
                                </div>
                                @if ($errors->has('sort'))
                                <span class="help-block">
                                    {{ $errors->first('sort') }}
                                </span>
                                @endif
                            </div>
                        </div>

                       

                        <div class="form-group  ">
                            <label for="status" class="col-sm-2  control-label">Status</label>
                            <div class="col-sm-8">
                                <input type="checkbox" name="status"
                                    {{ old('status',(empty($widget['status'])?0:1))?'checked':''}}>

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
);
</script>

@endpush