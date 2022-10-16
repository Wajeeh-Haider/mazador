@include('front.theme.header')
<style>
    .cat-check:hover, .pro-box:hover, .btn:hover {
        box-shadow: 0 0 10px 1px rgba(168,0,19,0.7);
        transition: .3s; 
    }

    .pro-total > span {


padding:12px;

    }
</style>
<section class="product-prev-sec product-list-sec">
    <div class="container">
        <div class="product-rev-wrap">
            <div class="cat-aside">
                <h3 class="text-center">Categories</h3>
                <div class="cat-aside-wrap">
                    @foreach ($getcategory as $category)
                    <a href="{{URL::to('/product/'.$category->id)}}" class="cat-check border-top-no @if (request()->id == $category->id) active @endif">
                        <img src='{!! asset("public/images/category/".$category->image) !!}' alt="">
                        <p>{{$category->category_name}}</p>
                    </a>
                    @endforeach
                </div>
            </div>
            <div class="cat-product">
                <div class="cart-pro-head">
                    <h2 class="sec-head">Our Menu</h2>
                    <div class="btn-wrap" data-toggle="buttons">
                        <label id="list" class="btn">
                            <input type="radio" name="layout" id="layout1"> <i class="fas fa-list"></i>
                        </label>
                        <label id="grid" class="btn active">
                            <input type="radio" name="layout" id="layout2" checked> <i class="fas fa-th"></i>
                        </label>
                    </div>
                </div>
                
                <div class="row">
                    @foreach ($getitem as $item)
                    <div class="col-xl-4 col-md-6">
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
                                @endif
                            </div>
                            <div class="product-details-wrap">
                                <div class="product-details">
                                    <a href="{{URL::to('product-details/'.$item->id)}}">
                                        <h4>{{$item->item_name}}</h4>
                                    </a>
                                    <p class="pro-pricing">{{$getdata->currency}}{{number_format((float) $item->item_price, 2)}}</p>
                              
                                    <input type="hidden" name="price" id="price" value="{{(float) $item->item_price}}">
                                    <input type="hidden" id="item_notes" name="item_notes" value="{{(float) $item->item_price}}">
                               
                              
                                </div>
                                <div class="product-details">
                                    <p>{{ Str::limit($item->item_description, 60) }}</p>
                                    @if (Session::get('id'))
                                        <button class="btn" onclick="AddtoCart('{{$item->id}}','{{Session::get('id')}}')">Add to Cart</button>
                                    @else
                                        <a class="btn" href="{{URL::to('/signin')}}">Add to Cart</a>
                                    @endif
                                </div>
                            </div>




                        </div>
                    </div>







                    
                    @endforeach
                </div>
            
            
            
            
            
            
            
            </div>
            <div class="cat-aside" style="margin-left: 12px;">
                <h3 class="text-center">Cart</h3>
                <div class="cat-aside-wrap" style="
    width: 300px;
