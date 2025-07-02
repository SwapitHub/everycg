@extends('admin.layout')

@section('main')
@php
$kindOpt = old('kind')
@endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">
<style>
    #start-add {
	margin: 20px;
    }
    .file-cont {
	width: 126px !important;
    }
    .gallery-images .img_holder {
    display: flex;
    flex-wrap: wrap;
    /*justify-content: space-between;*/
    column-gap: 4%;
	}
	
	.gallery-images .img_holder > *:not(.fa) {
    position: relative;
    width: 22%;
    cursor: pointer;
    margin: 0 0 15px;
	}
	
	.gallery-images .img_holder  .fa {
    position: absolute;
    color: #fff !important;
    right: 3px;
    top: 0px;
    font-size: 25px;
	}
	
	.gallery-images .img_holder  .fa:before {
    color: #fff !important;
    font-family: 'FontAwesome' !important;
    font-size: 13px;
    cursor: pointer;
	}
	
	
    .dropzone.dz-clickable .dz-preview {
    width: 100% !important;
	}
	
	.dropzone .dz-preview .dz-details {
    position: inherit !important;
    opacity: 1 !important;
    display: block;
    visibility: visible !important;
    padding: 0;
    border: none !important;
    min-width: 10px !important;
	}
	
	
	.dropzone .dz-preview {
	border: 1px solid #d8dce3;
	margin-inline: 0px !important;
	padding: 0px 0px 15px;
	width: 100% !important;
	display: flex;
	flex-direction: column;
	}
	.dropzone .dz-preview.dz-file-preview .dz-image {
	order: 1;
	}
	.dropzone .dz-preview .dz-details * {
	text-align: left !important;
	}
	
	.dropzone .dz-preview .dz-details * {
    opacity: 1 !important;
    display: block;
    visibility: visible !important;
    text-align: left !important;
    white-space: pre-wrap !important;
	}
	
	.dropzone .dz-preview .dz-details .dz-size {
    display: none;
	}
	
	.dropzone .dz-preview .dz-image img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover;
	}
	
	.dropzone.dz-clickable .dz-preview input[type="text"] {
	width: 100% !important;
	height: 30px;
	padding: 4px 40px 4px 4px;
	position: absolute;
	left: 150px;
	top: 87px;
	max-width: calc(100% - 205px);
	z-index: 8;
	border: 1px solid #edf2f7 !important;
	outline: none !important;
	}
	
	.dropzone.dz-clickable .dz-preview .dz-remove {
	position: absolute;
	right: 8px;
	top: 87px;
	background: transparent;
	color: #1f5a90 !important;
	height: 30px;
	padding: 1px 13px;
	font-size: 0px !important;
	z-index: 10;
	border: none;
	}
	
	
	.dropzone .dz-preview .dz-details .dz-filename , .dropzone .dz-preview .dz-details .dz-filename * {
	text-align: center !important;
	}
	
	
	.dropzone .dz-preview .dz-progress {
	left: 20px !important;
	transform: none;
	margin: 0px !important;
	top: 87px !important;
	}
	
	.dropzone.dz-clickable .dz-preview .dz-remove::before {
	
    content: '\00D7';
    font-size: 23px;
    color: #1f5a90;
    line-height: 1;
    font-weight: 900 !important;
	
	}
	
	.dropzone .dz-preview .dz-details .dz-filename {
    padding: 4px 4px 4px 5px;
    white-space: pre-wrap !important;
    overflow: visible !important;
    word-break: break-word;
	}
	
	.dropzone .dz-preview .dz-image img {
    display: none;
	}
	
	.dz-error .dz-error-mark {
	display: none !important;
	}
	
	
	.ext-type {
	position: absolute;
	left: 150px;
	top: 124px;
	max-width: calc(100% - 205px);
	z-index: 8;
	padding: 0px 10px;
	color: #c1c1c1;
	font-style: italic;
	}
	.dropzone .dz-preview.dz-file-preview .dz-image {
	background: transparent !important;
	}
	.dropzone.dz-clickable .dz-preview input[type="text"]::placeholder {
	
    color: #c1c1c1;
	font-style: italic;
	}
	.ext-type b {
	color: black !important;
	font-weight: bolder;
	}
	
	
	.dz-error .dz-image:before {
	background: url('/data/page/signal.png') !important;
	
	}
	
	.dropzone .dz-preview .dz-image:before {
    content: '';
    background: url('/data/page/Web Correction_v3-10.png');
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    background-position: center  !important;
    background-size: contain  !important;
    background-repeat: no-repeat  !important;
	}
	
	.dropzone .dz-preview .dz-image {
    position: relative;
	}
	
	
	.dropzone {
	border: none !important;
	
	}
	
	.left-drop {
	width: 48%;
	display: flex;
	flex-direction: column;
	}
	.dropzone-outer {
	display: flex;
	flex-wrap: wrap;
	column-gap: 4%;
	border: 1px solid #e6e6e6;
	padding: 36px 15px;
	position: relative;
	width: 100%;
	max-width: 100%;
	margin-bottom: 80px;
	}
	.right-drop {
	width: 48%;
	}
	.dropzone-outer > * {
	border: ;
	}
	.dropzone-outer:before {
	content: '';
	position: absolute;
	top: 50%;
	bottom: 0;
	width: 2px;
	background: #e6e6e6;
	left: 50%;
	height: calc(100% - 72px);
	transform: translate(-50% , -50%);
	}
	.left-drop .note {
	color: red;
	text-align: center;
	}
	.left-drop .dz-message {
	display: flex !important;
	flex-wrap: wrap;
	align-items: center;
	column-gap: 12px;
	flex-grow: 1;
	}
	
	
	.three-d .input-group {
	max-width: 100% !important;
	width: 100% !important;
	}
	
	.left-drop .dz-message span:first-child img {
	width: 72px;
	}
	
	.left-drop .dz-message * {
	font-size: 12px;
	}
	.left-drop .dz-message span:last-child img {
	width: 111px;
	}
	.right-drop h5 {
	margin: 0 0 13px;
	font-weight: bold;
	font-style: italic;
	font-size: 17px;
	}
	.right-drop  span {
	font-weight: 700;
	margin: 0 0 6px;
	display: block;
	}
	.right-drop  ol {
	padding: 0 0 0 17px;
	}
	#dropzone {
	width: 100%;
	max-width: 100%;
	padding-inline: 0 !important;
	}
	.right-drop ol > li {
	margin: 0 0 10px;
	font-size: 13px;
	}
	.dropzone .dz-preview .dz-details .dz-filename {
    background: #edf2f7;
    margin: 0 0 15px;
	}
	
	.dropzone .dz-preview .dz-details .dz-filename * {
    background: transparent !important;
    border: none !important;
    text-align: left !important;
    color: #4e719c !important;
    font-weight: 700;
    font-size: 14px;
	}
	
	.dropzone .dz-preview.dz-file-preview .dz-image {
    margin-left: 15px;
	}
	
	
	
	.fallback.dropzone:not(.dz-started) .control-label.hide-this-opt {
	display:none;
	}
	
	
	.fallback.dropzone {
	position: relative;
	}
	.control-label.hide-this-opt {
	position: absolute;
	right: calc(100% + 30px);
	width: max-content;
	margin: 0px 0 0;
	}
	
	.dropzone-outer:after {
	content: '';
	position: absolute;
	top: calc(100% + 40px);
	background: #edf2f7 !important;
	width: 100%;
	left: 0;
	right: 0;
	height: 2px;
	}
	
	.thumb-outer {
    display: flex;
    flex-wrap: wrap;
    column-gap: 4%;
    border: 1px solid #e6e6e6;
    padding: 10px 10px;
    position: relative;
    width: 100%;
    max-width: 100%;
    margin-bottom: 80px;
    align-items: center;
	}
	
	/*.input-group {
    width: 100% !important;
    max-width: 100% !important;
	}*/
	
	
	
	.thumb-left {
    width: 48%;
	}
	
	.thumb-right {
    width: 48%;
    padding: 20px 0px;
	}
	
	.thumb-outer:before {
    content: '';
    position: absolute;
    top: 50%;
    bottom: 0;
    width: 2px;
    background: #e6e6e6;
    left: 50%;
    height: calc(100% - 72px);
    transform: translate(-50% , -50%);
	}
	
	.thumb-left .input-group-btn {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    width: 100% !important;
    justify-content: space-between;
	}
	
	.thumb-left .input-group-btn .img_holder {
    width: 100% !important;
    margin: 0 !important;
    max-width: 50% !important;
	}
	
	.thumb-left .input-group-btn .img_holder img {
    width: 100% !important;
    max-width: 100% !important;
    height: 100% !important;
    max-height: 100% !important;
    object-fit: cover !important;
    object-position: center !important;
	
	}
	
	.thumb-left .input-group-btn a.btn {
    border: none !important;
    background: transparent !important;
    padding: 0px;
    width: 38%;
	}
	
	.thumb-left .input-group-btn a.btn img {
    width: 100% !important;
    max-width: 100% !important;
	}
	
	.thumb-right h5 {
    margin: 0 0 13px;
    font-weight: bold;
    font-style: italic;
    font-size: 17px;
	}
	
	.thumb-right ul {
    padding: 0 0 0 17px;
    list-style: none;
	}
	
	.thumb-right ul > li {
    margin: 0 0 10px;
    font-size: 13px;
    position: relative;
	}
	
	.thumb-right ul > li:before {
    content: '';
    background: black;
    position: absolute;
    height: 1px;
    width: 6px;
    right: calc(100% + 9px);
    top: 8px;
	}
	.gallery-images.hasimages .thumb-right , .gallery-images.hasimages .thumb-left .btn[data-input="sub_image"] , .gallery-images.hasimages .thumb-outer:before {
    display: none !important;
	}
	
	.gallery-images.hasimages .thumb-left .input-group-btn .img_holder , .gallery-images.hasimages .thumb-left  {
	
    width:100% !important;
    max-width:100% !important;
	}
    @if($kindOpt == '') 
    
	#main-add, #box-footer {
	display: none;
	}
	
    @else 
	.kind {
	display: none;
	}
	.kind{{ $kindOpt }}
	{
	display: block;
	}
    @endif 
	
    .select-product {
	margin: 10px 0;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title">{{ $title_description??'' }}</h2>
                <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a href="{{ route('admin_product.index') }}" class="btn  btn-flat btn-default" title="List"><i
						class="fa fa-list"></i><span class="hidden-xs"> {{trans('admin.back_list')}}</span></a>
					</div>
				</div>
			</div>
            <!-- /.box-header -->
			
			<!-- <form method="post" action="{{ route('admin_product_img.store') }}" enctype="multipart/form-data" class="dropzone" id="dropzone">
				@csrf
			</form>  -->
			
			
            <!-- form start -->
            <form action="{{ route('admin_product.create') }}" method="post" name="form_name" accept-charset="UTF-8"
			class="form-horizontal" id="form-main" enctype="multipart/form-data">
                @csrf
				
                <div class="col-xs-12" id="start-add">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 form-group  {{ $errors->has('kind') ? ' has-error' : '' }} ">
                        <div class="input-group input-group-sm" style="width: 300px;text-align: center;">
                            @if (sc_config('product_kind'))
                            <select class="form-control" style="width: 100%;" name="kind">
                                <option value="">{{ trans('product.admin.select_kind') }}</option>
                                @foreach ($kinds as $key => $kind)
                                <option value="{{ $key }}" {{ (old() && (int)old('kind') === $key)?'selected':'' }}>
								{{ $kind }}</option>
                                @endforeach
								</select>          
								<span class="input-group-addon" id="apply-add">
                                <i class="fa fa-hand-o-left"></i> {{ trans('product.kind') }}
								</span>                                                  
									@else
									<select class="form-control" style="display:none" name="kind">
										<option value="0" selected="selected">{{ $kinds[0] }}</option>
									</select>   
									@endif
									
									
								</div>
								@if ($errors->has('kind'))
								<span class="help-block">
									<i class="fa fa-info-circle"></i> {{ $errors->first('kind') }}
								</span>
								@endif
							</div>
						</div>
						
						
						<div class="box-body" id="main-add">
							<div class="fields-group">
								
								{{-- descriptions --}}
								@foreach ($languages as $code => $language)
								
								
								
								<div class="form-group three-d   {{ $errors->has('descriptions.'.$code.'.name') ? ' has-error' : '' }}">
									<label for="{{ $code }}__name"
									class="col-sm-2 asterisk control-label">Files <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
									<div class="col-sm-8">
										<!-- <span style="color:red;">*Please compress 3D files in ZIP or RAR before dragging it here*</span> -->
										<div class="input-group">
											<!--    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span> -->
											<div id="dropzone" class="fallback dropzone" enctype="multipart/form-data">
												<div class="dropzone-outer">
													<div class="left-drop">
														<p class="note">3D Files only in ZIP or RAR formats</p>
														<div class="dz-message" data-dz-message>
															<span>Drag and Drop <img src="{{ asset('/data/page/Web Correction_v3-04.png') }}"> </span> <span>OR</span>   <span><img src="{{ asset('/data/page/Web Correction_v3-05.png') }}"></span>
														</div>
													</div>
													<div class="right-drop">
														<h5>Tips:</h5>
														<span>3D Files</span>
														<ol>
															<li>Please compress all your 3D files such as (.max, .ma, .obj, .fbx, etc) in a ZIP or RAR format before uploading here.
															</li>
															<li>Please compress all your 3D files such as (.max, .ma, .obj, .fbx, etc) in a ZIP or RAR format before uploading here.
															</li>
															<li>For other JPG, PNG and other preview images, please use upload fields below.
															</li>
														</ol>
													</div>
												</div>
												<label for="Uploaded files"
												class="control-label hide-this-opt">Uploaded Files </label>
												
												
											</div>
										</div>
										@if ($errors->has('hiddenfile'))
										<span class="help-block">
											{{ $errors->first('hiddenfile') }}
										</span>
										@endif
										@if ($errors->has('content'))
										<span class="help-block">
											{{ $errors->first('content') }}
										</span>
										@endif
									</div>
								</div>
								
								{{-- images --}}
								<div class="form-group  kind kind0 kind1 {{ $errors->has('image') ? ' has-error' : '' }}">
									<label for="image"
									class="col-sm-2 asterisk control-label">Thumbnail image</label>
									<div class="col-sm-8">
										<div class="input-group">
											<div class="thumb-outer">
												<div class="thumb-left">
													<input type="hidden" id="image" name="image" value="{!! old('image') !!}"
													class="form-control input-sm image" placeholder="" />
													<span class="input-group-btn">
														<div id="preview_image" class="img_holder">
															
															<img src="{{ asset('/data/page/Web Correction_v3-07.png') }}">
															
														</div><a data-input="image" data-preview="preview_image" data-type="product"
														class="btn btn-sm btn-flat btn-primary lfm">
															<img src="{{ asset('/data/page/Web Correction_v3-05.png') }}">
														</a>
													</span>
												</div>
												<div class="thumb-right">
													<h5>Thumbnail Image</h5>
													<ul>
														<li>Your thumbnail image is your best selling image, so upload our best looking image here in JPG or PNG format</li>
														<li>This is the image that customers will see first when browsing</li>
														<li>Recommended size 1024 x 960 pixels</li>
														<li>Please limit your file size to 200kb</li>
													</ul>
												</div>
											</div>
										</div>
										@if ($errors->has('image'))
										<span class="help-block">
											<i class="fa fa-info-circle"></i> {{ $errors->first('image') }}
										</span>
										@endif
										
										
										
										
									</div>
									
								</div>
								{{-- //images --}}
								
								{{-- images --}}
								<div class="form-group gallery-images  kind kind0 kind1 {{ $errors->has('sub_image') ? ' has-error' : '' }}">
									<label for="sub_image"
									class="col-sm-2 control-label">Image gallery files</label>
									<div class="col-sm-8">
										<div class="input-group">
											<div class="thumb-outer">
												<div class="thumb-left">
													<input type="hidden" id="sub_image"   class="form-control input-sm sub_image" placeholder="" />
													<span class="input-group-btn">
														<div id="preview_image1" class="img_holder">
															
															<img src="{{ asset('/data/page/Web Correction_v3-09.png') }}">
															
														</div><a data-input="sub_image" data-preview="preview_image1" data-type="product"
														class="btn btn-sm btn-flat btn-primary lfm rmafter">
															<img src="{{ asset('/data/page/Web Correction_v3-05.png') }}">
														</a>
													</span>
												</div>
												<div class="thumb-right">
													<h5>Image Gallery</h5>
													<ul>
														<li>These are your preview images in JPG and PNG formats</li>
														<li>You can include as many images as you want here</li>
														<li>Include wireframe images for more insight</li>
														<li>You can select multiple images at the same time</li>
														<li>Please limit your file size to 200kb</li>
													</ul>
												</div>
											</div>
										</div>                            
										
									</div>
								</div>
								{{-- //images --}}
								
								<div
								class="form-group    {{ $errors->has('descriptions.'.$code.'.name') ? ' has-error' : '' }}">
									<label for="{{ $code }}__name"
									class="col-sm-2 asterisk control-label">Product Title <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
									<div class="col-sm-8">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
											<input type="text" id="{{ $code }}__name" name="descriptions[{{ $code }}][name]"
											value="{!! old('descriptions.'.$code.'.name') !!}"
											class="form-control input-sm {{ $code.'__name' }}" placeholder="" />
										</div>
										@if ($errors->has('descriptions.'.$code.'.name'))
										<span class="help-block">
											<i class="fa fa-info-circle"></i>
											{{ $errors->first('descriptions.'.$code.'.name') }}
										</span>
										@endif
									</div>
								</div>
								
								<div
								class="form-group    {{ $errors->has('descriptions.'.$code.'.keyword') ? ' has-error' : '' }}">
									<label for="{{ $code }}__keyword"
									class="col-sm-2  control-label">{{ trans('product.keyword') }} <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
									<div class="col-sm-8">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
											<input type="text" id="{{ $code }}__keyword"
											name="descriptions[{{ $code }}][keyword]"
											value="{!! old('descriptions.'.$code.'.keyword') !!}"
											class="form-control input-sm {{ $code.'__keyword' }}" placeholder="" />
										</div>
										@if ($errors->has('descriptions.'.$code.'.keyword'))
										<span class="help-block">
											<i class="fa fa-info-circle"></i>
											{{ $errors->first('descriptions.'.$code.'.keyword') }}
										</span>
										@endif
									</div>
								</div>
								
								<div
								class="form-group  {{ $errors->has('descriptions.'.$code.'.description') ? ' has-error' : '' }}">
									<label for="{{ $code }}__description"
									class="col-sm-2  control-label">{{ trans('product.description') }} <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
									<div class="col-sm-8">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
											<input type="text" id="{{ $code }}__description"
											name="descriptions[{{ $code }}][description]"
											value="{!! old('descriptions.'.$code.'.description') !!}"
											class="form-control input-sm {{ $code.'__description' }}" placeholder="" />
										</div>
										@if ($errors->has('descriptions.'.$code.'.description'))
										<span class="help-block">
											<i class="fa fa-info-circle"></i>
											{{ $errors->first('descriptions.'.$code.'.description') }}
										</span>
										@endif
									</div>
								</div>
								
								<div
								class="form-group   kind kind0  {{ $errors->has('descriptions.'.$code.'.content') ? ' has-error' : '' }}">
									<label for="{{ $code }}__content"
									class="col-sm-2 asterisk control-label">{{ trans('product.content') }}</label>
									<div class="col-sm-8">
										<textarea id="{{ $code }}__content" class="editor"
										name="descriptions[{{ $code }}][content]">
											{!! old('descriptions.'.$code.'.content') !!}
										</textarea>
										@if ($errors->has('descriptions.'.$code.'.content'))
										<span class="help-block">
											<i class="fa fa-info-circle"></i>
											{{ $errors->first('descriptions.'.$code.'.content') }}
										</span>
										@endif
									</div>
								</div>
								
								@endforeach
								{{-- //descriptions --}}
								
								
								{{-- select category --}}
								<div class="form-group  kind kind0 kind1 {{ $errors->has('category') ? ' has-error' : '' }}">
									<hr>
									
									<label for="category"
									class="col-sm-2 asterisk control-label">{{ trans('product.admin.select_category') }}</label>
									<div class="col-sm-4">
										<select class="form-control input-sm category select2"  data-placeholder="Select category" style="width: 100%;" name="category[]" >
											<option value="">Select category</option>
											@foreach ($categories as $v)
											<option value="{{ $v->id }}"
											>{{ $v->name }}
											</option>
											@endforeach
										</select>
										@if ($errors->has('category'))
										<span class="help-block">
											<i class="fa fa-info-circle"></i> {{ $errors->first('category') }}
										</span>
										@endif
									</div>
									<div class="col-sm-4">
										<select class="form-control input-sm subcategory select2"  data-placeholder="Select subcategory" style="width: 100%;"
										name="subcategory[]">
											<option value=""></option>
											
										</select>
										@if ($errors->has('subcategory'))
										<span class="help-block">
											<i class="fa fa-info-circle"></i> {{ $errors->first('subcategory') }}
										</span>
										@endif
									</div>
								</div>
								{{-- //select category --}}
								
								<div class="form-group  kind kind0 kind1 {{ $errors->has('tags') ? ' has-error' : '' }}">
									
									<label for="tags"
									class="col-sm-2 asterisk control-label">Tags</label>
									<div class="col-sm-8">
										<input type="text"  id="tags" name="tags" value="{!! old('tags')??'' !!}" class="form-control input-sm tags"
                                        placeholder="Tags" />
										<!-- <select class="form-control input-sm tags select2" multiple="multiple"
											data-placeholder="Select Tags" style="width: 100%;"
											name="tags[]">
											<option value=""></option>
											@foreach ($tags as $tag)
											<option value="{{ $tag->id }}"
											>{{ $tag->name }}
											</option>
											@endforeach
										</select> -->
										@if ($errors->has('tags'))
										<span class="help-block">
											<i class="fa fa-info-circle"></i> {{ $errors->first('tags') }}
										</span>
										@endif
									</div>
								</div>
								
								
								
								{{-- sku --}}
								<div class="form-group  kind kind0 kind1 kind2 {{ $errors->has('sku') ? ' has-error' : '' }}">
									<label for="sku" class="col-sm-2 asterisk control-label">{{ trans('product.sku') }}</label>
									<div class="col-sm-8">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
											<input type="text" style="width: 100px;" id="sku" name="sku"
											value="{!! old('sku')??'' !!}" class="form-control input-sm sku"
											placeholder="" />
										</div>
										@if ($errors->has('sku'))
										<span class="help-block">
											<i class="fa fa-info-circle"></i> {{ $errors->first('sku') }}
										</span>
										@else
										<span class="help-block">
											{{ trans('product.sku_validate') }}
										</span>
										@endif
									</div>
								</div>
								{{-- //sku --}}
								
								
								{{-- alias --}}
								<!---div class="form-group  kind kind0 kind1 kind2 {{ $errors->has('alias') ? ' has-error' : '' }}">
									<label for="alias" class="col-sm-2 asterisk control-label">{!! trans('product.alias') !!}</label>
									<div class="col-sm-8">
									<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
									<input type="text"  id="alias" name="alias"
									value="{!! old('alias')??'' !!}" class="form-control input-sm alias"
									placeholder="" />
									</div>
									@if ($errors->has('alias'))
									<span class="help-block">
									<i class="fa fa-info-circle"></i> {{ $errors->first('alias') }}
									</span>
									@else
									<span class="help-block">
									{{ trans('product.alias_validate') }}
									</span>
									@endif
									</div>
								</div--->
								{{-- //alias --}}
								
								@if (sc_config('product_brand'))
								{{-- select brand --}}
								<div class="form-group  kind kind0 kind1  {{ $errors->has('brand_id') ? ' has-error' : '' }}">
									<label for="brand_id"
									class="col-sm-2 asterisk control-label">{{ trans('product.brand') }}</label>
									<div class="col-sm-8">
										<select class="form-control input-sm brand_id select2" style="width: 100%;"
										name="brand_id">
											<option value=""></option>
											@foreach ($brands as $k => $v)
											<option value="{{ $k }}" {{ (old('brand_id') ==$k) ? 'selected':'' }}>{{ $v->name }}
											</option>
											@endforeach
											</select>
											@if ($errors->has('brand_id'))
											<span class="help-block">
												<i class="fa fa-info-circle"></i> {{ $errors->first('brand_id') }}
											</span>
											@endif
										</div>
									</div>
									{{-- //select brand --}}   
									@endif
									
									
									@if (sc_config('product_vendor'))
									{{-- select vendor --}}
									<div class="form-group  kind kind0 kind1  {{ $errors->has('vendor_id') ? ' has-error' : '' }}">
										<label for="vendor_id"
										class="col-sm-2 asterisk control-label">{{ trans('product.vendor') }}</label>
										<div class="col-sm-8">
											<select class="form-control input-sm vendor_id select2" style="width: 100%;"
											name="vendor_id">
												<option value=""></option>
												@foreach ($vendors as $k => $v)
												<option value="{{ $k }}" {{ (old('vendor_id') ==$k) ? 'selected':'' }}>
												{{ $v->name }}</option>
												@endforeach
												</select>
												@if ($errors->has('vendor_id'))
												<span class="help-block">
													<i class="fa fa-info-circle"></i> {{ $errors->first('vendor_id') }}
												</span>
												@endif
											</div>
										</div>
										{{--// select vendor --}}
										@endif
										
										@if (sc_config('product_cost'))
										{{-- cost --}}
										<div class="form-group  kind kind0 kind1  {{ $errors->has('cost') ? ' has-error' : '' }}">
											<label for="cost" class="col-sm-2  control-label">{{ trans('product.cost') }}</label>
											<div class="col-sm-8">
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
													<input type="number" style="width: 100px;" id="cost" name="cost"
													value="{!! old('cost')??0 !!}" class="form-control input-sm cost"
													placeholder="" />
												</div>
												@if ($errors->has('cost'))
												<span class="help-block">
													<i class="fa fa-info-circle"></i> {{ $errors->first('cost') }}
												</span>
												@endif
											</div>
										</div>
										{{-- //cost --}}
										@endif
										<div class="form-group  ">
											<label for="noprice" class="col-sm-2  control-label">Share for free </label>
											<div class="col-sm-8">
												@if (old())
												<input type="checkbox" name="noprice" {{ ((old('noprice') ==='on')?'checked':'')}}>
												@else
												<input type="checkbox" name="noprice" id="noprice">
												@endif
												
												</div>
												</div>
												@if (sc_config('product_price'))
												{{-- price --}}
												<div class="form-group  kind kind0 kind1  {{ $errors->has('price') ? ' has-error' : '' }}">
												<label for="price" class="col-sm-2  control-label">{{ trans('product.price') }}</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
														<input type="number" style="width: 100px;" id="price" name="price"
														value="{!! old('price')??0 !!}" class="form-control input-sm price"
														placeholder="" />
													</div>
													@if ($errors->has('price'))
													<span class="help-block">
														<i class="fa fa-info-circle"></i> {{ $errors->first('price') }}
													</span>
													@endif
												</div>
											</div>
											{{-- //price --}}
											@endif
											
											
											@if (sc_config('product_promotion'))
											{{-- price promotion --}}
											<div class="form-group  kind kind0 kind1">
												<label for="price"
												class="col-sm-2  control-label">{{ trans('product.price_promotion') }}</label>
												<div class="col-sm-8">
													@if (old('price_promotion'))
													<div class="price_promotion">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
															<input type="number" style="width: 100px;" id="price_promotion"
															name="price_promotion" value="{!! old('price_promotion')??0 !!}"
															class="form-control input-sm price" placeholder="" />
															<span title="Remove" class="btn btn-flat btn-sm btn-danger removePromotion"><i
															class="fa fa-times"></i></span>
														</div>
														
														<div class="form-inline">
															<div class="input-group">
																{{ trans('product.price_promotion_start') }}<br>
																<div class="input-group">
																	<span class="input-group-addon"><i
																	class="fa fa-calendar fa-fw"></i></span>
																	<input type="text" style="width: 100px;" id="price_promotion_start"
																	name="price_promotion_start"
																	value="{!!old('price_promotion_start')!!}"
																	class="form-control input-sm price_promotion_start date_time"
																	placeholder="" />
																</div>
															</div>
															
															<div class="input-group">
																{{ trans('product.price_promotion_end') }}<br>
																<div class="input-group">
																	<span class="input-group-addon"><i
																	class="fa fa-calendar fa-fw"></i></span>
																	<input type="text" style="width: 100px;" id="price_promotion_end"
																	name="price_promotion_end" value="{!!old('price_promotion_end')!!}"
																	class="form-control input-sm price_promotion_end date_time"
																	placeholder="" />
																</div>
															</div>
														</div>
													</div>
													@else
													<button type="button" id="add_product_promotion" class="btn btn-flat btn-success">
														<i class="fa fa-plus" aria-hidden="true"></i>
														{{ trans('product.admin.add_product_promotion') }}
													</button>
													@endif
													
												</div>
											</div>
											{{-- //price promotion --}}
											@endif
											<div class="form-group  kind kind0 kind1 {{ $errors->has('license') ? ' has-error' : '' }}">
												
												<label for="license"
												class="col-sm-2 asterisk control-label">License</label>
												<div class="col-sm-8">
													<select class="form-control input-sm license select2" 
													data-placeholder="License" style="width: 100%;"
													name="license">
														<option value=""></option>
														@foreach ($license as $lice)
														<option {{ (old('license') ==$lice->id) ? 'selected':'' }} value="{{ $lice->id }}"
															>{{ $lice->name }}
														</option>
														@endforeach
													</select>
													@if ($errors->has('license'))
													<span class="help-block">
														<i class="fa fa-info-circle"></i> {{ $errors->first('license') }}
													</span>
													@endif
												</div>
											</div>
											@if (sc_config('product_stock'))
											{{-- stock --}}
											<div class="form-group  kind kind0  kind1 {{ $errors->has('stock') ? ' has-error' : '' }}">
												<label for="stock" class="col-sm-2  control-label">{{ trans('product.stock') }}</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
														<input type="number" style="width: 100px;" id="stock" name="stock"
														value="{!! old('stock')??0 !!}" class="form-control input-sm stock"
														placeholder="" />
													</div>
													@if ($errors->has('stock'))
													<span class="help-block">
														<i class="fa fa-info-circle"></i> {{ $errors->first('stock') }}
													</span>
													@endif
												</div>
											</div>
											{{-- //stock --}}
											@endif
											
											
											@if (sc_config('product_type'))
											{{-- type --}}
											<div class="form-group  kind kind0 kind1  {{ $errors->has('type') ? ' has-error' : '' }}">
												<label for="type" class="col-sm-2  control-label">{{ trans('product.type') }}</label>
												<div class="col-sm-8">
													@foreach ( $types as $key => $type)
													<label class="radio-inline"><input type="radio" name="type" value="{{ $key }}"
														{{ ((!old() && $key ==0) || old('type') == $key)?'checked':'' }}>{{ $type }}</label>
														@endforeach
														@if ($errors->has('type'))
														<span class="help-block">
														<i class="fa fa-info-circle"></i> {{ $errors->first('type') }}
													</span>
													@endif
												</div>
											</div>
											{{-- //type --}}
											@endif
											
											@if (sc_config('product_virtual'))
											{{-- virtual --}}
											<div class="form-group  kind kind0 kind1  {{ $errors->has('virtual') ? ' has-error' : '' }}">
												<label for="virtual" class="col-sm-2  control-label">{{ trans('product.virtual') }}</label>
												<div class="col-sm-8">
													@foreach ( $virtuals as $key => $virtual)
													<label class="radio-inline"><input type="radio" name="virtual" value="{{ $key }}"
														{{ ((!old() && $key ==0) || old('virtual') == $key)?'checked':'' }}>{{ $virtual }}</label>
														@endforeach
														@if ($errors->has('virtual'))
														<span class="help-block">
														<i class="fa fa-info-circle"></i> {{ $errors->first('virtual') }}
													</span>
													@endif
												</div>
											</div>
											{{-- //virtual --}}
											@endif
											
											
											@if (sc_config('product_available'))
											{{-- date available --}}
											<div
											class="form-group  kind kind0 kind1  {{ $errors->has('date_available') ? ' has-error' : '' }}">
												<label for="date_available"
												class="col-sm-2  control-label">{{ trans('product.date_available') }}</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
														<input type="text" style="width: 100px;" id="date_available" name="date_available"
														value="{!!old('date_available')!!}"
														class="form-control input-sm date_available date_time" placeholder="" />
													</div>
													@if ($errors->has('date_available'))
													<span class="help-block">
														<i class="fa fa-info-circle"></i> {{ $errors->first('date_available') }}
													</span>
													@endif
												</div>
											</div>
											{{-- //date available --}}
											@endif
											<div class="form-group  kind kind0 kind1 ">
												
												<label for="related"
												class="col-sm-2 control-label">Related  Product</label>
												<div class="col-sm-4">
													<select class="form-control input-sm select2 related-category" data-placeholder="Select category"  multiple style="width: 100%;">
														<option value=""></option>
														@foreach ($categories as $v)
														<option value="{{ $v->id }}"
														>{{ $v->name }}
														</option>
														@endforeach
													</select>
													@if ($errors->has('category'))
													<span class="help-block">
														<i class="fa fa-info-circle"></i> {{ $errors->first('category') }}
													</span>
													@endif
												</div>
												<div class="col-sm-4">
													<select class="form-control input-sm res-subcategory  select2"  data-placeholder="Select subcategory" multiple style="width: 100%;" >
														<option value=""></option>
														
													</select>
													@if ($errors->has('subcategory'))
													<span class="help-block">
														<i class="fa fa-info-circle"></i> {{ $errors->first('subcategory') }}
													</span>
													@endif
												</div>
											</div>
											
											<div class="form-group  kind kind0 kind1 {{ $errors->has('related') ? ' has-error' : '' }}">
												
												<label for="related"
												class="col-sm-2 control-label"></label>
												
												<div class="col-sm-8">
													<select class="form-control input-sm related select2" multiple="multiple" data-placeholder="Related Product" style="width: 100%;" name="related[]">
														<option value=""></option>
														<?php if(!empty($product->related)) { 
														$newsexpl = explode(",",$product->related);} ?>   
														@foreach ($related as $rlpro)
														<option <?php if(!empty($product->related)) { if(in_array($rlpro->id,$newsexpl)) echo 'selected'; } ?> value="{{ $rlpro->id }}"
														>{{ $rlpro->name }}
														</option>
														@endforeach
													</select>
													@if ($errors->has('related'))
													<span class="help-block">
														<i class="fa fa-info-circle"></i> {{ $errors->first('related') }}
													</span>
													@endif
												</div>
											</div>
											
											
											{{-- sort --}}
											<div class="form-group    {{ $errors->has('sort') ? ' has-error' : '' }}">
												<label for="sort" class="col-sm-2  control-label">{{ trans('product.sort') }}</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
														<input type="number" style="width: 100px;" id="sort" name="sort"
														value="{!! old('sort')??0 !!}" class="form-control input-sm sort"
														placeholder="" />
													</div>
													@if ($errors->has('sort'))
													<span class="help-block">
														<i class="fa fa-info-circle"></i> {{ $errors->first('sort') }}
													</span>
													@endif
												</div>
											</div>
											{{-- //sort --}}
											
											
											{{-- status --}}
											<div class="form-group  ">
												<label for="status" class="col-sm-2  control-label">{{ trans('product.status') }}</label>
												<div class="col-sm-8">
													@if (old())
													<input type="checkbox" name="status" {{ ((old('status') ==='on')?'checked':'')}}>
													@else
													<input type="checkbox" name="status" checked>
													@endif
													
													</div>
													</div>
													{{-- //status --}}
													
													<div class="form-group  ">
													<label for="featured" class="col-sm-2  control-label">Featured</label>
													<div class="col-sm-8">
													@if (old())
													<input type="checkbox" name="featured" {{ ((old('featured') ==='on')?'checked':'')}}>
													@else
													<input type="checkbox" name="featured">
													@endif
													
													</div>
													</div>
													
													@if (sc_config('product_kind'))
													{{-- List product in groups --}}
													<div class="form-group  kind kind2 {{ $errors->has('productInGroup') ? ' has-error' : '' }}">
													<hr>
													<label class="col-sm-2 control-label"></label>
													<div class="col-sm-8"><label>{{ trans('product.admin.select_product_in_group') }}</label>
													</div>
												</div>
												<div class="form-group  kind kind2 {{ $errors->has('productInGroup') ? ' has-error' : '' }}">
													<div class="col-sm-2"></div>
													<div class="col-sm-8">
														@if (old('productInGroup'))
														@foreach (old('productInGroup') as $pID)
														@if ( (int)$pID)
														@php
														$newHtml = str_replace('value="'.(int)$pID.'"', 'value="'.(int)$pID.'" selected',
														$htmlSelectGroup);
														@endphp
														{!! $newHtml !!}
														@endif
														@endforeach
														@endif
														<button type="button" id="add_product_in_group" class="btn btn-flat btn-success">
															<i class="fa fa-plus" aria-hidden="true"></i>
															{{ trans('product.admin.add_product') }}
														</button>
														@if ($errors->has('productInGroup'))
														<span class="help-block">
															<i class="fa fa-info-circle"></i> {{ $errors->first('productInGroup') }}
														</span>
														@endif
													</div>
												</div>
												{{-- //end List product in groups --}}
												
												
												{{-- List product build --}}
												<div class="form-group  kind kind1 {{ $errors->has('productBuild') ? ' has-error' : '' }}">
													<hr>
													<label class="col-sm-2 control-label"></label>
													<div class="col-sm-8">
														<label>{{ trans('product.admin.select_product_in_build') }}</label>
													</div>
												</div>
												
												<div
												class="form-group  kind kind1 {{ ($errors->has('productBuild') || $errors->has('productBuildQty'))? ' has-error' : '' }}">
													<div class="col-sm-2">
													</div>
													<div class="col-sm-8">
														
														@if (old('productBuild'))
														@foreach (old('productBuild') as $key => $pID)
														@if ( (int)$pID && (int)old('productBuildQty')[$key])
														@php
														$newHtml = str_replace('value="'.(int)$pID.'"', 'value="'.(int)$pID.'" selected',
														$htmlSelectBuild);
														$newHtml = str_replace('name="productBuildQty[]" value="1" min=1',
														'name="productBuildQty[]" value="'.(int)old('productBuildQty')[$key].'"', $newHtml);
														@endphp
														{!! $newHtml !!}
														@endif
														@endforeach
														@endif
														<button type="button" id="add_product_in_build" class="btn btn-flat btn-success">
															<i class="fa fa-plus" aria-hidden="true"></i>
															{{ trans('product.admin.add_product') }}
														</button>
														@if ($errors->has('productBuild') || $errors->has('productBuildQty'))
														<span class="help-block">
															<i class="fa fa-info-circle"></i> {{ $errors->first('productBuild') }}
														</span>
														@endif
														
													</div>
												</div>
												{{-- //end List product build --}}
												@endif
												
												
												@if (sc_config('product_attribute'))
												{{-- List product attributes --}}
												
												@if (!empty($attributeGroup))
												<div class="form-group kind kind0">
													<hr>
													<label class="col-sm-2 control-label"></label>
													<div class="col-sm-8">
														<label>{{ trans('product.attribute') }}</label>
													</div>
												</div>
												
												<div class="form-group kind kind0">
													<div class="col-sm-2">
													</div>
													<div class="col-sm-8">
														@foreach ($attributeGroup as $attGroupId => $attName)
														<table width="100%">
															<tr>
																<td colspan="2"><b>{{ $attName }}:</b><br></td>
															</tr>
															@if (!empty(old('attribute')[$attGroupId]))
															@foreach (old('attribute')[$attGroupId] as $attValue)
															@if ($attValue)
															@php
															$newHtml = str_replace('attribute_group', $attGroupId, $htmlProductAtrribute);
															$newHtml = str_replace('attribute_value', $attValue, $newHtml);
															@endphp
															{!! $newHtml !!}
															@endif
															@endforeach
															@endif
															<tr>
																<td colspan="2"><br><button type="button"
																	class="btn btn-flat btn-success add-attribute"
																	data-id="{{ $attGroupId }}">
																		<i class="fa fa-plus" aria-hidden="true"></i>
																		{{ trans('product.admin.add_attribute') }}
																	</button><br>
																</td>
															</tr>
														</table>
														@endforeach
													</div>
												</div>
												
												
												
												
												
												@endif
												{{-- //end List product attributes --}}
												@endif
												
												
												<!-- /.box-body -->
										<div class="form-group    {{ $errors->has('meta_title.'.$code.'.name') ? ' has-error' : '' }}">
											<label for="{{ $code }}__meta-title"
											class="col-sm-2  control-label">Meta Title <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
											<div class="col-sm-8">
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
													<input type="text" id="{{ $code }}meta-title" name="meta_title"
													value="{!! old('meta-title') !!}"
													class="form-control input-sm {{ $code.'meta-title' }}" placeholder="" />
												</div>
												@if ($errors->has('meta_title.'.$code.'.name'))
												<span class="help-block">
													<i class="fa fa-info-circle"></i>
													{{ $errors->first('meta-title.'.$code.'.name') }}
												</span>
												@endif
											</div>
										</div>
										<div class="form-group    {{ $errors->has('descriptions.'.$code.'.meta_keyword') ? ' has-error' : '' }}">
											<label for="{{ $code }}__meta_keyword"
											class="col-sm-2  control-label">Meta Keyword <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
											<div class="col-sm-8">
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
													<input type="text" id="{{ $code }}__meta_keyword" name="meta_keyword"
													value="{!! old('meta_keyword') !!}"
													class="form-control input-sm {{ $code.'__meta_keyword' }}" placeholder="" />
												</div>
												@if ($errors->has('descriptions.'.$code.'.meta_keyword'))
												<span class="help-block">
													<i class="fa fa-info-circle"></i>
													{{ $errors->first('descriptions.'.$code.'.meta_keyword') }}
												</span>
												@endif
											</div>
										</div>
										<div class="form-group    {{ $errors->has('descriptions.'.$code.'.name') ? ' has-error' : '' }}">
											<label for="{{ $code }}__name"
											class="col-sm-2  control-label">Meta Description <span class="seo" title="SEO"><i class="fa fa-coffee" aria-hidden="true"></i></span></label>
											<div class="col-sm-8">
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
													<input type="text" id="{{ $code }}__name" name="meta_description"
													value="{!! old('descriptions.'.$code.'.name') !!}"
													class="form-control input-sm {{ $code.'__name' }}" placeholder="" />
												</div>
												@if ($errors->has('descriptions.'.$code.'.meta_description'))
												<span class="help-block">
													<i class="fa fa-info-circle"></i>
													{{ $errors->first('descriptions.'.$code.'.meta_description') }}
												</span>
												@endif
											</div>
										</div>
												
											</div>
										</div>
										
										
										
										
										
										<div class="box-footer kind kind0  kind1 kind2" id="box-footer">
											
											<div class="col-md-2">
											</div>
											
											<div class="col-md-8">
												<div class="btn-group pull-right">
													<input type="submit" name="draft" value="Save As Draft" class="btn btn-primary" style="margin-right:10px;"> 
													
													<button type="submit" class="btn btn-primary">{{ trans('admin.submit') }}</button>
												</div>
												
												<div class="btn-group pull-left">
													<button type="reset" class="btn btn-warning">{{ trans('admin.reset') }}</button>
												</div>
											</div>
										</div>
										
										<!-- /.box-footer -->
										<div class="dynamic-content">
										</div>
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
						
						{{-- input image --}}
						{{-- <link rel="stylesheet" href="{{ asset('admin/plugin/fileinput.min.css')}}"> --}}
						
						@endpush
						
						@push('scripts')
						<!--ckeditor-->
						<script src="{{ asset('packages/ckeditor/ckeditor.js') }}"></script>
						<script src="{{ asset('packages/ckeditor/adapters/jquery.js') }}"></script>
						<script src="{{ asset('admin/plugin/dropzone.js')}}"></script>
						
						<!-- Select2 -->
						<script src="{{ asset('admin/AdminLTE/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
						
						{{-- input image --}}
						{{-- <script src="{{ asset('admin/plugin/fileinput.min.js')}}"></script> --}}
						
						{{-- switch --}}
						<script src="{{ asset('admin/plugin/bootstrap-switch.min.js')}}"></script>
						
						<script>
							$(document).on('change', 'select.related-category', function() {
								var cat = $(this).val();
								
								$.ajax({
									url:'{{ route('admin_product.related') }}',
									type:'POST',
									data:{cat:cat,"_token": "{{ csrf_token() }}"},
									success: function(data){
										
										$('select.related').html(data);
									}
								});
								$.ajax({
									url:'{{ route('admin_product.getsubcat') }}',
									type:'POST',
									data:{cat:cat,"_token": "{{ csrf_token() }}"},
									success: function(data){
										$('select.res-subcategory').html(data);
									}
								});
							});
							
							$(document).on('change', 'select.res-subcategory', function() {
								var cat = $(this).val();
								
								$.ajax({
									url:'{{ route('admin_product.related') }}',
									type:'POST',
									data:{cat:cat,"_token": "{{ csrf_token() }}"},
									success: function(data){
										
										$('select.related').html(data);
									}
								});
								
							});
							$(document).on('change', 'select.category', function() {
								var cat  = $(this).val();
								$.ajax({
									url:'{{ route('admin_product.subcat') }}',
									type:'POST',
									data:{cat:cat,"_token": "{{ csrf_token() }}"},
									success: function(data){
										$('select.subcategory').html(data);
									}
								});
							});
							$(function() {
								$('.img_holder').sortable({
									items: '.sortable'
								});
							});
							Dropzone.options.dropzone =
							{
								headers: {
									"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
								},            
								maxFilesize: 5000,
								renameFile: function(file) {
									var dt = new Date();
									var time = dt.getTime();
									return time+file.name;
								},
								url: '{{ route('admin_product_img.store') }}',
								acceptedFiles: ".rar,.zip",
								addRemoveLinks: true,
								timeout: 500000,
								removedfile: function(file) 
								{
									var name = file.upload.filename;
									$.ajax({                    
										type: 'POST',
										url: '{{ route('admin_product_img.delete') }}',
										data: {filename: name,  "_token": "{{ csrf_token() }}",},
										success: function (data){
											$("input[value='"+data+"']").remove();
											
										},
										error: function(e) {
											console.log(e);
										}});
										var fileRef;
										return (fileRef = file.previewElement) != null ? 
										fileRef.parentNode.removeChild(file.previewElement) : void 0;
								},
								
								success: function(file, response) 
								{   
									
									$('form#form-main .dynamic-content').append(response);
									var cnt = 1;
									$('#dropzone .dz-preview').each(function(){
										cnt++;
										$('#content'+cnt).remove();
										$(this).append('<input type="text" class="file-cont" name="content[]" id="content'+cnt+'" placeholder="File extension only: ZIP, RAR"><p class="ext-type">Type the original 3D file extension for this file. Example: <b>“ZIP”, “RAR”.</b></p>');
									});
									
									
									
								},
								error: function(file, response)
								{
									$(file.previewElement).addClass("dz-error").find('.dz-error-message').text(response);
									
									
								}
							};
						</script>
						<script type="text/javascript">
							$("[name='top'],[name='status'],[name='featured'],[name='noprice']").bootstrapSwitch();
							
						</script>
						
						<script type="text/javascript">
							$('#noprice').on('switchChange.bootstrapSwitch', function (event, state) {
								var state = event.target.checked;
								if(state) {
									$('#price, #add_product_promotion').prop('disabled',true);
								}
								else
								{
									$('#price, #add_product_promotion').prop('disabled',false);
								}
							});
							// Promotion
							$('#add_product_promotion').click(function(event) {
								$(this).before('<div class="price_promotion"><div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span><input type="number" style="width: 100px;"  id="price_promotion" name="price_promotion" value="0" class="form-control input-sm price" placeholder="" /><span title="Remove" class="btn btn-flat btn-sm btn-danger removePromotion"><i class="fa fa-times"></i></span></div><div class="form-inline"><div class="input-group">{{ trans('product.price_promotion_start') }}<br><div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span><input type="text" style="width: 100px;"  id="price_promotion_start" name="price_promotion_start" value="" class="form-control input-sm price_promotion_start date_time" placeholder="" /></div></div><div class="input-group">{{ trans('product.price_promotion_end') }}<br><div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span><input type="text" style="width: 100px;"  id="price_promotion_end" name="price_promotion_end" value="" class="form-control input-sm price_promotion_end date_time" placeholder="" /></div></div></div></div>');
								$(this).hide();
								$('.removePromotion').click(function(event) {
									$(this).closest('.price_promotion').remove();
									$('#add_product_promotion').show();
								});
								$('.date_time').datepicker({
									autoclose: true,
									format: 'yyyy-mm-dd'
								})
							});
							$('.removePromotion').click(function(event) {
								$('#add_product_promotion').show();
								$(this).closest('.price_promotion').remove();
							});
							//End promotion
							
							// Add sub images
							var id_sub_image = {{ old('sub_image')?count(old('sub_image')):0 }};
							$('#add_sub_image').click(function(event) {
								id_sub_image +=1;
								$(this).before('<div class="group-image"><div class="input-group"><input type="text" id="sub_image_'+id_sub_image+'" name="sub_image[]" value="" class="form-control input-sm sub_image" placeholder=""  /><span class="input-group-btn"><span><a data-input="sub_image_'+id_sub_image+'" data-preview="preview_sub_image_'+id_sub_image+'" data-type="product" class="btn btn-sm btn-flat btn-primary lfm"><i class="fa fa-picture-o"></i> {{trans('product.admin.choose_image')}}</a></span><span title="Remove" class="btn btn-flat btn-sm btn-danger removeImage"><i class="fa fa-times"></i></span></span></div><div id="preview_sub_image_'+id_sub_image+'" class="img_holder"></div></div>');
								$('.removeImage').click(function(event) {
									$(this).parents('.group-image').remove();
								});
								$('.lfm').filemanager();
							});
							$('.removeImage').click(function(event) {
								$(this).parents('.group-image').remove();
							});
							//end sub images
							
							// Select product in group
							$('#add_product_in_group').click(function(event) {
								var htmlSelectGroup = '{!! $htmlSelectGroup !!}';
								$(this).before(htmlSelectGroup);
								$('.select2').select2();
								$('.removeproductInGroup').click(function(event) {
									$(this).closest('table').remove();
								});
							});
							$('.removeproductInGroup').click(function(event) {
								$(this).closest('table').remove();
							});
							//end select in group
							
							// Select product in build
							$('#add_product_in_build').click(function(event) {
								var htmlSelectBuild = '{!! $htmlSelectBuild !!}';
								$(this).before(htmlSelectBuild);
								$('.select2').select2();
								$('.removeproductBuild').click(function(event) {
									$(this).closest('table').remove();
								});
							});
							$('.removeproductBuild').click(function(event) {
								$(this).closest('table').remove();
							});
							//end select in build
							
							
							// Select product attributes
							$('.add-attribute').click(function(event) {
								var htmlProductAtrribute = '{!! $htmlProductAtrribute??'' !!}';
								var attGroup = $(this).attr("data-id");
								htmlProductAtrribute = htmlProductAtrribute.replace("attribute_group", attGroup);
								htmlProductAtrribute = htmlProductAtrribute.replace("attribute_value", "");
								$(this).closest('tr').before(htmlProductAtrribute);
								$('.removeAttribute').click(function(event) {
									$(this).closest('tr').remove();
								});
							});
							$('.removeAttribute').click(function(event) {
								$(this).closest('tr').remove();
							});
							//end select attributes
							
							$(document).ready(function() {
								$('.select2').select2();
							});
							// image
							// with plugin options
							// $("input.image").fileinput({"browseLabel":"Browse","cancelLabel":"Cancel","showRemove":true,"showUpload":false,"dropZoneEnabled":false});
							
							process_form();
							
							$('[name="kind"]').change(function(event) {
								process_form();
							});
							
							function process_form(){
								var kind = $('[name="kind"] option:selected').val();
								if(kind){
									$('#loading').show();
									setTimeout(
									function(){
										$('.kind').hide();
										$('.kind'+kind).show();
										$('#main-add').show();
										$('#loading').hide();
									}
									, 500);
									}else{
									Swal.fire(
									'{{ trans('product.admin.select_kind') }}',
									'',
									'error'
									);
									$('#main-add').hide();
									$('#box-footer').hide();
								}
							}
							
							//Date picker
							$('.date_time').datepicker({
								autoclose: true,
								format: 'yyyy-mm-dd'
							})
							
							
							$('textarea.editor').ckeditor(
							{
								filebrowserImageBrowseUrl: '{{ route('admin.home').'/'.config('lfm.url_prefix') }}?type=product',
								filebrowserImageUploadUrl: '{{ route('admin.home').'/'.config('lfm.url_prefix') }}/upload?type=product&_token={{csrf_token()}}',
								filebrowserBrowseUrl: '{{ route('admin.home').'/'.config('lfm.url_prefix') }}?type=Files',
								filebrowserUploadUrl: '{{ route('admin.home').'/'.config('lfm.url_prefix') }}/upload?type=file&_token={{csrf_token()}}',
								filebrowserWindowWidth: '900',
								filebrowserWindowHeight: '500'
							}
							);
							
							
							$(document).on('change', '.sub_image', function() {
								$('.gallery-images').addClass('hasimages');
								setTimeout(function(){
									$('#preview_image1 img').each(function(){  
										var replit = $(this).attr('src');  
										var str = replit.substring(replit.indexOf("/data/") + 1);
										str = str.replace('thumbs/','');
										str ='/'+str;
										$(this).wrap('<div class="img-outer sortable"></div>');
										$(this).after('<input type="hidden" class="more-image" name="sub_image[]" value="'+str+'">');
										$(this).after('<i class="fa" aria-hidden="true">&times;</i>');
										
									})
								},500); 
								
								
								
							});
							$(document).on('click', '#preview_image1 i.fa', function() {
								$(this).parent('.img-outer').remove();
								if( $('#preview_image1 .img-outer').length == 0)
								{
									$('.gallery-images').removeClass('hasimages');
								}
								
							})
							function checkprice(){
								var prc =  $('#price').val();
								var proprc =  $('#price_promotion').val();
								
								if(parseInt(proprc) >= parseInt(prc))
								{
									Swal.fire(
									'Promotion price should be less than the actual price',
									'',
									'error'
									);
									$('#box-footer button').prop('disabled',true);
								}
								else
								{
									$('#box-footer button').prop('disabled',false);
								}
							}
							
							
							var timer;
							$(document).on('keyup', '#price, #price_promotion', function () {
								clearTimeout(timer);
								timer = setTimeout(function (event) {
									
									checkprice();
								}, 1000);
							});
							
							
						</script>
						
					@endpush										