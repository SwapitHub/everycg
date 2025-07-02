@extends('admin.layout')

@section('main')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title">{{ $title_description??'' }}</h2>

                <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 5px">
                       
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="fields-group">
                    <div class="connected-account">
                        
                        <h4>Please create connected account or complete the pending details for connected account before creating the product! click <a href="{{ $url }}">here</a> to proceed further.</h4>
                        

                    </div>
                </div>
            </div>
          
           

        </div>
    </div>
</div>
@endsection

@push('styles')

<style>
    
.connected-account {
  padding: 40px;
  max-width: 600px;
  margin: 0 auto 40px;
  text-align: center;
  border: 1px solid red !important;
  border-radius: 20px;
}
.connected-account h4 {
  margin: 0;
}
</style>



@endpush