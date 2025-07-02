@extends($templatePath.'.shop_layout')

@section('main')
 
<div class="col-md-10 cart-col cart-stripe-inner width_int">
   <div class="stripe-inner">
      <form class="stripe_from" action="{{ route('stripe.post') }}" method="post">
         {{ csrf_field() }}
         <h2>Payment Information</h2>
         <div class="mb-3 form-group Card_Number card">
            <label class="form-label ">Card Number</label>
            <input type="text" name="card" />
                <span class="card_icon"></span>
                <div class="status" style="display:none;">
                <span class="status_icon"></span>
                <span class="status_message"></span>
             </div>
            
         </div>
         <div class="month_Year">
            <div class="card_name"><label class="form-label">Month</label> / <label class="form-label">Year</label></div>
            <div class="mb-3 form-group group-error">
               <span class="_month">
                  <select class="month-select" name="month">
                     <option value="">MM</option>
                     <?php for($i=1; $i<=12; $i++) { 
                        $i = sprintf("%02d", $i);
                        echo '<option value="'.$i.'">'.$i.'</option>'; 
                     }
                     ?>
                  </select>                  
               </span>
               <span class="_yesr">
                  <select class="year-select" name="year">
                     <option value="">YYYY</option>
                      <?php for($i=date('Y'); $i<=date('Y')+10; $i++){ 
                        echo '<option value="'.$i.'">'.$i.'</option>'; 
                     }
                  ?>
                  </select>
               </span>
            </div>
         </div>
         <div class="mb-3  cvc_cvv">
            <label class="form-label">CVC</label>
            <input type="text" name="cvc" >
         </div>
         <div class="submit_cart">
            <button class="btn btn-primary w-100 paySubmit" type="submit" >Submit</button>
         </div>
      </form>
   </div>
</div> 
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>
<script src="{{ asset('/js/jquery.cardcheck.js') }}"></script>
<script>
   $(document).ready(function () {

    $('.stripe_from').validate({ // initialize the plugin
        rules: {
            card: {
                required: true
            },
            cvc: {
                required: true,
                minlength: 3
            },
            month: {
                required: true
            },
            year: {
                required: true
            },           
        },
          groups: {
               datefield: "month year"
           },
            errorPlacement: function(error, element) {
              if (element.attr("name") == "month" || element.attr("name") == "year" )
                error.insertAfter(".group-error");
              else
                error.insertAfter(element);
            },
              submitHandler: function(form) {
                 $.blockUI({ css: { 
                    border: 'none', 
                    padding: '15px', 
                    backgroundColor: '#000', 
                    '-webkit-border-radius': '10px', 
                    '-moz-border-radius': '10px', 
                    opacity: .5, 
                    color: '#fff' 
                } }); 
                
                form.submit();
              }

         });

});

    jQuery(function($) {
        
        // If JavaScript is enabled, hide fallback select field
        $('.no-js').removeClass('no-js').addClass('js');
        
        // When the user focuses on the credit card input field, hide the status
        $('.card input').bind('focus', function() {
            $('.card .status').hide();
        });
        
        // When the user tabs or clicks away from the credit card input field, show the status
        $('.card input').bind('blur', function() {
            $('.card .status').show();
        });
        
        // Run jQuery.cardcheck on the input
        $('.card input').cardcheck({
            callback: function(result) {
                
                var status = (result.validLen && result.validLuhn) ? 'valid' : 'invalid',
                    message = '',
                    types = '';
                
                // Get the names of all accepted card types to use in the status message.
                for (i in result.opts.types) {
                    types += result.opts.types[i].name + ", ";
                }
                types = types.substring(0, types.length-2);
                
                // Set status message
                 if (!result.cardClass) {
                    message = 'We accept the following types of cards: ' + types + '.';
                    $('.paySubmit').prop('disabled', true);
                } else if (!result.validLen) {
                    message = 'Please check that this number matches your ' + result.cardName + ' (it appears to be the wrong number of digits.)';
                    $('.paySubmit').prop('disabled', true);
                } else if (!result.validLuhn) {
                    message = 'Please check that this number matches your ' + result.cardName + ' (did you mistype a digit?)';
                    $('.paySubmit').prop('disabled', true);
                } else {
                    //message = 'Great, looks like a valid ' + result.cardName + '.';
                    $('.paySubmit').prop('disabled', false);
                }
                
                // Show credit card icon
                $('.card .card_icon').removeClass().addClass('card_icon ' + result.cardClass);
                
                // Show status message
                $('.card .status').removeClass('invalid valid').addClass(status).children('.status_message').text(message);
            }
        });
    });
  </script>
@endpush