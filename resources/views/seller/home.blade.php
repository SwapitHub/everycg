@extends($templatePath.'.shop_layout')

@section('main')

        <div class="col-md-10 width_int">
         
            <div class="affiliate-dashboard">
            

      
                <figure class="ps-block--vendor-status card px-4">
                    <figcaption class="fs-10 text-center py-3 my-auto">Generate your affiliate link</figcaption>
                 <div class="card px-5 pt-3 mb-4">
                        <div class="pt-2">
                            <p>Affiliate Commission : <span class="font-weight-bold">{{ $Afffees }}%</span></p>
                            <p>Your affiliation Code is : <span class="font-weight-bold">{{ $user->affiliate_code }}</span></p>
                           
                            <h3>Affiliate URL Generator</h3>
                            <small>Enter Product URL from this website in the form below to generate a referral link!</small>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label>Enter Url</label>
                                <input type="text" class="form-control" id="url" value="{{ route('product.all') }}" readonly>
                                
                            </div>
                           
                            <button class="ps-btn py-3 ml-4 mb-3" id="genurl">Click To Generate URL</button>

                            <div class="form-group col-md-12 col-sm-12">
                                <label>Referral Url</label>
                                <input type="text" class="form-control" value="" id="link" placeholder="Copy the link">
                                <small class="text-primary">*Now copy this link and share it anywhere</small>
                            </div>
                        </div>
                    </div>
                                    </figure>
            
    </div>
  </div>
@endsection 
@push('scripts')
<script>
$(document).ready(function(){
    $('#genurl').click(function(){
        var refer = {{ $user->affiliate_code }}

        var url = $('#url').val();
        if(url)
        {
            var link = url+'?refer='+refer;
            $.ajax({
              url:'{{ route('affiliate.link') }}',
              type:'POST',
              data:{link:link,"_token": "{{ csrf_token() }}"},
              success: function(data){   
                $('#link').val(link);        
              }
            });
        }
       
    })
});

</script>
@endpush   
