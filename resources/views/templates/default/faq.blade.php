@extends($templatePath.'.shop_layout')

@section('main')

        <div class=" width_int">

                 <div class="banner-sect-category"
                    style="background: url('{{ asset($pageData->image) }}');">
                    <div class="breadcrumb"><a href="{{ route('home') }}">Home </a> <span> <i class="fa fa-angle-right"></i></span>
                        {{ $pageData->title }}</div>
                    <h1 class="cat-title">{{ $pageData->title }}</h1>
                   <p class="content-category"> {!! sc_html_render($pageData->content) !!} </p>
                </div>
                
                
           <div class="main-faq-wrapperr">
           <div class="container">
            <div class="faq">
                <div class="accordion" id="accordionExample">
                     @if (!empty($faqs))
                      <?php $i=0; foreach ($faqs as $faq) {
                      $i++; ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $faq->id }}">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $faq->id }}" aria-expanded="true" aria-controls="collapse{{ $faq->id }}">
                                {{ $faq->name }}
                            </button>
                        </h2>
                        <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse <?php if($i==1) echo 'show'; ?>" aria-labelledby="heading{{ $faq->id }}"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                               <?php echo  $faq->description; ?>
                            </div>
                        </div>
                    </div>
                      <?php } ?>
                  @endif   
                    
                    
                </div>
            </div>
            </div>
            </div>
        </div>

 


@endsection


