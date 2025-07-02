@extends('admin.layout')

@section('main')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title">{{ $title_description??'' }}</h2>

                <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a href="{{ route('admin_contact_us.index') }}" class="btn  btn-flat btn-default" title="List"><i
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
                                        value="{!! old('name',($contact['name']??'')) !!}"
                                        class="form-control name" placeholder="" />
                                </div>
                                @if ($errors->has('name'))
                                <span class="help-block">
                                    {{ $errors->first('name') }}
                                </span>
                                @endif
                            </div>
                        </div>

                         <div class="form-group   {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email"
                                class="col-sm-2  control-label">email</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" id="email" name="email" value="{!! old('email',($contact['email']??'')) !!}"
                                        class="form-control email" placeholder="" />
                                </div>
                                @if ($errors->has('email'))
                                <span class="help-block">
                                    {{ $errors->first('email') }}
                                </span>
                                @endif
                            </div>
                        </div>


                          <div class="form-group   {{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title"
                                class="col-sm-2  control-label">title</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" id="title" name="title" value="{!! old('title',($contact['title']??'')) !!}"
                                        class="form-control title" placeholder="" />
                                </div>
                                @if ($errors->has('title'))
                                <span class="help-block">
                                    {{ $errors->first('title') }}
                                </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group   {{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone"
                                class="col-sm-2  control-label">phone</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" id="phone" name="phone" value="{!! old('phone',($contact['phone']??'')) !!}"
                                        class="form-control phone" placeholder="" />
                                </div>
                                @if ($errors->has('phone'))
                                <span class="help-block">
                                    {{ $errors->first('phone') }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group   {{ $errors->has('content') ? ' has-error' : '' }}">
                            <label for="content"
                                class="col-sm-2  control-label">content</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" id="content" name="content" value="{!! old('content',($contact['content']??'')) !!}"
                                        class="form-control content" placeholder="" />
                                </div>
                                @if ($errors->has('content'))
                                <span class="help-block">
                                    {{ $errors->first('content') }}
                                </span>
                                @endif
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
                            <a href="{{ route('admin_contact_us.index') }}" class="btn  btn-flat btn-default" title="List">Back</a>
                        </div>

                        <div class="btn-group pull-left">
                           
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