">
                   <div class="cat-check border-top-no  active " style="padding: 4px 4px;">
                   <div class="col-lg-12">
                   @if (count($cartdata) == 0)
                <p>Your selections come here</p>   
            @else 
                    @foreach ($cartdata as $cart)
                    <?php
                        $data[] = array(
                            "total_price" => $cart->price
                        );
                    ?>
                    <div class="cart-box">
                        <!-- <div class="cart-pro-img">
                            <img src='{{$cart["itemimage"]->image }}' alt="">
                        </div> -->
                        <div class="cart-pro-details">
                            <div class="cart-pro-edit">
                                <a style="font-size: 15px; padding-right: 20px;" class="cart-pro-name">{{$cart->item_name}}</a>
                                <a href="javascript:void(0)"><i class="fal fa-trash-alt" onclick="RemoveCart('{{$cart->id}}')"></i></a>
                            </div>
                            <div class="cart-pro-edit">
                                <input type="hidden" name="max_qty" id="max_qty" value="{{$max->max_order_qty}}">
                                <!-- <input type="hidden" name="max_qty" id="max_qty" value="10"> -->
                                <div class="pro-add">
                                    <div class="value-button sub" id="decrease" onclick="qtyupdate('{{$cart->id}}','{{$cart->item_id}}','decreaseValue')" value="Decrease Value">
                                        <i class="fal fa-minus-circle"></i>
                                    </div>
                                    <input type="number" id="number_{{$cart->id}}" name="number" value="{{$cart->qty}}" readonly="" min="1" max="10" style="background-color: #f4f4f8;" />
                                    <div class="value-button add" id="increase" onclick="qtyupdate('{{$cart->id}}','{{$cart->item_id}}','increase')" value="Increase Value">
                                        <i class="fal fa-plus-circle"></i>
                                    </div>
                                </div>
                                <p style="font-size: 15px;     padding-left: 12px;" class="cart-pricing">{{$taxval->currency}}{{number_format((float) $cart->price, 2)}}</p>
                            </div>
                                
                            @if (count($cart['addons']) != 0)
                                <div class="cart-addons-wrap">
                                @foreach ($cart['addons'] as $addons)
                                
                                    <div class="cart-addons">
                                        <b>{{$addons['name']}}</b> : {{$taxval->currency}}{{number_format((float) $addons['price'], 2)}}
                                    </div>
                                @endforeach
                                </div>
                            @endif

                            <!-- @if ($cart->item_notes != "")
                                <textarea placeholder="Your product message" readonly="">{{$cart->item_notes}}</textarea>
                            @endif -->
                        </div>
                    </div>
                    @endforeach

                    
                    <!-- @if (Session::has('offer_amount'))
                        <div class="promo-code mb-4" style="margin: 0 auto;">
                            <form>
                            <div class="promo-wrap">
                                <input type="text" name="removepromocode" id="removepromocode" autocomplete="off" readonly="" value="{{Session::get('offer_code')}}">
                                <button class="btn" id="ajaxRemove">Remove</button>
                            </div>
                            </form>
                        </div>
                    @else
                        <div class="promo-code mb-4" style="margin: 0 auto;">
                            <form>
                            <div class="promo-wrap">
                                <input type="text" placeholder="Apply Promocode" name="promocode" id="promocode" autocomplete="off" readonly="">
                                <button class="btn" id="ajaxSubmit">Apply</button>
                            </div>
                            </form>
                            <p class="btn" data-toggle="modal" data-target="#staticBackdrop">Select Promocode</p>
                        </div>
                    @endif -->
                    <div class="col-lg-4">
                    <?php 
                    $order_total = array_sum(array_column(@$data, 'total_price'));
                    $taxprice = array_sum(array_column(@$data, 'total_price'))*$taxval->tax/100; 
                    $total = array_sum(array_column(@$data, 'total_price'))+$taxprice+$taxval->delivery_charge;
                    ?>
                    <div class="">
                        <h2 class="sec-head">Payment summary</h2>
                           
                        <p class="pro-total">Order total <span>{{$taxval->currency}}{{number_format((float) $order_total, 2)}}</span></p>
                        <p class="pro-total">Tax({{$taxval->tax}}%) <span>{{$taxval->currency}}{{number_format((float) $taxprice, 2)}}</span></p>
                        <p class="pro-total" id="delivery_charge_hide">Delivery charge<span>{{$taxval->currency}}{{number_format((float) $taxval->delivery_charge, 2)}}</span></p>

                        @if (Session::has('offer_amount'))
                            <p class="pro-total offer_amount">Discount ({{Session::get('offer_code')}})</span>
                                <span id="offer_amount">
                                    -{{$taxval->currency}}{{number_format((float) $order_total*Session::get('offer_amount')/100, 2)}}
                                </span>
                            </p>
                        @else
                            <p class="pro-total offer_amount" style="display: none">Discount <span id="offer_amount"></span></p>
                        @endif

                        @if (Session::has('offer_amount'))

                            <p class="cart-total">Total Amount 
                                <span id="total_amount">
                                    {{$taxval->currency}}{{number_format((float) $order_total+$taxval->delivery_charge+$taxprice-$order_total*Session::get('offer_amount')/100, 2)}}
                                </span>
                            </p>
                        @else
                            <p class="cart-total">Total Amount <span id="total_amount">{{$taxval->currency}}{{number_format((float) $total, 2)}}</span></p>
                        @endif

                       
                        
                        <input type="hidden" name="order_total" id="order_total" value="{{$order_total}}">
                        <input type="hidden" name="tax" id="tax" value="{{$taxval->tax}}">
                        <input type="hidden" name="tax_amount" id="tax_amount" value="{{$taxprice}}">
                        <input type="hidden" name="email" id="email" value="{{Session::get('email')}}">
                        <input type="hidden" name="delivery_charge" id="delivery_charge" value="{{$taxval->delivery_charge}}">

                        @if (Session::has('offer_amount'))
                            <input type="hidden" name="discount_amount" id="discount_amount" value="{{$order_total*Session::get('offer_amount')/100}}">
                        @else
                            <input type="hidden" name="discount_amount" id="discount_amount" value="">
                        @endif

                        @if (Session::has('offer_amount'))
                            <input type="hidden" name="paid_amount" id="paid_amount" value="{{$order_total+$taxval->delivery_charge+$taxprice-$order_total*Session::get('offer_amount')/100}}">
                        @else
                            <input type="hidden" name="paid_amount" id="paid_amount" value="{{$total}}">
                        @endif

                        @if (Session::has('offer_amount'))
                            <input type="hidden" name="discount_pr" id="discount_pr" value="{{Session::get('offer_amount')}}">
                        @else
                            <input type="hidden" name="discount_pr" id="discount_pr" value="">
                        @endif

                        @if (Session::has('offer_amount'))
                            <input type="hidden" name="getpromo" id="getpromo" value="{{Session::get('offer_code')}}">
                        @else
                            <input type="hidden" name="getpromo" id="getpromo" value="">
                        @endif

                        <!-- <div class="mt-3">                            
                            <button type="button" style="width: 100%;" class="btn open comman" onclick="WalletOrder()">My Wallet ({{$taxval->currency}}{{number_format($userinfo->wallet, 2)}})</button>
                        </div> -->

                     

                    </div>
                </div>


