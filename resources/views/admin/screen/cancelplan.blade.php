@extends('admin.layout')

@section('main')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title">{{ $title_description??'' }}</h2>

                <!-- <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a href="{{ route('admin_cancel_plan.index') }}" class="btn  btn-flat btn-default" title="List"><i
                                class="fa fa-list"></i><span class="hidden-xs"> {{trans('admin.back_list')}}</span></a>
                    </div>
                </div> -->
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{ $url_action }}" method="post" accept-charset="UTF-8" class="form-horizontal" id="form-main"
                enctype="multipart/form-data">


                <div class="box-body">
                    <div class="fields-group">                
                        <div class="form-group ">
                            <label for="plan_amount"
                                class="col-sm-2  control-label">Plan <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" 
                                        value="{{ $planname }}"
                                        class="form-control plan_amount" placeholder="" />
                                </div>
                               
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="plan_amount"
                                class="col-sm-2  control-label">price <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" id="plan_amount" name="plan_amount"
                                        value="{!! old('plan_amount',($plan['plan_amount']??'')) !!}"
                                        class="form-control plan_amount" placeholder="" />
                                </div>
                               
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="plan_amount"
                                class="col-sm-2  control-label">Interval <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" 
                                        value="{{ $plan->plan_interval }}"
                                        class="form-control plan_amount" placeholder="" />
                                </div>
                               
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="plan_amount"
                                class="col-sm-2  control-label">Email <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" 
                                        value="{{ $plan->payer_email }}"
                                        class="form-control plan_amount" placeholder="" />
                                </div>
                               
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="plan_amount"
                                class="col-sm-2  control-label">Plan Start <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" 
                                        value="{{ $plan->plan_period_start }}"
                                        class="form-control plan_amount" placeholder="" />
                                </div>
                               
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="plan_amount"
                                class="col-sm-2  control-label">Plan End <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" id="plan_amount" name="plan_amount"
                                        value="{{ $plan->plan_period_end }}"
                                        class="form-control plan_amount" placeholder="" />
                                </div>
                               
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="plan_amount"
                                class="col-sm-2  control-label">Status <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" 
                                        value="{{ $plan->status }}"
                                        class="form-control plan_amount" placeholder="" />
                                </div>
                               
                            </div>
                        </div>                   
                     <hr>

                         

                                            

                        

                       

                    </div>
                </div>



                <!-- /.box-body -->

                <div class="box-footer">
                    @csrf
                    <div class="col-md-2">
                    </div>

                    <div class="col-md-8">
                        <?php if($plan->status=='active') { ?>
                        <div class="btn-group pull-right">
                            <a href="{{ route('admin_cancel_plan.edit', ['id' => $plan['id']]) }}" onclick="return confirm('Are you sure?');" class="btn btn-danger">Cancel Subscription</a>
                        </div>
                        <?php } ?>
                        <div class="btn-group pull-left">
                            <button onclick="history.back()" class="btn btn-warning">Back</button>
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