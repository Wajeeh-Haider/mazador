@include('front.theme.header')
<style>
    .feature-box:hover, .pro-box:hover, .product-tab:hover, 
    .btn:hover, .about-box:hover, .contact-from a:hover, 
    #contactform:hover {
        box-shadow: 0 0 10px 1px rgba(168,0,19,0.7);
        transition: .3s; 
    }

    .modalDialog {
    position: fixed;
    font-family: Arial, Helvetica, sans-serif;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.8);
    z-index: 99999;
    opacity:0;
    -webkit-transition: opacity 400ms ease-in;
    -moz-transition: opacity 400ms ease-in;
    transition: opacity 400ms ease-in;
    pointer-events: none;
}
.modalDialog:target {
    opacity:1;
    pointer-events: auto;
}
.modalDialog > div {
    width: 400px;
    position: relative;
    margin: 10% auto;
    padding: 5px 20px 13px 20px;
    border-radius: 10px;
    background: #fff;
    background: -moz-linear-gradient(#fff, #999);
    background: -webkit-linear-gradient(#fff, #999);
    background: -o-linear-gradient(#fff, #999);
}
.close {
    background: #606061;
    color: #FFFFFF;
    line-height: 25px;
    position: absolute;
    right: -12px;
    text-align: center;
    top: -10px;
    width: 24px;
    text-decoration: none;
    font-weight: bold;
    -webkit-border-radius: 12px;
    -moz-border-radius: 12px;
    border-radius: 12px;
    -moz-box-shadow: 1px 1px 3px #000;
    -webkit-box-shadow: 1px 1px 3px #000;
    box-shadow: 1px 1px 3px #000;
}
.close:hover {
    background: #00d9ff;
}

</style>
<section class="banner-sec" style="margin-bottom:0rem;">
    <div class="container-fluid px-0">
        <div class="banner-carousel owl-carousel owl-theme">
            @foreach ($getslider as $slider)
            <div class="item">
                <img src='{!! asset("public/images/slider/".$slider->image) !!}' alt="">
                <div class="banner-contant">
                    <h1>{{$slider->title}}</h1>
                    <p>{{$slider->description}}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="feature-sec" style="z-index: 1;">
    <div class="container">
        <div class="feature-carousel owl-carousel owl-theme">
            @foreach ($getbanner as $banner)
            <div class="item">
                <div class="feature-box">
                    <a href="{{URL::to('product-details/'.$banner->item_id)}}">
                        <img src='{!! asset("public/images/banner/".$banner->image) !!}' alt="">
                    </a>
                    <div class="feature-contant">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <!-- <a href="#openModal">Open Modal</a> -->

<div id="openModal" class="modalDialog">
    <div>	<a href="#close" title="Close" class="close">X</a>

        	<h2>Order message</h2>

        <p>Order Successful</p>
    </div>
</div>
</section>




<script>
    function send_email(vare) {
      
        data = vare;
            $.ajax({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                },

                url:"{{ URL::to('/getmail/1') }}",

                data: JSON.stringify(data),
                datatype: 'JSON',
                method: 'GET',
                contentType: 'application/json',
                success: function(response) {

                  

                    swal({
                            title: "Payment!",
                            text: "Payment Successful.",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "Ok",
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true,
                        },
                        function(isConfirm) {
                            if (isConfirm) {
                                swal.close();
                                 window.location = "https://mazadorantwerp.com/#openModal";
                            }else{
                                 window.location = "https://mazadorantwerp.com/#openModal";
                            }
                        });

                  
               
                    
                },

                error: function(e) {

                     window.location = "https://mazadorantwerp.com/#openModal";

                }

            });
        }
            </script>
        <?php
  
        if(@$_GET['status'] === '1'){
          
            echo "<script>send_email(1);</script>";
        


        }else{


        }
        
        
        
      
        
        ?>





















<section class="product-prev-sec">
    <div class="container">
        <h2 class="sec-head">Our Menu</h2>
        <div id="sync2" class="owl-carousel owl-theme">
            <?php $i=1; ?>
            @foreach ($getcategory as $category)
            <div class="item product-tab">
                <img src='{!! asset("public/images/category/".$category->image) !!}' alt=""> {{$category->category_name}}
            </div>
            <?php $i++; ?>
            @endforeach
        </div>
        <div id="sync1" class="owl-carousel owl-theme">
            <?php $i=1; ?>
            @foreach($getcategory as $category)
            <div class="item">
                <div class="tab-pane">
                    <div class="row">
                        <?php $count = 0; ?>
                        @foreach($getitem as $item)
                        @if($item->cat_id==$category->id)
                        <?php if($count == 6) break; ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="pro-box">
                                <div class="pro-img">
                                    <a href="{{URL::to('product-details/'.$item->id)}}">
                                        <img src='{{$item["itemimage"]->image }}' alt="">
                                    </a>
                                    @if (Session::get('id'))
                                        @if ($item->is_favorite == 1)
                                            <i class="fas fa-heart i"></i>
                                        @else
                                            <i class="fal fa-heart i" onclick="MakeFavorite('{{$item->id}}','{{Session::get('id')}}')"></i>
                                        @endif
                                    @else
                                        <a class="i" href="{{URL::to('/signin')}}"><i class="fal fa-heart"></i></a>
                                    @endif
                                </div>
                                <div class="product-details-wrap">
                                    <div class="product-details">
                                        <a href="{{URL::to('product-details/'.$item->id)}}">
                                            <h4>{{$item->item_name}}</h4>
                                        </a>
                                        <p class="pro-pricing">{{$getdata->currency}}{{number_format((float) $item->item_price, 2)}}</p>
                                    </div>
                                    <div class="product-details">
                                        <p>{{ Str::limit($item->item_description, 60) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $count++; ?>
                        @endif
                        @endforeach
                    </div>
                    <a href="{{URL::to('product/')}}" class="btn">View More</a>
                </div>
            </div>
            <?php $i++; ?>
            @endforeach
        </div>
    </div>
</section>

<section class="about-sec">
    <div class="container">
        <div class="about-box">
            <div class="about-img">
                <img src='{!! asset("public/images/about/".$getabout->image) !!}' alt="">
            </div>
            <div class="about-contant">
                <h2 class="sec-head text-left">About us</h2>
                <p>{!! \Illuminate\Support\Str::limit(htmlspecialchars($getabout->about_content, ENT_QUOTES, 'UTF-8'), $limit = 500, $end = '...') !!}</p>
            </div>
        </div>
    </div>
</section>

<section class="review-sec">
    <div class="container">
        <h2 class="sec-head">Our Reviews</h2>
        <div class="review-carousel owl-carousel owl-theme">
            @foreach($getreview as $review)
            <div class="item">
                <div class="review-profile">
                    <img src='{!! asset("public/images/profile/".$review["users"]->profile_image) !!}' alt="">
                </div>
                <h3>{{$review['users']->name}}</h3>
                <p>{{$review->comment}}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>

<section class="our-app">
    <div class="mapouter"><div class="gmap_canvas"><iframe class="gmap_iframe" width="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=600&amp;height=400&amp;hl=en&amp;q=Bisschopstraat 01, Antwerpen, Belgium&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe></div><style>.mapouter{position:relative;text-align:right;width:100%;height:400px;}.gmap_canvas {overflow:hidden;background:none!important;width:100%;height:400px;}.gmap_iframe {height:400px!important;}</style></div>
</section>

<section class="contact-from">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="sec-head">Contact us</h2>
                @if($getabout->mobile != "")
                    <a href="tel:{{$getabout->mobile}}" class="contact-box">
                        <i class="fas fa-phone-alt"></i>
                        <p>{{$getabout->mobile}}</p>
                    </a>
                @endif

                @if($getabout->email != "")
                    <a href="mailto:{{$getabout->email}}" class="contact-box">
                        <i class="fas fa-envelope"></i>
                        <p>{{$getabout->email}}</p>
                    </a>
                @endif

                @if($getabout->address != "")
                        <a href="https://goo.gl/maps/RwqXJdMTETMiFH9B6" class="contact-box" target="_blank">
                            <i class="fas fa-home"></i>
                            <p>{{$getabout->address}}</p>
                        </a>
                @endif
            </div>
            <div class="col-lg-6">
                <form class="contact-form" id="contactform" method="post">
                    {{csrf_field()}}
                    <input type="text" name="firstname" placeholder="First name*" id="firstname" required="">
                    <input type="text" name="lastname" placeholder="Last name*" id="lastname" required="">
                    <input type="email" name="email" placeholder="Email*" id="email" required="">
                    <textarea name="message" placeholder="Message" id="message" required=""></textarea>
                    <button type="button" name="submit" class="btn" onclick="contact()">Submit</button>
                </form>
            </div>
        </div>
    </div>
</section>

@include('front.theme.footer')