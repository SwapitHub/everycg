@extends('admin.layout')

@section('main')
 

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title">{{ $title_description??'' }}</h2>

                <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a href="{{ route('admin_customeremail.index') }}" class="btn  btn-flat btn-default" title="List"><i
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
					
					<div class="form-group   {{ $errors->has('language') ? ' has-error' : '' }}">
                            <label for="emails"
                                class="col-sm-2  control-label">User Emails <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
                            <div class="col-sm-8">
                                  
                                    <select class="form-control input-sm language" multiple="multiple" name="emails[]" id="useremails" style="width: 100%;">                                          
                                       <?php foreach ($users as $key => $value) { ?>
                                        <option value="{{ $value->email }}">{{ $value->email }}</option>
                                    <?php } ?>

                                   </select>

                              
                                @if ($errors->has('emails'))
                                <span class="help-block">
                                    {{ $errors->first('emails') }}
                                </span>
                                @endif
                            </div>
                            

                        </div>


                        <div class="form-group   {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name"
                                class="col-sm-2  control-label">Name<span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                    <input type="text" id="name" name="name"
                                        value="{!! old('name',($template['name']??'')) !!}"
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
                                class="col-sm-2  control-label">Content <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    
                                    <textarea id="description"
                                        name="description"  class="editor" >
                                        {!! old('name',($template['description']??'')) !!}
                                    </textarea>
                                    
                                </div>
                                 @if ($errors->has('description'))
                                <span class="help-block">
                                    {{ $errors->first('description') }}
                                </span>
                                @endif
                            </div>
                        </div>

                        
                         <div class="form-group">   
                          <label for="emails"
                                class="col-sm-2  control-label">Custom Recipients <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label> 
                           <div class="col-sm-8">
                          <div class="input-group input-group-md">
                            <input type="text" id="example_emailBS" name="custom_emails" class="form-control">
                            <span class="input-group-btn">
                              <a class="btn red" type="button"><i class="fa fa-calendar-plus-o"></i></a>
                             </span>
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
                        

                      <div class="btn-group pull-right">
                            <button type="submit" class="btn btn-primary">Send mail</button>
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

    $('form').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
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

    (function( $ ){
 
    $.fn.multiple_emails = function(options) {
        
        // Default options
        var defaults = {
            checkDupEmail: true,
            theme: "Bootstrap",
            position: "top"
        };
        
        // Merge send options with defaults
        var settings = $.extend( {}, defaults, options );
        
        var deleteIconHTML = "";
        if (settings.theme.toLowerCase() == "Bootstrap".toLowerCase())
        {
            deleteIconHTML = '<a href="#" class="multiple_emails-close" title="Remove"><span class="glyphicon glyphicon-remove"></span></a>';
        }
        else if (settings.theme.toLowerCase() == "SemanticUI".toLowerCase() || settings.theme.toLowerCase() == "Semantic-UI".toLowerCase() || settings.theme.toLowerCase() == "Semantic UI".toLowerCase()) {
            deleteIconHTML = '<a href="#" class="multiple_emails-close" title="Remove"><i class="remove icon"></i></a>';
        }
        else if (settings.theme.toLowerCase() == "Basic".toLowerCase()) {
            //Default which you should use if you don't use Bootstrap, SemanticUI, or other CSS frameworks
            deleteIconHTML = '<a href="#" class="multiple_emails-close" title="Remove"><i class="basicdeleteicon">Remove</i></a>';
        }
        
        return this.each(function() {
            //$orig refers to the input HTML node
            var $orig = $(this);
            var $list = $('<ul class="multiple_emails-ul" />'); // create html elements - list of email addresses as unordered list

            if ($(this).val() != '' && IsJsonString($(this).val())) {
                $.each(jQuery.parseJSON($(this).val()), function( index, val ) {
                    $list.append($('<li class="multiple_emails-email"><span class="email_name" data-email="' + val.toLowerCase() + '">' + val + '</span></li>')
                      .prepend($(deleteIconHTML)
                           .click(function(e) { $(this).parent().remove(); refresh_emails(); e.preventDefault(); })
                      )
                    );
                });
            }
            
            var $input = $('<input type="text" class="multiple_emails-input text-left" />').on('keyup', function(e) { // input
                $(this).removeClass('multiple_emails-error');
                var input_length = $(this).val().length;
                
                var keynum;
                if(window.event){ // IE                 
                    keynum = e.keyCode;
                }
                else if(e.which){ // Netscape/Firefox/Opera                 
                    keynum = e.which;
                }
                
                //if(event.which == 8 && input_length == 0) { $list.find('li').last().remove(); } //Removes last item on backspace with no input
                
                // Supported key press is tab, enter, space or comma, there is no support for semi-colon since the keyCode differs in various browsers
                if(keynum == 9 || keynum == 32 || keynum == 188) { 
                    display_email($(this), settings.checkDupEmail);
                }
                else if (keynum == 13) {
                    display_email($(this), settings.checkDupEmail);
                    //Prevents enter key default
                    //This is to prevent the form from submitting with  the submit button
                    //when you press enter in the email textbox
                    e.preventDefault();
                }

            }).on('blur', function(event){ 
                if ($(this).val() != '') { display_email($(this), settings.checkDupEmail); }
            });

            var $container = $('<div class="multiple_emails-container" />').click(function() { $input.focus(); } ); // container div
 
            // insert elements into DOM
            if (settings.position.toLowerCase() === "top")
                $container.append($list).append($input).insertAfter($(this));
            else
                $container.append($input).append($list).insertBefore($(this));

            /*
            t is the text input device.
            Value of the input could be a long line of copy-pasted emails, not just a single email.
            As such, the string is tokenized, with each token validated individually.
            
            If the dupEmailCheck variable is set to true, scans for duplicate emails, and invalidates input if found.
            Otherwise allows emails to have duplicated values if false.
            */
            function display_email(t, dupEmailCheck) {
                
                //Remove space, comma and semi-colon from beginning and end of string
                //Does not remove inside the string as the email will need to be tokenized using space, comma and semi-colon
                var arr = t.val().trim().replace(/^,|,$/g , '').replace(/^;|;$/g , '');
                //Remove the double quote
                arr = arr.replace(/"/g,"");
                //Split the string into an array, with the space, comma, and semi-colon as the separator
                arr = arr.split(/[\s,;]+/);
                
                var errorEmails = new Array(); //New array to contain the errors
                
                var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
                
                for (var i = 0; i < arr.length; i++) {
                    //Check if the email is already added, only if dupEmailCheck is set to true
                    if ( dupEmailCheck === true && $orig.val().indexOf(arr[i]) != -1 ) {
                        if (arr[i] && arr[i].length > 0) {
                            new function () {
                                var existingElement = $list.find('.email_name[data-email=' + arr[i].toLowerCase().replace('.', '\\.').replace('@', '\\@') + ']');
                                existingElement.css('font-weight', 'bold');
                                setTimeout(function() { existingElement.css('font-weight', ''); }, 1500);
                            }(); // Use a IIFE function to create a new scope so existingElement won't be overriden
                        }
                    }
                    else if (pattern.test(arr[i]) == true) {
                        $list.append($('<li class="multiple_emails-email"><span class="email_name" data-email="' + arr[i].toLowerCase() + '">' + arr[i] + '</span></li>')
                              .prepend($(deleteIconHTML)
                                   .click(function(e) { $(this).parent().remove(); refresh_emails(); e.preventDefault(); })
                              )
                        );
                    }
                    else
                        errorEmails.push(arr[i]);
                }
                // If erroneous emails found, or if duplicate email found
                if(errorEmails.length > 0)
                    t.val(errorEmails.join("; ")).addClass('multiple_emails-error');
                else
                    t.val("");
                refresh_emails ();
            }
            
            function refresh_emails () {
                var emails = new Array();
                var container = $orig.siblings('.multiple_emails-container');
                container.find('.multiple_emails-email span.email_name').each(function() { emails.push($(this).html()); });
                $orig.val(JSON.stringify(emails)).trigger('change');
            }
            
            function IsJsonString(str) {
                try { JSON.parse(str); }
                catch (e) { return false; }
                return true;
            }
            
            return $(this).hide();
 
        });
        
    };
    
})(jQuery);



    
        //Plug-in function for the bootstrap version of the multiple email
        $(function() {
            //To render the input device to multiple email input using BootStrap icon
            $('#example_emailBS').multiple_emails({position: "bottom"});
            //OR $('#example_emailBS').multiple_emails("Bootstrap");
            
            //Shows the value of the input device, which is in JSON format
        $('#current_emailsBS').text($('#example_emailBS').val());
//          $('#example_emailBS').change( function(){
//              $('#current_emailsBS').text($(this).val());
            
        });
</script>

   <script>
        $(document).ready(function () {
            $("#useremails").CreateMultiCheckBox({ width: '230px', defaultText : 'Select Emails', height:'250px' });
        });

        $(document).ready(function () {
            $(document).on("click", ".MultiCheckBox", function () {
               // var detail = $(this).next();
                $('.MultiCheckBoxDetail').toggleClass('open-select');
            });

            $(document).on("click", ".MultiCheckBoxDetailHeader input", function (e) {
                e.stopPropagation();
                var hc = $(this).prop("checked");
                $(this).closest(".MultiCheckBoxDetail").find(".MultiCheckBoxDetailBody input").prop("checked", hc);
                $(this).closest(".MultiCheckBoxDetail").next().UpdateSelect();
            });

            $(document).on("click", ".MultiCheckBoxDetailHeader", function (e) {
                var inp = $(this).find("input");
                var chk = inp.prop("checked");
                inp.prop("checked", !chk);
                $(this).closest(".MultiCheckBoxDetail").find(".MultiCheckBoxDetailBody input").prop("checked", !chk);
                $(this).closest(".MultiCheckBoxDetail").next().UpdateSelect();
            });

            $(document).on("click", ".MultiCheckBoxDetail .cont input", function (e) {
                e.stopPropagation();
                $(this).closest(".MultiCheckBoxDetail").next().UpdateSelect();

                var val = ($(".MultiCheckBoxDetailBody input:checked").length == $(".MultiCheckBoxDetailBody input").length)
                $(".MultiCheckBoxDetailHeader input").prop("checked", val);
            });

            $(document).on("click", ".MultiCheckBoxDetail .cont", function (e) {
                var inp = $(this).find("input");
                var chk = inp.prop("checked");
                inp.prop("checked", !chk);

                var multiCheckBoxDetail = $(this).closest(".MultiCheckBoxDetail");
                var multiCheckBoxDetailBody = $(this).closest(".MultiCheckBoxDetailBody");
                multiCheckBoxDetail.next().UpdateSelect();

                var val = ($(".MultiCheckBoxDetailBody input:checked").length == $(".MultiCheckBoxDetailBody input").length)
                $(".MultiCheckBoxDetailHeader input").prop("checked", val);
            });

            $(document).mouseup(function (e) {
                var container = $(".MultiCheckBoxDetail");
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    container.hide();
                    $(".MultiCheckBoxDetail").removeClass('open-select');
                }
            });
        });

        var defaultMultiCheckBoxOption = { width: '220px', defaultText: 'Select Below', height: '200px' };

        jQuery.fn.extend({
            CreateMultiCheckBox: function (options) {

                var localOption = {};
                localOption.width = (options != null && options.width != null && options.width != undefined) ? options.width : defaultMultiCheckBoxOption.width;
                localOption.defaultText = (options != null && options.defaultText != null && options.defaultText != undefined) ? options.defaultText : defaultMultiCheckBoxOption.defaultText;
                localOption.height = (options != null && options.height != null && options.height != undefined) ? options.height : defaultMultiCheckBoxOption.height;

                this.hide();
                this.attr("multiple", "multiple");
                var divSel = $("<div class='MultiCheckBox'>" + localOption.defaultText + "<span class='k-icon k-i-arrow-60-down'><svg aria-hidden='true' focusable='false' data-prefix='fas' data-icon='sort-down' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 320 512' class='svg-inline--fa fa-sort-down fa-w-10 fa-2x'><path fill='currentColor' d='M41 288h238c21.4 0 32.1 25.9 17 41L177 448c-9.4 9.4-24.6 9.4-33.9 0L24 329c-15.1-15.1-4.4-41 17-41z' class=''></path></svg></span></div>").insertBefore(this);
                divSel.css({ "width": localOption.width });

                var detail = $("<div class='MultiCheckBoxDetail'><div class='MultiCheckBoxDetailHeader'><input type='checkbox' class='mulinput' value='-1982' /><div>Select All</div></div><div class='MultiCheckBoxDetailBody'></div></div>").insertAfter(divSel);
                detail.css({ "width": parseInt(options.width) + 10, "max-height": localOption.height });
                var multiCheckBoxDetailBody = detail.find(".MultiCheckBoxDetailBody");

                this.find("option").each(function () {
                    var val = $(this).attr("value");

                    if (val == undefined)
                        val = '';

                    multiCheckBoxDetailBody.append("<div class='cont'><div><input type='checkbox' class='mulinput' value='" + val + "' /></div><div>" + $(this).text() + "</div></div>");
                });

                multiCheckBoxDetailBody.css("max-height", (parseInt($(".MultiCheckBoxDetail").css("max-height")) - 28) + "px");
            },
            UpdateSelect: function () {
                var arr = [];

                this.prev().find(".mulinput:checked").each(function () {
                    arr.push($(this).val());
                });

                this.val(arr);
            },
        });
    </script>

@endpush