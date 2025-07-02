@extends($templatePath.'.shop_layout')
@section('main')
<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

 html, p, body, h1, h2, h3, h4, h5, h6, span{font-family:'Montserrat',sans-serif !important;}
.card-title , .card-body {font-family:'Montserrat',sans-serif !important;}
.black-headerr{background:black !important;}
.black-headerr .bg-light{background:#131212 !important;}
.head-top-bar{background:black;color:white !important;padding:13px 0;text-align:center;}
.diivi-der{background:#6a6969 !important;color:#6a6969 !important;border:0px solid #6a6969 !important;height:18px;margin:0 4px;}
.tbar-txtt{font-size:13px;line-height: 1.6;}
.nav-list-newww{display:flex;flex-wrap:wrap;margin:0 0 0;}
.nav-list-newww > .nav--link-out > a{color:white !important;text-align:center;display:block;font-size:12px;line-height:1.2;outline:none !important;text-decoration:none !important;border:none !important;}
.black-headerr .nab_left a.navbar-brand{margin:0px 0px 0px 0px !important;}
.nav-list-newww > .nav--link-out{width:8.33%;text-align:center;padding:0px 10px;position:relative;display:flex;flex-direction:column;justify-content:center;cursor:pointer;}
.cstm-container-fluidss{padding:10px 0 10px;}
.menu-has-submenu{padding-right:23px !important;}
.nav--link-submenu{position:absolute;background:white;left:0;right:0;top:calc(100% + 10px);z-index:99;box-shadow:0 0 12px #0000003b;display:none;}
.nav--link-submenu .sub-menu--linkk > a{color:black !important;outline:none !important;text-decoration:none !important;border:none !important;word-break:break-word;}
.nav--link-submenu .sub-menu--linkk{padding:6px 4px;}
.nav--link-submenu .sub-menu--linkk:not(:last-child){border-bottom:1px solid #bdbdbd;}
.nav--link-submenu .sub-menu--linkk:hover > a,.nav-list-newww > .nav--link-out:hover > a{color:#f5921d !important;}
.ddown-btn{position:absolute;right:2px;top:50%;transform:translate(0px,-50%);width:13px;height:13px;background:url(../images/aroow-down.png) !important;background-repeat:no-repeat !important;background-position:center !important;background-size:contain !important;}



.banner-row{display:flex;flex-wrap:wrap;align-items:center;}
.banner-row *{color:white;}
.text-side-sec{width:60%;}
.imag-side-sec{width:40%;}
.slider-inner-sec{padding:40px 30px;}
.txttt-boxss{background:black;text-align:center;padding:40px 15px 20px;}
.shop-orange-btn{background-color:#f7931d;display:block;text-align:center;max-width:max-content;padding:10px 10px;color:black !important;font-weight:700;margin:15px auto 10px;font-size:18px;min-width:200px;cursor:pointer;}
.txttt-boxss h1{font-size:44px;letter-spacing:1px;color:#ffffff;font-weight:700;font-family:"Montserrat";text-align:center;line-height:1.2;}
.txttt-boxss p{font-size:18px;letter-spacing:0px;color:#ffffff;font-weight:600;font-family:"Montserrat";text-align:center;}
.banner-slide{margin-bottom:0px !important;}
.banner-slide .slick-prev{left:5px !important;}
.banner-slide .slick-next{right:5px !important;}
.banner-slide .slick-prev:before,.banner-slide .slick-next:before{content:'' !important;width:30px !important;height:30px !important;background-size:contain !important;background-repeat:no-repeat !important;background-position:center !important;opacity:1 !important;}
.banner-slide .slick-prev,.banner-slide .slick-next{width:30px !important;height:30px !important;}
.banner-slide .slick-next:before{background-image:url(../images/arrow-1.png);}
.banner-slide .slick-prev:before{background-image:url(../images/arrow.png);}
.img-boxss img{width:100%;}
.banner-slide .slick-track{display:flex;}
.banner-slide .slick-track > *{height:auto !important;}
.banner-slide .slick-track > *{background-repeat:no-repeat;background-size:cover;background-position:center;}


@media (max-width:991px){
    .banner-row > *{width:100% !important;}
    .banner-row{flex-direction:column-reverse;}
    .img-boxss img{width:100% !important;max-width:400px !important;margin:0 auto;}
    .imag-side-sec{margin:0 0 20px;}
}

@media (max-width:767px){
    .txttt-boxss h1{font-size:28px !important;margin:0 0 10px !important;}
    .txttt-boxss p{font-size:15px !important;line-height:1.3;}
}

.logo-sectionss{padding:40px 0 40px;}
.logos-lissts{display:flex;flex-wrap:wrap;column-gap:2.5%;}
.logoos-sec{width:18%;display:flex;flex-direction:column;justify-content:center;padding:10px 10px;margin:0 0 15px !important;}
.logoos-sec img{width:100% !important;}



/* Skeww-images start */

@media (min-width:992px){
    .skew-images-sectionss{padding:20px 0 40px;}
    .images-listt-inn{display:flex;flex-wrap:wrap;width:100%;overflow:hidden;}
    .imgg-list-n{width:16.667%;min-height:400px;will-change:transform;-webkit-transition:all 0.5s cubic-bezier(0.215,0.61,0.355,1);transition:all 0.5s cubic-bezier(0.215,0.61,0.355,1);position:relative;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;-webkit-backface-visibility:hidden;}
    .imgg-list-n:not(:first-child):not(:last-child) .immg-list-inner-div{border:2px solid white !important;border-top:none !important;border-bottom:none !important;}
    .images-listt-inn img{width:100%;object-fit:cover;object-position:center;display:block;}
    .imgg-list-n *{height:100% !important;}
    .immg-list-inner-div{box-shadow:0 0 0 5px rgb(255 255 255 / 70%);-webkit-transform:skewX(-10deg);-ms-transform:skewX(-10deg);transform:skewX(-10deg);overflow:hidden;position:relative;-webkit-backface-visibility:hidden;height:100%;-webkit-transition:box-shadow 0.2s ease-out;transition:box-shadow 0.2s ease-out;}
    .imgg-list-n:first-child .immg-list-inner-div{margin-left:-40px;margin-right:-40px;}
    .imgg-list-n:last-child .immg-list-inner-div{margin-right:-37px;}
    .imgg-list-n:hover{flex:1.1;-webkit-box-flex:1.1;-webkit-flex:1.1;-ms-flex:1.1;}
    .imgg-list-n:hover .immg-list-inner-div{box-shadow:0 0 0 2px #fff,0 0 50px 20px rgb(0 0 0 / 25%);}
    .caption-part{position:absolute;bottom:0;left:0;right:0;height:auto !important;width:100%;max-width:80%;padding-bottom:20px;}
    .caption-part *{color:white !important;text-align:center;max-width:max-content;width:100%;margin-left:auto !important;margin-right:auto !important;background:#000000c4;padding:7px 10px;}
    .caption-part h4{font-size:16px !important;text-transform:uppercase;font-weight:800;margin-bottom: 10px;}
    .caption-part h6{text-transform:uppercase;font-size:12px;}
}

@media (max-width:767px) and (min-width:550px){
    .imgg-list-n{width:50% !important;}
}

@media (max-width:549px){
    .images-listt-inn > *{width:100% !important;}
    .images-listt-inn{margin-left:auto !important;margin-right:auto !important;}
    .imgg-list-n .immg-list-inner-div {min-height:200px !important;padding-top:0 !important;}
}


@media (max-width:991px) {

    .skew-images-sectionss{padding:20px 0 40px;}
    .images-listt-inn{display:flex;flex-wrap:wrap;width:100%;overflow:hidden;}
    .imgg-list-n{width:33.3%;will-change:transform;position:relative;border:4px solid white;margin:0 0 20px;z-index:10;}
    .images-listt-inn img{width:100%;object-fit:cover;object-position:center;display:block;}
    .caption-part{position:absolute;bottom:0;left:0;right:0;height:auto !important;width:100%;padding-bottom:20px;z-index:10;}
    .caption-part *{color:white !important;text-align:center;max-width:max-content;width:100%;margin-left:auto !important;margin-right:auto !important;background:#000000c4;padding:7px 10px;}
    .caption-part h4{font-size:16px !important;text-transform:uppercase;font-weight:800;margin-bottom:10px;}
    .caption-part h6{text-transform:uppercase;font-size:12px;}
    .imgg-list-n .immg-list-inner-div{position:relative;z-index:1;height:auto !important;padding-top:65%;display:block;overflow:hidden;margin:0 0 0px;width:100%;}
    .imgg-list-n .immg-list-inner-div img{position:absolute;top:0;left:0;right:0;bottom:0;object-fit:cover;display:block;width:100%;height:100%;}

}

/* Skeww-images end */


/* Skeww-slidesss start */

.img-skew-wrapper{box-shadow:0 0 0 5px rgb(255 255 255 / 70%);-webkit-transform:skewX(-10deg);-ms-transform:skewX(-10deg);transform:skewX(-10deg);overflow:hidden;position:relative;-webkit-backface-visibility:hidden;height:100%;-webkit-transition:box-shadow 0.2s ease-out;transition:box-shadow 0.2s ease-out;}
.img-skew-wrapper img{width:100% !important;height:100%;min-height:400px;object-fit:cover !important;object-position:center;}
.skeww-slides .slick-track{display:flex;}
.skeww-slides-outer{will-change:transform;-webkit-transition:all 0.5s cubic-bezier(0.215,0.61,0.355,1);transition:all 0.5s cubic-bezier(0.215,0.61,0.355,1);position:relative;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;-webkit-backface-visibility:hidden;}
.skeww-slides-outer:hover{flex:1.1;-webkit-box-flex:1.1;-webkit-flex:1.1;-ms-flex:1.1;}
.skeww-slides-outer:hover .img-skew-wrapper{box-shadow:0 0 0 2px #fff,0 0 50px 20px rgb(0 0 0 / 25%);}
.skeww-slides-outer a{position:relative;display: block;}
.skew-images-captonns{position:absolute;bottom:0;left:0;right:0;height:auto !important;width:100%;max-width:80%;padding-bottom:20px;}
/* .skew-images-captonns{position:absolute;bottom:0;top:0;left:0;right:0;height:auto !important;width:100%;max-width:80%;padding-bottom:20px;padding-top:20px;display:flex;flex-direction:column;justify-content:end;} */

.skew-images-captonns *{color:white !important;text-align:center;max-width:max-content;width:100%;margin-left:auto !important;margin-right:auto !important;background:#000000c4;padding:7px 10px;}
.skew-images-captonns h4{font-size:16px !important;text-transform:uppercase;font-weight:800;margin-bottom:10px;}
.skew-images-captonns h6{text-transform:uppercase;font-size:12px;}
.skew-slidess-n .slick-prev:before,.skew-slidess-n .slick-next:before{content:'' !important;width:30px !important;height:30px !important;background-size:contain !important;background-repeat:no-repeat !important;background-position:center !important;opacity:1 !important;}
.skew-slidess-n .slick-prev, .skew-slidess-n .slick-next{width:30px !important;height:30px !important;background:black !important;border-radius: 50% !important;}
.skew-slidess-n .slick-next:before{background-image:url(../images/arrow-1.png);}
.skew-slidess-n .slick-prev:before{background-image:url(../images/arrow.png);}
.skew-slidess-n .slick-prev{left:10px !important;}
.skew-slidess-n .slick-next{right:10px !important;}


/* Skeww-slidesss End */


.icons-globlss{padding:30px 0 30px;}
.global-logos-row{display:flex;flex-wrap:wrap;background:#ebebeb !important;padding:20px 0;}
.global-logos-row .global-list-outer{width:25%;padding:10px 15px;position:relative;}
.logo-and-head-seccc{display:flex;flex-wrap:wrap;justify-content:center;margin:0 0 10px;}
.logo-and-head-seccc h3{display:flex;flex-direction:column;justify-content:center;width:calc(100% - 40px);max-width:max-content;font-size:20px !important;line-height:1.3;font-weight:700;text-align:center;margin:0 0 0 !important;}
.logo-and-head-seccc img{width:33px;height:33px;object-fit:contain;margin-right:7px;}
.text-para-seccc *{text-align:center;font-size:12px;line-height:1.3;font-weight:600;}
.global-logos-row .global-list-outer:not(:last-child){border-right:2px solid #dfdfdf !important;}

@media (max-width:767px) and (min-width:550px) {
    .global-logos-row .global-list-outer:nth-child(2n + 2){border-right:none !important;}
    .global-logos-row .global-list-outer{width:50% !important;margin:0 0 25px;}
}

@media (max-width:549px) {
    .global-logos-row .global-list-outer{width: 100% !important;margin:0 0 25px;}
    .global-logos-row{margin-left:auto !important;margin-right:auto !important;}
    .global-logos-row .global-list-outer:not(:last-child){border-bottom:2px solid #dfdfdf !important;padding-bottom:35px !important;}
}


@media (max-width:767px){
    .logo-and-head-seccc h3 {font-size:15px !important;}
    .icons-globlss > .container{max-width:100% !important;}
    .container{max-width:100% !important;}
}



.heading-sectionss h2{font-weight:700;font-size:35px;margin:0 0 15px;}
.product-list-sectionsss{padding:40px 0 20px;}
.product-list-sectionsss > .container{border-bottom:5px solid gray;padding-bottom:44px;}


.three-D-loggoss{display:flex;margin:35px 0 20px;column-gap:2%;}
.three-D-loggoss > *{width:10.75%;text-align:center;margin:0 0 15px !important;}
.three-D-loggoss img{width:100% !important;margin:0 auto 10px !important;display:block;max-width:130px !important;}
.threee-D-programss{padding:40px 0 40px;}
.three-D-loggoss > * p{font-weight:600;font-size:13px;}
.three-D-loggoss img{height:119px;object-fit:cover;}

@media (max-width:767px){
    .heading-sectionss h2{font-size:24px !important;}
}

@media (max-width:550px) and (min-width:450px){

    .three-D-loggoss > *{width:31.9% !important;}

}

@media (max-width:449px){
    
    .three-D-loggoss > *{width:49% !important;}

}


@media (max-width:991px){
    .three-D-loggoss > *{width:23.5%;}
    .three-D-loggoss{flex-wrap:wrap;}
}

@media (min-width:901px) {

    .cart.new-cartt,.cart.contact-bttnn{display:flex;flex-direction:column;justify-content:center;}
    .custom-for-desk .register_or_login{background:transparent !important;}
    .register_loginout_login .dropdown ul.dropdown-menu .login-bttn-headdd > .nav-link{background-color:#f7931d !important;display:block;padding:16px 25px !important;font-weight:500 !important;margin-right:10px;margin-top:1px;}
    .register_loginout_login .dropdown ul.dropdown-menu .login-bttn-headdd,.register_loginout_login .dropdown ul.dropdown-menu .register-bttn-headdd{padding:0 !important;margin-bottom:0px !important;width:auto !important;margin-left:auto !important;}
    .register_loginout_login .dropdown ul.dropdown-menu .register-bttn-headdd > .nav-link{background-color:#ffffff !important;display:block;padding:16px 25px !important;font-weight:500 !important;margin-top:1px;}
    .custom-for-desk .register_or_login.dropdown-menu{margin-left:auto !important;margin-right:0 !important;float:right !important;}
    .register_loginout_login{width:100%;max-width:max-content !important;}
    .nab_right .search_div{width:40% !important;}


}

@media (min-width:1081px) {
    .nab_right .search_div{margin-right:auto !important;}
    .nab_right .search_div{width:40% !important;}
    .avtarr_btn_dt img{max-width:35px !important;}
    .cart.contact-bttnn{margin-right:15px !important;margin-left:15px !important;}
    .cart.new-cartt{margin-right:10px !important;}
    .search_form_mant .btn.search_name *{color:white !important;}
    .search_form input[type="text"]{background:#404040;color:white !important;border-radius:21px !important;border:none !important;padding-left:10px;}
  



}

@media (max-width:1080px) {

    .nab_right .search_div{display:flex;}
    .search_div .tab_btn{border-bottom:none !important;margin-left:auto;}
    .search_div .tab_btn *{color:white !important;}
    .avtarr_btn_dt img{max-width: 28px !important;}
    .cart.contact-bttnn{margin-right: 3px !important;margin-left: 7px !important;}
    .cart.new-cartt{margin-right:10px !important;}
    .search_form_mant .btn.search_name *{color:white !important;}
    .search_form input[type="text"]{background:#404040;color:white !important;border-radius:21px !important;border:none !important;padding-left:10px;}
    .cart.new-cartt img{width:20px;}
    .register_loginout_login .dropdown ul.dropdown-menu li .nav-link{padding:9.6px 10px !important;}
}


@media (max-width:576px) and (min-width:425px) {
    .product-list-sectionsss .row > *{width:50% !important;}

}


.search_form_mant .btn.search_name{padding:4px 8px !important;}
.with_hund_pt .pro-details .pro-content{margin-top:10px;line-height:1.2;}


.footer-newsletter .block.newsletter .title-section:before{top:0 !important;}
/* .footer{background-color:#202020 !important;} */
.footer{background-color:black !important;}
.footer ul li .footer_atn{color:white !important;}
.support p a{color:#ff7f33 !important;}
.footer *{color:white;line-height:1.3;}
.contact_dt ul li{margin:10px 0;}
.footer .footer-sec-divide h4{margin-bottom:10px !important;font-weight:600;}
.footer ul li{margin:0 0 5px !important;}
.copyRighrt p{font-size:12px !important;}
.footer .container-fluids .row{width:100% !important;max-width:100% !important;}
.footer-sec-divide,.footer-sec-divide.shop_ul,.footer-sec-divide.why_brand,.footer-sec-divide.support{width:100% !important;}
.top-footerr{padding:50px 0;background:black;color:white;}
.top-footerr h4{font-weight:700;font-size:22px;margin:0 0 13px !important;}
.top-footerr p{line-height:1.5;font-size:14px !important;margin:0 0 22px !important;}
.foot-payment-lissttt{display:flex;flex-wrap:wrap;column-gap:10px;}
.foot-payment-lissttt > li{margin:0 0 10px !important;}
.foot-payment-lissttt > li img{width:100% !important;max-width:35px;}
.foot-n-newslett{display:flex;flex-wrap:wrap;width:100%;max-width:400px;}
.control-nnns input{border:none !important;outline:none !important;padding:12px 15px !important;font-size:15px !important;width:100%;}
.actions-nnn .new-submittt{background-color:#f7931d !important;padding:12px 15px !important;font-weight:500 !important;margin:0 !important;border:none !important;outline:none !important;height:100% !important;display:flex;flex-direction:column;justify-content:center;text-align:center;align-items:center;font-size:15px !important;width:100%;}
.actions-nnn{width:110px;}
.control-nnns{width:calc(100% - 110px);}

@media (max-width:767px) and (min-width:576px) {

    .footer .row > *:not(:nth-child(2n + 2)) {
        padding-left: 0 !important;
    }
}

@media (max-width:575px) {

    .footer .row > * {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
}


@media (max-width:767px){

    .top-footerr .row > *:not(:last-child){margin-bottom:25px;} 
}

@media (max-width:767px){

    .foot-n-newslett > *{width:100% !important;}
    .foot-n-newslett > *:not(:last-child){margin:0px 0 12px;}

}

@media (min-width:992px){

    .container{max-width:90% !important;}

}

.text-para-seccc{max-width:200px;width:100%;margin:auto;}
.logo-and-head-seccc{max-width:250px !important;margin-left:auto !important;margin-right:auto !important;}
.skew-slidess-n{padding:25px 0;}
.custmm-containersss{max-width:1480px;width:100% !important;padding:0 15px;margin:0 auto;}

@media (min-width:1600px){
    .img-skew-wrapper img {
        min-height: 55vh;
    }
}


@media (min-width:2000px){
    .img-skew-wrapper img {
        min-height: 55vh;
    }
}

@media screen and (min-width: 1480px) {
    
	.col-xl-3 {width: 25% !important;}

}

</style>
        <section class="banner-sections">
            <div class="banner-slide">
                
                <div class="slider-outer" style="background-image:url(./images/bg.jpg);">
                    <div class="slider-inner-sec">
                        <div class="custmm-containersss">
                            <div class="banner-row">

                                <div class="text-side-sec">
                                    <div class="txttt-boxss">
                                        <h1>EVERYCG 3D MODELS</h1>
                                        <p>Latest quality 3D models that are ready to use for your projects. Download them and and speed up your work</p>
                                    </div>
                                    <div class="btn-sec-boxx">
                                        <a class="shop-orange-btn">Shop Now</a>
                                    </div>
                                </div>

                                <div class="imag-side-sec">
                                    <div class="img-boxss">
                                        <img src="{{ asset('/images/Image-1.png') }}" alt="">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="slider-outer" style="background-image:url({{ asset('/images/bg.jpg') }});">
                    <div class="slider-inner-sec">
                        <div class="custmm-containersss">
                            <div class="banner-row">

                                <div class="text-side-sec">
                                    <div class="txttt-boxss">
                                        <h1>EVERYCG 3D MODELS</h1>
                                        <p>Latest quality 3D models that are ready to use for your projects. Download them and and speed up your work</p>
                                    </div>
                                    <div class="btn-sec-boxx">
                                        <a class="shop-orange-btn">Shop Now</a>
                                    </div>
                                </div>

                                <div class="imag-side-sec">
                                    <div class="img-boxss">
                                        <img src="{{ asset('/images/Image-1.png') }}" alt="">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="slider-outer" style="background-image:url({{ asset('/images/bg.jpg') }});">
                    <div class="slider-inner-sec">
                        <div class="custmm-containersss">
                            <div class="banner-row">

                                <div class="text-side-sec">
                                    <div class="txttt-boxss">
                                        <h1>EVERYCG 3D MODELS</h1>
                                        <p>Latest quality 3D models that are ready to use for your projects. Download them and and speed up your work</p>
                                    </div>
                                    <div class="btn-sec-boxx">
                                        <a class="shop-orange-btn">Shop Now</a>
                                    </div>
                                </div>

                                <div class="imag-side-sec">
                                    <div class="img-boxss">
                                        <img src="{{ asset('/images/Image-1.png') }}" alt="">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


              </div>
        </section>
        <section class="skew-slidess-n">
            <div class="custmm-container-slides">
                <div class="skeww-slides">
				@foreach ($catlist as  $key => $cat)
                    <div class="skeww-slides-outer">
                        <a href="{{ $cat->getUrl() }}">
                            <div class="img-skew-wrapper">
                                <img src="<?php if($cat->getThumb()) {?>{{ asset($cat->getThumb()) }}<?php } else {?>{{ asset('/images/skew-imag.png') }}<?php } ?>" alt="{{ $cat->name }}">
                            </div>
                            <div class="skew-images-captonns">
                                <h4>{{ $cat->name }}</h4>
                                <h6>category</h6>
                            </div>
                        </a>
                    </div>
				@endforeach	
                </div>
            </div>
        </section>
        

        <section class="icons-globlss">
            <div class="container">
                <div class="global-logos-row">

                    <div class="global-list-outer">
                        <div class="globl-list-logos">
                            <div class="logo-and-head-seccc">
                                <img src="{{ asset('/images/icon-1.png') }}" alt="">
                                <h3>Realstics</h3>
                            </div>
                            <div class="text-para-seccc">
                                <p>Photorealistic 3D Scans. 250 DSLR camera rig.</p>
                            </div>
                        </div>
                    </div>

                    <div class="global-list-outer">
                        <div class="globl-list-logos">
                            <div class="logo-and-head-seccc">
                                <img src="{{ asset('/images/icon-1.png') }}" alt="">
                                <h3>Realstics</h3>
                            </div>
                            <div class="text-para-seccc">
                                <p>Photorealistic 3D Scans. 250 DSLR camera rig.</p>
                            </div>
                        </div>
                    </div>

                    <div class="global-list-outer">
                        <div class="globl-list-logos">
                            <div class="logo-and-head-seccc">
                                <img src="{{ asset('/images/icon-1.png') }}" alt="">
                                <h3>Realstics</h3>
                            </div>
                            <div class="text-para-seccc">
                                <p>Photorealistic 3D Scans. 250 DSLR camera rig.</p>
                            </div>
                        </div>
                    </div>

                    <div class="global-list-outer">
                        <div class="globl-list-logos">
                            <div class="logo-and-head-seccc">
                                <img src="{{ asset('/images/icon-1.png') }}" alt="">
                                <h3>Realstics</h3>
                            </div>
                            <div class="text-para-seccc">
                                <p>Photorealistic 3D Scans. 250 DSLR camera rig.</p>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </section>

        <section class="product-list-sectionsss">
            <div class="container">

                <div class="heading-sectionss">
                    <h2>Featured</h2>
                </div>

                <div class="row ">
                    @foreach ($products_feat as  $key => $product_feat)
                    <?php  $pname = App\Models\ProductFiles::getFileExt($product_feat['id']); ?>                   
                    <div class="col-6 col-md-3 col-xl-3 with_hund_pt mt-3">
                        <div class="com_cover_div">
                            <a href="{{ $product_feat->getUrl() }}">
                            <div class="text-black featured_Products">
                                <div class="featured_Products_image card ">
                                    <img src="{{ asset($product_feat->getThumb()) }}" alt="{{ $product_feat->name }}">
                                     <div class="Shop_now">
                                   
                                        <span class="pname">@php
                                     echo strlen($pname) > 20 ? substr($pname,0,25)."..." : $pname;
                                  @endphp</span> <span>  {!! $product_feat->showPrice() !!} </span>
                                        
                                      </div>
                                   
                                </div>
                            </div>
                            </a>
                            <div class="card-body">
                                <div class="text-center">
                                    <h5 class="card-title">@php
                                     echo strlen($product_feat->name) > 20 ? substr($product_feat->name,0,20).".." : $product_feat->name;
                                  @endphp</h5>
                                  <div class="pro-details">
                                     <p class="show-detail">Details <i class="fa fa-angle-down"></i></p>
                                     <div class="pro-content" style="display:none">
                                      @php
                                         echo substr($product_feat->content, 0, 60).'...';
                                      @endphp                                  </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach                                       
                </div>
            </div>
        </section>

        <section class="product-list-sectionsss">
            <div class="container">

                <div class="heading-sectionss">
                    <h2>Most Popular</h2>
                </div>


                <div class="row ">
                                       
                    <div class="col-6 col-md-3 col-xl-3 with_hund_pt mt-3">
                        <div class="com_cover_div">
                            <a href="https://everycg.com/product/office-chair">
                            <div class="text-black featured_Products">
                                <div class="featured_Products_image card ">
                                    <img src="{{ asset('/data/product/38_Office Chair 1/Chair.webp') }}" alt="Office Chair">
                                     <div class="Shop_now">
                                   
                                        <span class="pname">FBX | MA | OBJ | USD</span> <span>  <span class="sc-new-price">$5</span> </span>
                                        
                                      </div>
                                   
                                </div>
                            </div>
                            </a>
                            <div class="card-body">
                                <div class="text-center">
                                    <h5 class="card-title">Office Chair</h5>
                                  <div class="pro-details">
                                     <p class="show-detail">Details <i class="fa fa-angle-down"></i></p>
                                     <div class="pro-content" style="display:none">
                                      EveryCG 3D Models&nbsp;- This 3D Model was modeled in great ...                                     </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                        
                    <div class="col-6 col-md-3 col-xl-3 with_hund_pt mt-3">
                        <div class="com_cover_div">
                            <a href="https://everycg.com/product/human">
                            <div class="text-black featured_Products">
                                <div class="featured_Products_image card ">
                                    <img src="{{ asset('/data/product/37_Human_Sofia/2.jpg') }}" alt="Human">
                                     <div class="Shop_now">
                                   
                                        <span class="pname">FBX | MA | OBJ | USD</span> <span>  <span class="sc-new-price">$5</span> </span>
                                        
                                      </div>
                                   
                                </div>
                            </div>
                            </a>
                            <div class="card-body">
                                <div class="text-center">
                                    <h5 class="card-title">Human</h5>
                                  <div class="pro-details">
                                     <p class="show-detail">Details <i class="fa fa-angle-down"></i></p>
                                     <div class="pro-content" style="display: none;">
                                      EveryCG 3D Models&nbsp;- This 3D Model was modeled in great ...                                     </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                        
                    <div class="col-6 col-md-3 col-xl-3 with_hund_pt mt-3">
                        <div class="com_cover_div">
                            <a href="https://everycg.com/product/sofa-3-seater">
                            <div class="text-black featured_Products">
                                <div class="featured_Products_image card ">
                                    <img src="{{ asset('/data/product/36_Sofa/Sofa.webp') }}" alt="Sofa - 3 seater">
                                     <div class="Shop_now">
                                   
                                        <span class="pname">FBX | MA | OBJ | USD</span> <span>  <span class="sc-new-price">$12</span> </span>
                                        
                                      </div>
                                   
                                </div>
                            </div>
                            </a>
                            <div class="card-body">
                                <div class="text-center">
                                    <h5 class="card-title">Sofa - 3 seater</h5>
                                  <div class="pro-details">
                                     <p class="show-detail">Details <i class="fa fa-angle-down"></i></p>
                                     <div class="pro-content" style="display:none">
                                      EveryCG 3D Models&nbsp;- This 3D Model was modeled in great ...                                     </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                        
                    <div class="col-6 col-md-3 col-xl-3 with_hund_pt mt-3">
                        <div class="com_cover_div">
                            <a href="https://everycg.com/product/kitchen-knife-set">
                            <div class="text-black featured_Products">
                                <div class="featured_Products_image card ">
                                    <img src="{{ asset('/data/product/35_Kitchen_knife_Set/Knife.webp') }}" alt="Kitchen Knife Set">
                                     <div class="Shop_now">
                                   
                                        <span class="pname">FBX | MA | OBJ | USD</span> <span>  <span class="sc-new-price">$5</span> </span>
                                        
                                      </div>
                                   
                                </div>
                            </div>
                            </a>
                            <div class="card-body">
                                <div class="text-center">
                                    <h5 class="card-title">Kitchen Knife Set</h5>
                                  <div class="pro-details">
                                     <p class="show-detail">Details <i class="fa fa-angle-down"></i></p>
                                     <div class="pro-content" style="display:none">
                                      EveryCG 3D Models&nbsp;- This 3D Model was modeled in great ...                                     </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                
                   
                </div>
            </div>
        </section>

        <section class="threee-D-programss">
            <div class="container">

                <div class="heading-sectionss">
                    <h2>Which 3D programs and formats are supported?</h2>
                    <p>our 3D People are available for all major 3D applications</p>
                </div>

                <div class="three-D-loggoss">

                    <div class="logo-list-thre-D">
                        <img src="{{ asset('/images/1.jpg') }}" alt="">
                        <p>3ds Max</p>
                    </div>

                    <div class="logo-list-thre-D">
                        <img src="{{ asset('/images/2.png') }}" alt="">
                        <p>Maya</p>
                    </div>

                    <div class="logo-list-thre-D">
                        <img src="{{ asset('/images/3.jpg') }}" alt="">
                        <p>Cinema 4D</p>
                    </div>

                    <div class="logo-list-thre-D">
                        <img src="{{ asset('/images/4.jpg') }}" alt="">
                        <p>SketchUp</p>
                    </div>

                    <div class="logo-list-thre-D">
                        <img src="{{ asset('/images/5.jpg') }}" alt="">
                        <p>Unreal Engine 4</p>
                    </div>

                    <div class="logo-list-thre-D">
                        <img src="{{ asset('/images/6.jpg') }}" alt="">
                        <p>Unity</p>
                    </div>

                    <div class="logo-list-thre-D">
                        <img src="{{ asset('/images/7.jpg') }}" alt="">
                        <p>Blender</p>
                    </div>

                    <div class="logo-list-thre-D">
                        <img src="{{ asset('/images/8.jpg') }}" alt="">
                        <p>Rhino</p>
                    </div>


                </div>
                </div>
        </section>


        
<!-- /.col -->
@endsection