<button class="btn" onclick="window.location.href = '{{ URL::to('/cart')}}'">Checkout</button>

                    @endif
                </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('front.theme.footer')
<script>
function AddtoCart(id,user_id) {



var price = $('#price').val();

var item_notes = $('#item_notes').val();



var addons_id = ($('.Checkbox:checked').map(function() {

    return this.value;

}).get().join(', '));

$('#preloader').show();

$.ajax({

    headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

    },

    url:"{{ URL::to('/product/addtocart') }}",

    data: {

        item_id: id,

        addons_id: addons_id,

        qty: '1',

        price: price,

        item_notes: item_notes,

        user_id: user_id

    },

    method: 'POST', //Post method,

    dataType: 'json',

    success: function(response) {

        $("#preloader").hide();

        if (response.status == 1) {

            $('#cartcnt').text(response.cartcnt);

            $('#msg').text(response.message);

            $('#success-msg').addClass('alert-success');

            $('#success-msg').css("display","block");

            $('.view-order-btn').show();

window.location.reload();

            setTimeout(function() {

                $("#success-msg").hide();

            }, 5000);

        } else {

            $('#ermsg').text(response.message);

            $('#error-msg').addClass('alert-danger');

            $('#error-msg').css("display","block");



            setTimeout(function() {

                $("#success-msg").hide();

            }, 5000);

        }

    },

    error: function(error) {



        // $('#errormsg').show();

    }

})

};










function qtyupdate(cart_id,item_id,type) 
{
    var qtys= parseInt($("#number_"+cart_id).val());
    var max_qty = $("#max_qty").val();
    var item_id= item_id;
    var cart_id= cart_id;

    if (type == "decreaseValue") {
        qty= qtys-1;
    } else {
        qty= qtys+1;
    }

    if (qty >= "1" && qty <= max_qty) {
        $('#preloader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{ URL::to('/cart/qtyupdate') }}",
            data: {
                cart_id: cart_id,
                qty:qty,
                item_id,item_id,
                type,type
            },
            method: 'POST',
            success: function(response) {
                $('#preloader').hide();
                if (response.status == 1) {
                    location.reload();
                } else {
                    $('#ermsg').text(response.message);
                    $('#error-msg').addClass('alert-danger');
                    $('#error-msg').css("display","block");
window.location.reload();
                    setTimeout(function() {
                        $("#success-msg").hide();
                    }, 5000);
                }
            },
            error: function(error) {

                // $('#errormsg').show();
            }
        });
    } else {

        if (qty < "1") {
            $('#ermsg').text("You've reached the minimum units allowed for the purchase of this item");
            $('#error-msg').addClass('alert-danger');
            $('#error-msg').css("display","block");

            setTimeout(function() {
                $("#error-msg").hide();
            }, 5000);
        } else {
            $('#ermsg').text("You've reached the maximum units allowed for the purchase of this item");
            $('#error-msg').addClass('alert-danger');
            $('#error-msg').css("display","block");

            setTimeout(function() {
                $("#error-msg").hide();
            }, 5000);
        }
    }
}

function RemoveCart(cart_id) {
    swal({
        title: "Are you sure?",
        text: "Do you want to Remove this item from cart?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, Remove it!",
        cancelButtonText: "No, cancel plz!",
        closeOnConfirm: false,
        closeOnCancel: false,
        showLoaderOnConfirm: true,
    },
    function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:"{{ URL::to('/cart/deletecartitem') }}",
                data: {
                    cart_id: cart_id
                },
                method: 'POST',
                success: function(response) {
                    if (response == 1) {
                        swal({
                            title: "Approved!",
                            text: "Item has been removed.",
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
                                location.reload();
                            }
                        });
                    } else {
                        swal("Cancelled", "Something Went Wrong :(", "error");
                    }
                },
                error: function(e) {
                    swal("Cancelled", "Something Went Wrong :(", "error");
                }
            });
        } else {
            swal("Cancelled", "Your record is safe :)", "error");
        }
    });
}

</script>