@extends('admin.layout')

@section('main')
<div class="row">
    <div class="col-md-12">
        <div class="box-account">
      
        <div class="container-fluid">
            <!-- /.box-header -->
                <div class="account-setting">
                    <h1>Account settings</h1>
                    <p>Update your profile info, change password or change email notification settings.</p>
                </div>
            
        </div>

            <div class="agreement-form account-form">
                <form action="{{ $url_action }}" method="post" accept-charset="UTF-8" class="container-fluid form-account-setting" id="form-main"  enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group verify-email col-md-12  {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="text-left control-label">Email</label>
                            <?php if($user->emailverfy===1){ ?>
                             <div class="succes-btnn btn btn-success"><i class="fa fa-solid fa-check"></i>&nbsp;Verified</div>
                            <?php } else { ?>
                                 <div class="succes-btnn btn btn-danger"><i class="fa fa-solid fa-times"></i>&nbsp;Not verified</div>
                            <?php } ?>
                                <div class="row email-input">
                                <div class="input-groupn col-md-9 pr-0">
                                    
                                    <input type="email" id="email" name="email"
                                        value="{!! old('email',($user['email']??'')) !!}"
                                        class="form-control email w-100" placeholder="" />

                                </div>
                                <div class="col-md-3 pl-0">
                                    <div class="btn btn-warning w-100 verifymail">Verify your email address</div>
                                </div>
                            </div>

                                @if ($errors->has('email'))
                                <span class="help-block">
                                    {{ $errors->first('email') }}
                                </span>
                                @endif
                            

                        </div>
                        <div class="form-group  col-md-3  {{ $errors->has('birthday') ? ' has-error' : '' }}">
                            <label for="birthday"
                                class="text-left control-label">Birthday </label>
                      
                                <div class="input-groupn">
                                    <input type="date" id="birthday" name="birthday"
                                        value="{!! old('birthday',($user['birthday']??'')) !!}"
                                        class="form-control birthday" placeholder="" />
                                </div>
                                @if ($errors->has('birthday'))
                                <span class="help-block">
                                    {{ $errors->first('birthday') }}
                                </span>
                                @endif
                           
                        </div>

                          <div class="form-group col-md-12 {{ $errors->has('avatar') ? ' has-error' : '' }}">
                            <label for="avatar"
                                class=" control-label">Your Avatar </label>
                           
                                <div class="row flex-end">
                                    <div class="col-md-2">
                                        <div class="avatar"><img src="<?php if($user->avatar) echo asset($user->avatar); else echo asset('/data/page/account.png'); ?>" class="proimg"></div>
                                </div>
                                <div class="col-md-10">
                                    <p class="note-avatar"><b>Note:</b>the avatar should not exceed 100X100 dimensions (in pixels) and it should be smaller than 1 MB in file size. Acceptables formats: jpg, jpeg</p>
                                    <div class="input-groupn file-upload-yellow">
                                        <input type="file" id="avatar" name="avatar">
                                    </div>
                                    </div>
                            </div>

                                @if ($errors->has('avatar'))
                                <span class="help-block">
                                    {{ $errors->first('avatar') }}
                                </span>
                                @endif
                       
                        </div>

                        <div class="col-md-12 form-inner-heading">
                            <h2>Personal Information</h2>

                    </div>

                    <div class="form-group  col-md-12  {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="text-left control-label">Full Name</label>
                            
                                <div class="input-groupn">
                                    
                                    <input type="text" id="name" name="name"
                                        value="{!! old('name',($user['name']??'')) !!}"
                                        class="form-control name w-100" placeholder="" />

                                </div>
                                @if ($errors->has('name'))
                                <span class="help-block">
                                    {{ $errors->first('name') }}
                                </span>
                                @endif
                            

                        </div>

                        <div class="form-group  col-md-12  {{ $errors->has('location') ? ' has-error' : '' }}">
                            <label for="location" class="text-left control-label">Location</label>
                            
                                <div class="input-groupn">
                                    
                                    <input type="text" id="location" name="location"
                                        value="{!! old('location',($user['location']??'')) !!}"
                                        class="form-control location w-100" placeholder="" />

                                </div>
                                @if ($errors->has('location'))
                                <span class="help-block">
                                    {{ $errors->first('location') }}
                                </span>
                                @endif
                            

                        </div>


                        <div class="form-group  col-md-12  {{ $errors->has('language') ? ' has-error' : '' }}">
                            <label for="language" class="text-left control-label">Languages</label>
                            
                                <div class="input-groupn">
                                    
                                    <!-- <input type="text" id="language" name="language"
                                        value="{!! old('language',($user['language']??'')) !!}"
                                        class="form-control language w-100" placeholder="" /> -->
                                        <?php if(!empty($user['language'])){ $exlang  = explode(',',$user['language']); } ?>
                                         <select class="form-control input-sm language select2 w-100" multiple="multiple" name="language[]">
                                           <option value=""></option>
                                           <option value="en" <?php if(!empty($user['language'])){ if (in_array("en", $exlang)) echo 'selected'; }?>>English</option>
                                           <option value="fr" <?php if(!empty($user['language'])){ if (in_array("fr", $exlang)) echo 'selected'; }?>>French</option>
                                           <option value="es" <?php if(!empty($user['language'])){ if (in_array("es", $exlang)) echo 'selected'; }?>>Spanish</option>
                                           
                                        </select>

                                </div>
                                @if ($errors->has('language'))
                                <span class="help-block">
                                    {{ $errors->first('language') }}
                                </span>
                                @endif
                            

                        </div>


                        
                        <div class="form-group  col-md-12  {{ $errors->has('website') ? ' has-error' : '' }}">
                            <label for="website" class="text-left control-label">Website</label>
                            
                                <div class="input-groupn border-b-none">
                                    
                                    <input type="text" id="website" name="website"
                                        value="{!! old('website',($user['website']??'')) !!}"
                                        class="form-control website w-100" placeholder="https://" />

                                </div>
                                @if ($errors->has('website'))
                                <span class="help-block">
                                    {{ $errors->first('website') }}
                                </span>
                                @endif
                            

                        </div>

                         <div class="box-footer col-md-12">
                            @csrf
                       

                        <div class="inner-inout-btn">
                            <div class="btn-group pull-right">
                                <button type="submit" class="btn btn-warning">Update</button>
                            </div>

                           
                        </div>
                    </div>
             
                     </div>
                </form>
            </div>
           

        </div>
    </div>
</div>
@endsection  
@push('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('admin/AdminLTE/bower_components/select2/dist/css/select2.min.css')}}">

@endpush

@push('scripts')
<!-- Select2 -->
<script src="{{ asset('admin/AdminLTE/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{ asset('admin/plugin/jquery.pjax.js')}}"></script>

<script>
    $(document).ready(function(){
    // does current browser support PJAX
      if ($.support.pjax) {
        $.pjax.defaults.timeout = 2000; // time in milliseconds
      }
    });
      $('.select2').select2()
     function readURL(input) {
     if (input.files && input.files[0]) {
       var reader = new FileReader();

       reader.onload = function(e) {
         $('.proimg').attr('src', e.target.result);
       }

       reader.readAsDataURL(input.files[0]);
     }
   }

    $("#avatar").change(function() {
      var val = $(this).val();
      if(val){  
       var fileExtension = ['jpeg', 'jpg'];
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) { 
            alert('Select jpg or jpeg image file!');
        }
        else { $('.proimg').show(); readURL(this); }  
    }
    });

    $('.verifymail').click(function(){ 
        var email = $('#email').val();
     $.ajax({
        type: 'POST',
        url: '{{ route('admin_accsett.mail') }}',
        data: {
          "_token": "{{ csrf_token() }}",
          "email" : email,
        },
        success: function (response) {   

          console.log(response);
          if(response ==1){             
            Swal.fire(
                'Success!',
                'Please check your email for verification!',
                'success'
                )
          }else{
            Swal.fire(
              'error',
              'Something went wrong. Please try again letter!',
              'error'
              )
          }
          
        }
      });
 });
</script>
@endpush


