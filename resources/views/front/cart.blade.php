@include('front.theme.header')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
V
<script src="https://js.stripe.com/v3/"></script>
 <style type="text/css">
        .panel-title {
        display: inline;
        font-weight: bold;
        }
        .display-table {
            display: table;
        }
        .display-tr {
            display: table-row;
        }
        .display-td {
            display: table-cell;
            vertical-align: middle;
            width: 61%;
        }
    </style>
<section class="cart">
    <div class="container">
        <h2 class="sec-head">My Cart</h2>
        <div class="row">
            @if (count($cartdata) == 0)
                <p>Your selections come here</p>
            @else 
                <div class="col-lg-8">
                    @foreach ($cartdata as $cart)
                    <?php
                        $data[] = array(
                            "total_price" => $cart->price
                        );
                    ?>
                    <div class="cart-box">
                        <div class="cart-pro-img">
                            <img src='{{$cart["itemimage"]->image }}' alt="">
                        </div>
                        <div class="cart-pro-details">
                            <div class="cart-pro-edit">
                                <a class="cart-pro-name">{{$cart->item_name}}</a>
                                <a href="javascript:void(0)"><i class="fal fa-trash-alt" onclick="RemoveCart('{{$cart->id}}')"></i></a>
                            </div>
                            <div class="cart-pro-edit">
                                <input type="hidden" name="max_qty" id="max_qty" value="{{$getdata->max_order_qty}}">
                                <div class="pro-add">
                                    <div class="value-button sub" id="decrease" onclick="qtyupdate('{{$cart->id}}','{{$cart->item_id}}','decreaseValue')" value="Decrease Value">
                                        <i class="fal fa-minus-circle"></i>
                                    </div>
                                    <input type="number" id="number_{{$cart->id}}" name="number" value="{{$cart->qty}}" readonly="" min="1" max="10" style="background-color: #f4f4f8;" />
                                    <div class="value-button add" id="increase" onclick="qtyupdate('{{$cart->id}}','{{$cart->item_id}}','increase')" value="Increase Value">
                                        <i class="fal fa-plus-circle"></i>
                                    </div>
                                </div>
                                <p class="cart-pricing">{{$taxval->currency}}{{number_format((float) $cart->price, 2)}}</p>
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

                            @if ($cart->item_notes != "")
                                <textarea placeholder="Your product message" readonly="">{{$cart->item_notes}}</textarea>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    @if (Session::has('offer_amount'))
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
                    @endif
                    
                </div>
                
                <div class="col-lg-4">
                    <?php 
                    $order_total = array_sum(array_column(@$data, 'total_price'));
                    $taxprice = array_sum(array_column(@$data, 'total_price'))*$taxval->tax/100; 
                    $total = array_sum(array_column(@$data, 'total_price'))+$taxprice+$taxval->delivery_charge;
                    ?>
                    <div class="cart-summary">
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

                        @if (Session::has('offer_amount') )

                            <p class="cart-total">Total Amount 
                                <span id="total_amount">
                                    {{$taxval->currency}}{{number_format((float) $order_total+$taxval->delivery_charge+$taxprice-$order_total*Session::get('offer_amount')/100, 2)}}
                                </span>
                            </p>
                        @else
                            <p class="cart-total">Total Amount <span id="total_amount">{{$taxval->currency}}{{number_format((float) $total, 2)}}</span></p>
                        @endif

                        <h4 class="sec-head openmsg mt-5" style="color: red; display: none;">Restaurant is closed.</h4>

                        <div class="cart-delivery-type open">
                            <label for="cart-delivery">
                                <input type="radio" name="cart-delivery" id="cart-delivery" checked value="1">
                                <div class="cart-delivery-type-box" onclick="hide()">
                                    <img src="{!! asset('public/front/images/pickup-truck.png') !!}" height="40" width="40" alt="">                                   
                                    <p>Delivery</p>
                                </div>
                            </label>
                            <label for="cart-pickup">
                                <input type="radio" name="cart-delivery" id="cart-pickup" value="2">
                                <div class="cart-delivery-type-box" onclick="show()">
                                    <img src="{!! asset('public/front/images/delivery.png') !!}" height="40" width="40" alt="">
                                    <p>Pickup</p>
                                </div>
                            </label>
                        </div>

                        @if (env('Environment') == 'sendbox')
                            <span style="color: red;" id="dummy-msg">You can not change this address.</span>
                            <div class="promo-wrap open">
                                <input type="text" placeholder="Enter a location" name="address" size="50" id="address" value="New York, NY, USA" required="" readonly="" autocomplete="on" > 
                                <input type="hidden" id="lat" name="lat" value="40.7127753" />
                                <input type="hidden" id="lang" name="lang" value="-74.0059728" />
                                <input type="hidden" id="city" name="city" placeholder="city" value="New York" /> 
                                <input type="hidden" id="state" name="state" placeholder="state" value="NY" /> 
                                <input type="hidden" id="country" name="country" placeholder="country" value="US" />
                            </div>

                            <div class="promo-wrap open">
                                <input type="text" id="postal_code" name="postal_code" placeholder="Postcode" value="10001" readonly="" /> 
                            </div>

                            <div class="promo-wrap open">
                                <input type="text" placeholder="Door / Flat no." name="building" id="building" required="" value="4043" readonly=""> 
                            </div>

                            <div class="promo-wrap open">
                                <input type="text" placeholder="Landmark" name="landmark" id="landmark" required="" value="Central Park" readonly=""> 
                            </div>
                        @else
                            <div class="promo-wrap open">
                                <input type="text" placeholder="Delivery Address" name="address" size="50" id="address" required="" autocomplete="on" > 
                                <input type="hidden" id="lat" name="lat" />
                                <input type="hidden" id="lang" name="lang" />
                                <input type="hidden" id="city" name="city" placeholder="city" /> 
                                <input type="hidden" id="state" name="state" placeholder="state" /> 
                                <input type="hidden" id="country" name="country" placeholder="country" />
                            </div>

                            <div class="promo-wrap open">
                                <input type="text" id="postal_code" name="postal_code" placeholder="Postcode" required="" /> 
                            </div>

                            <div class="promo-wrap open">
                                <input type="text" placeholder="Door / Flat no." name="building" id="building" required=""> 
                            </div>

                            <div class="promo-wrap open">
                                <input type="hidden" placeholder="Landmark" name="landmark" id="landmark" required="" value="Central Park" > 
                            </div>
                        @endif
                        <div class="promo-wrap open">
                            <textarea name="notes" id="notes" placeholder="Write order notes..." rows="3"></textarea>
                        </div>
                     
                        @if($getabout->address != "")
                            <div id='btna2' style="display: none;">
                                <a href="javascript:void(0)" class="contact-box">
                                    <i class="fas fa-location-arrow"></i>
                                    <p>{{$getabout->address}}</p>
                                </a>
                            </div>
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

                        @foreach($getpaymentdata as $paymentdata)

                            @if ($paymentdata->payment_name == "COD")
                                <div class="mt-3">
                                    <button type="button" id="btna" style="width: 100%; display: none;" class="btn open comman" onclick="CashonDelivery()">Cash Payment</button>
                                </div>
                            @endif

                            @if ($paymentdata->payment_name == "RazorPay")
                                <div class="mt-3">
                                    <button type="button" style="width: 100%;" class="btn buy_now open comman">RazorPay Payment</button>
                                </div>

                                @if($paymentdata->environment=='1')
                                    <input type="hidden" name="razorpay" id="razorpay" value="{{$paymentdata->test_public_key}}">
                                @else
                                    <input type="hidden" name="razorpay" id="razorpay" value="{{$paymentdata->live_public_key}}">
                                @endif

                            @endif

                            @if ($paymentdata->payment_name == "Stripe")
                                <div class="mt-3">
                                    <button id="customButton" class="btn comman" style="display: none; width: 100%;">Pay Now</button>
                                    <button class="btn open stripe comman" style="width: 100%;" onclick="CashonDelivery('1')">Pay with bancontact</button>
                                    <!-- <button class="btn open stripe comman" style="width: 100%;" onclick="stripe()" data-toggle="modal" data-target="#exampleModal">Pay Now</button> -->
                                      
<!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">-->
<!-- Pay Now-->
<!--</button>-->
                                </div>

                                @if($paymentdata->environment=='1')
                                    <input type="hidden" name="stripe" id="stripe" value="{{$paymentdata->test_public_key}}">
                                @else
                                    <input type="hidden" name="stripe" id="stripe" value="{{$paymentdata->live_public_key}}">
                                @endif
                            @endif

                        @endforeach

                    </div>
                </div>
            @endif
   
                <div class="panel-body">
  
                    @if (Session::has('success'))
                        <div class="alert alert-success text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            <p>{{ Session::get('success') }}</p>
                        </div>
                    @endif
                    
 
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          
          <input type="hidden" name="indetd" id="indetd" value="<?php  echo $intent['client_secret'] ?>">
          <input type="hidden" name="indetdemail" id="indetdemail" value="saleemmayo371@gmail.com">
          <form id="payment-form">
        <div class="">

      <label class="sr-only" for="amount">Amount</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fa fa-gbp" style="color: green;font-size: 16px"></i></div>
        </div>
        <input type="hidden" name="intent" class="form-control" id="intent" readonly="" value="<?php echo $intent ?>">
        <input type="number" name="amount" class="form-control" id="amount" readonly="" value="120">
      </div>
    </div>
    <div class="">
      <label class="sr-only" for="name">Full Name</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fa fa-user" style="color: green;font-size: 16px"></i></div>
        </div>
        <input type="text" class="form-control updatestripe" id="name" placeholder="Full Name" >
      </div>
    </div>
    <div class="">
      <label class="sr-only" for="email">Email</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fa fa-envelope" style="color: green;font-size: 12px"></i></div>
        </div>
        <input type="email" name="email" class="form-control updatestripe" id="email" placeholder="Email Address.."  >
      </div>
    </div>
    <div class="">
      <label class="sr-only" for="location">Street</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fa fa-map-marker" style="color: green;font-size: 18px"></i></div>
        </div>
        <input type="text" name="location" id="location" class="form-control updatestripe" placeholder="Street Address"  >
      </div>
    </div>
    <div class="" style="width: 100%">
      <label class="sr-only" for="postal_code">PostCode</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fa fa-file-archive-o" style="color: green;font-size: 12px"></i></div>
        </div>
        <input type="text" name="postal_code" id="postal_code"  class="form-control updatestripe" placeholder="PostCode" >
      </div>
    </div>
    <div class="" style="width: 100%">
      <label class="sr-only" for="description">Descripton</label>
      <div class="input-group ">
        <textarea name="description" id="description"  class="form-control updatestripe" placeholder="descriptions..." ></textarea>
    </div>
  </div>
    <br>

       <!--card--->
       <div class="form-group">
        <label class="font-weight-bold" style="color: lightgreen">Card Details</label>
        <div id="card-element" >
          <!-- Elements will create input elements here -->
        </div>

        <!-- We'll put the error messages in this element -->
        <div id="card-errors" role="alert" style="color: red"></div>

      </div>
      <!---card end-->

<!-- <div id="refresh">
  <div id="time">
    <p id="ppera"></p>
  </div>
  
</div> -->


<div class="form-group">
       <button  id="submit" class="btn btn-block btn-success">Pay Now</button>
     </div>
       
   </form>
   <form id="banc" role="form" action="https://payment.mazadorantwerp.com/public/bancontact.php" method="post">
 
 
    @if (count($cartdata) == 0)
                <p>No Data found</p>
            @else 
 
    <input name="price" type="hidden" value="{{number_format((float) $order_total+$taxval->delivery_charge+$taxprice-$order_total*Session::get('offer_amount')/100, 2)}}">
    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />   
    <input id="output" name="name" type="hidden" value="">
@endif
 <div class="form-group">
        <label class="font-weight-bold" style="color: lightgreen">Pay with bancontact</label>
       
        <!-- We'll put the error messages in this element -->
       

      </div>
      <!---card end-->

<!-- <div id="refresh">
  <div id="time">
    <p id="ppera"></p>
  </div>
  
</div> -->

      <!-- <div class="form-group">
       <button onclick="window.location.href = 'https://payment.mazadorantwerp.com/public/bancontact.php'" id="" class="btn btn-block btn-success">Pay Now</button>
     </div> -->
      <div class="form-group">
       <button type="submit" id="" class="btn btn-block btn-success">Pay Now</button>
     </div>


   </form>
         <!--<form role="form" action="{{ route('stripe.post') }}" method="post" class="validation"-->
         <!--                                            data-cc-on-file="false"-->
         <!--                                           data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"-->
         <!--                                           id="payment-form">-->
                        
         <!--               @csrf                    <input type="hidden" name="_token" value="E4UOLbYLx7G4PasyuXbLD5Xwu7QEpST9qgl65u3B">                    <div class="form-group col-sm-12">-->
         <!--                                  <input class="form-control" type="text" name="card" placeholder="Card Number" required="">-->
         <!--             <input class="form-control" type="text" name="card" placeholder="Card Name" required="">-->

         <!--           </div>-->
               
         <!--           <div class="form-group col-sm-6">-->
         <!--             <input class="form-control card-expiry-month" type="text" name="month" placeholder="Expitation Month" required="">-->
         <!--           </div>-->
         <!--           <div class="form-group col-sm-6">-->
         <!--             <input class="form-control card-expiry-year" type="text" name="year" placeholder="Expitation Year" required="">-->
         <!--           </div>-->
         <!--           <div class="form-group col-sm-12">-->
         <!--             <input class="form-control card-cvc" type="text" name="cvc" placeholder="CVV" required="">-->
         <!--           </div>-->

         <!--           <p class="p-3">Stripe is the faster &amp; safer way to send money. Make an online payment via Stripe.</p>-->
         <!--       </form>-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               

        <button type="button" class="btn btn-primary" type="submit">Save changes</button>
      </div>
    </div>
  </div>
</div>
  
                      
  
                </div>
      

  
<!--<script type="text/javascript" src="https://js.stripe.com/v2/"></script>-->
  <script type="text/javascript">
    // alert('submit buton');
    $("#submit").click(function(){
      // alert('submit buton');
      var intent=$("#intent").val();
      console.log(intent)
    })

  // Set your publishable key: remember to change this to your live publishable key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
var stripe = Stripe('pk_test_51H8gmiFxQWPaCVe0X8FDHFNVCWm0Z0IE9DAYHPhKJLTCIv3Ksqj7lGv7qzkuwmqGuXmxAuu3owXMcWWIBJ58U3AE00NI3vV5pz');
var elements = stripe.elements();

// Set up Stripe.js and Elements to use in checkout form
var style = {
  base: {
    color: "#32325d",
  }
};

var card = elements.create("card", { style: style });
card.mount("#card-element");



card.addEventListener('change', ({error}) => {
  const displayError = document.getElementById('card-errors');
  if (error) {
    displayError.textContent = error.message;
  } else {
    displayError.textContent = '';
  }
});
// var intents=$('#indetd').val();
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(ev) {
  ev.preventDefault(); indetdemail
  var intents = $('#indetd').val();
  var indetdemail=$('#indetdemail').val();

  stripe
    .confirmCardPayment(intents, {
      payment_method: {
        card: card
      }
    }).then(function(result) {
    if (result.error) {
      // '<?= $intent->client_secret; ?>'
      // Show error to your customer (e.g., insufficient funds)
      console.log(result.error.message);
      $('#card-errors').text(result.error.message);
    } else {
      // The payment has been processed!
      if (result.paymentIntent.status === 'succeeded') {
        // Show a success message to your customer
        // There's a risk of the customer closing the window before callback
        // execution. Set up a webhook or plugin to listen for the
        // payment_intent.succeeded event that handles any business critical
        // post-payment actions.

        console.log(result);


        $('#card-errors').text('Payment Completed');
        window.location.replace("thankyou.php")

      }
    }
  });
});
</script>

<script >

$(document).ready(function () {
    $(document).on("mouseleave", ".updatestripe", function() {
      // alert('comming');
      var intent=$("#intent").val();
      console.log(intent)
    $.ajax({
      url: "intent.php",
      type: "GET",
      cache: false,
      data:{
        type:3,
        name: $("#name").val(),email: $("#email").val(),description: $("#description").val(),location: $("#location").val(),postal_code: $("#postal_code").val()
      },
      success: function(dataResult){
        // alert('duccess')
        $("#refresh").load(location.href + " #refresh"); 
        $("#time").load(location.href + " #time");
        // $("#scriptre").load(location.href + "#scriptre");
        console.log(dataResult);
          // $('#deleteEmployeeModal').modal('hide');
          // $("#"+dataResult).remove();
      
      }
    });
  });
    });

</script>
  
<!--<script type="text/javascript">-->
<!--$(function() {-->
<!--    var $form         = $(".validation");-->
<!--  $('form.validation').bind('submit', function(e) {-->
<!--    var $form         = $(".validation"),-->
<!--        inputVal = ['input[type=email]', 'input[type=password]',-->
<!--                         'input[type=text]', 'input[type=file]',-->
<!--                         'textarea'].join(', '),-->
<!--        $inputs       = $form.find('.required').find(inputVal),-->
<!--        $errorStatus = $form.find('div.error'),-->
<!--        valid         = true;-->
<!--        $errorStatus.addClass('hide');-->
 
<!--        $('.has-error').removeClass('has-error');-->
<!--    $inputs.each(function(i, el) {-->
<!--      var $input = $(el);-->
<!--      if ($input.val() === '') {-->
<!--        $input.parent().addClass('has-error');-->
<!--        $errorStatus.removeClass('hide');-->
<!--        e.preventDefault();-->
<!--      }-->
<!--    });-->
  
<!--    if (!$form.data('cc-on-file')) {-->
<!--      e.preventDefault();-->
<!--      Stripe.setPublishableKey($form.data('stripe-publishable-key'));-->
<!--      Stripe.createToken({-->
<!--        number: $('.card-num').val(),-->
<!--        cvc: $('.card-cvc').val(),-->
<!--        exp_month: $('.card-expiry-month').val(),-->
<!--        exp_year: $('.card-expiry-year').val()-->
<!--      }, stripeHandleResponse);-->
<!--    }-->
  
<!--  });-->
  
<!--  function stripeHandleResponse(status, response) {-->
<!--        if (response.error) {-->
<!--            $('.error')-->
<!--                .removeClass('hide')-->
<!--                .find('.alert')-->
<!--                .text(response.error.message);-->
<!--        } else {-->
<!--            var token = response['id'];-->
<!--            $form.find('input[type=text]').empty();-->
<!--            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");-->
<!--            $form.get(0).submit();-->
<!--        }-->
<!--    }-->
  
<!--});-->
<!--</script>-->
</section>

<!-- Modal -->
<div class="promo-modal modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-head">
                <h4>Select Promocode</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach ($getpromocode as $promocode)
                <div class="promo-box">
                    <button class="btn btn-copy" data-id="{{$promocode->offer_code}}">Copy</button>
                    <p class="promo-title">{{$promocode->offer_name}}</p>
                    <p class="promo-code-here">Code :: <span>{{$promocode->offer_code}}</span></p>
                    <small>{{$promocode->description}}</small>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
 
<script type="text/javascript">
	function show(){
	    var x = document.getElementById('btna');
	    var y = document.getElementById('btna2');
	    x.style.display = "block";
	    y.style.display = "block";
	}
	function hide(){
	    var x = document.getElementById('btna');
	    var y = document.getElementById('btna2');
	    x.style.display = "none";
	    y.style.display = "none";
	}
</script>

@include('front.theme.footer')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://checkout.stripe.com/v2/checkout.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{$taxval->map}}&libraries=places"></script>

<script type="text/javascript">

    var handler = StripeCheckout.configure({
      key: $('#stripe').val(),
      image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
      locale: 'auto',
      token: function(token) {
        // You can access the token ID with `token.id`.
        // Get the token ID to your server-side code for use.

        var order_total = parseFloat($('#order_total').val());
        var tax = parseFloat($('#tax').val());
        var delivery_charge = parseFloat($('#delivery_charge').val());
        var discount_amount = parseFloat($('#discount_amount').val());
        var paid_amount = parseFloat($('#paid_amount').val());
        var notes = $('#notes').val();
        var address = $('#address').val();
        var promocode = $('#getpromo').val();
        var tax_amount = $('#tax_amount').val();
        var discount_pr = $('#discount_pr').val();
        var lat = 5464.675;
        var lang = 56756.54654;
        // var lat = $('#lat').val();
        // var lang = $('#lang').val();
        var building = $('#building').val();
        var landmark = $('#landmark').val();
        var postal_code = $('#postal_code').val();
        var city = $('#city').val();
        var state = $('#state').val();
        var country = $('#country').val();
        var order_type = $("input:radio[name=cart-delivery]:checked").val();
        var token = token.id;


        $('#preloader').show();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{ URL::to('stripe-payment/charge') }}",
            data: {
                order_total : paid_amount ,
                address: address , 
                promocode: promocode , 
                discount_amount: discount_amount , 
                discount_pr: discount_pr , 
                tax : tax,
                tax_amount : tax_amount,
                delivery_charge : delivery_charge ,
                notes : notes,
                order_type : order_type,
                lat : lat,
                lang : lang,
                building : building,
                landmark : landmark,
                postal_code : postal_code,
                city : city,
                state : state,
                country : country,
                stripeToken : token,
            }, 
            method: 'POST',
            success: function(response) {
                $('#preloader').hide();
                if (response.status == 1) {
                    window.location.href = SITEURL + '/orders';
                } else {
                    $('#ermsg').text(response.message);
                    $('#error-msg').addClass('alert-danger');
                    $('#error-msg').css("display","block");

                    setTimeout(function() {
                        $("#error-msg").hide();
                    }, 5000);
                }
            },
            error: function(error) {

                // $('#errormsg').show();
            }
        });
      },
      opened: function() {
        // console.log("Form opened");
      },
      closed: function() {
        // console.log("Form closed");
      }
    });

    $('#customButton').on('click', function(e) {
        // Open Checkout with further options:
        var paid_amount = parseFloat($('#paid_amount').val());
        var order_total = parseFloat($('#order_total').val());
        var order_type = $("input:radio[name=cart-delivery]:checked").val();
        var address = $('#address').val();
        var lat = 5464.675;
        var lang = 56756.54654;
        // var lat = $('#lat').val();
        // var lang = $('#lang').val();
        var landmark = $('#landmark').val();
        var postal_code = $('#postal_code').val();
        var building = $('#building').val();
        var email = $('#email').val();

        if (order_type == "1") {
            if (address == "" && lat == "" && lang == "") {
                $('#ermsg').text('Address is required');            
                $('#error-msg').addClass('alert-danger');
                $('#error-msg').css("display","block");

                setTimeout(function() {
                    $("#error-msg").hide();
                }, 5000);
            } else if (lat == "") {
                $('#ermsg').text('Please select the address from suggestion');
                $('#error-msg').addClass('alert-danger');
                $('#error-msg').css("display","block");

                setTimeout(function() {
                    $("#error-msg").hide();
                }, 5000);

            } else if (lang == "") {
                $('#ermsg').text('Please select the address from suggestion');
                $('#error-msg').addClass('alert-danger');
                $('#error-msg').css("display","block");

                setTimeout(function() {
                    $("#error-msg").hide();
                }, 5000);

            } else if (building == "") {
                $('#ermsg').text('Door / Flat no. is required');
                $('#error-msg').addClass('alert-danger');
                $('#error-msg').css("display","block");

                setTimeout(function() {
                    $("#error-msg").hide();
                }, 5000);

            } else if (landmark == "") {
                $('#ermsg').text('Landmark is required');
                $('#error-msg').addClass('alert-danger');
                $('#error-msg').css("display","block");

                setTimeout(function() {
                    $("#error-msg").hide();
                }, 5000);
            } else if (postal_code == "") {
                $('#ermsg').text('Postal Code is required');
                $('#error-msg').addClass('alert-danger');
                $('#error-msg').css("display","block");

                setTimeout(function() {
                    $("#error-msg").hide();
                }, 5000);
            } else {
                handler.open({
                    name: 'Mazador App',
                    description: 'Mazador Food Service',
                    amount: paid_amount*100,
                    email: email
                });
                e.preventDefault();
                // Close Checkout on page navigation:
                $(window).on('popstate', function() {
                  handler.close();
                });
            }
        } else {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:"{{ URL::to('/home/checkpincode') }}",
                data: {
                    postal_code: postal_code,
                    order_total: order_total,
                },
                method: 'POST',
                success: function(result) {
                    if (result.status == 1) {
                        handler.open({
                            name: 'Food App',
                            description: 'Food Service',
                            amount: paid_amount*100,
                            email: email
                        });
                        e.preventDefault();
                        // Close Checkout on page navigation:
                        $(window).on('popstate', function() {
                          handler.close();
                        });
                    } else {
                        $('#ermsg').text(result.message);
                        $('#error-msg').addClass('alert-danger');
                        $('#error-msg').css("display","block");

                        setTimeout(function() {
                            $("#error-msg").hide();
                        }, 5000);
                    }
                },
            });
        }

    });

</script>
@if (env('Environment') != 'sendbox')
<script>
    function initialize() {
      var input = document.getElementById('address');
    //   var autocomplete = new google.maps.places.Autocomplete(input);
    //     google.maps.event.addListener(autocomplete, 'place_changed', function () {
    //         var place = autocomplete.getPlace();

    //         for (var i = 0; i < place.address_components.length; i++) {
    //             var addressType = place.address_components[i].types[0];
                
    //             if (addressType == "administrative_area_level_1") {
    //               document.getElementById("state").value = place.address_components[i].short_name;
    //             }

    //             if (addressType == "postal_code") {
    //                 document.getElementById("postal_code").value = place.address_components[i].short_name;
    //             }

    //             if (addressType == "locality") {
    //               document.getElementById("city").value = place.address_components[i].short_name;
    //             }

    //             // for the country, get the country code (the "short name") also
    //             if (addressType == "country") {
    //               document.getElementById("country").value = place.address_components[i].short_name;
    //             }
    //           }

    //         document.getElementById('lat').value = place.geometry.location.lat();
    //         document.getElementById('lang').value = place.geometry.location.lng();
    //     });
    // }
    // google.maps.event.addDomListener(window, 'load', initialize);
</script>
@endif
<script>
    $(document).ready(function() {


        document.getElementById("name").oninput = () => {
  const input = document.getElementById('name');
  const output = document.getElementById('output');

  output.value = input.value; 
};


        $("input[name$='cart-delivery']").click(function() {
            var test = $(this).val();

            if (test == 1) {
                $("#address").show();
                $("#delivery_charge_hide").show();
                $("#building").show();
                $("#landmark").show();
                $("#postal_code").show();
                $(".stripe").show();
                $("#dummy-msg").show();
                $("#customButton").hide();

                var order_total = parseFloat($('#order_total').val());
                var delivery_charge = parseFloat($('#delivery_charge').val());
                var tax_amount =  parseFloat($('#tax_amount').val());
                var discount_amount =  parseFloat($('#discount_amount').val());

                if (isNaN(discount_amount)) {
                    $('#total_amount').text('{{$taxval->currency}}'+(order_total+tax_amount+delivery_charge).toFixed(2));

                    $('#paid_amount').val((order_total+tax_amount+delivery_charge).toFixed(2));
                } else {
                    $('#total_amount').text('{{$taxval->currency}}'+(order_total+tax_amount+delivery_charge-discount_amount).toFixed(2));

                    $('#paid_amount').val((order_total+tax_amount+delivery_charge-discount_amount).toFixed(2));
                }

            } else {
                $("#address").hide();
                $("#delivery_charge_hide").hide();
                $("#building").hide();
                $("#landmark").hide();
                $("#postal_code").hide();
                $("#dummy-msg").hide();
                $(".stripe").hide();
                $("#customButton").show();
            
                var order_total = parseFloat($('#order_total').val());
                var delivery_charge = parseFloat($('#delivery_charge').val());
                var tax_amount =  parseFloat($('#tax_amount').val());
                var discount_amount =  parseFloat($('#discount_amount').val());

                if (isNaN(discount_amount)) {
                    $('#total_amount').text('{{$taxval->currency}}'+(order_total+tax_amount).toFixed(2));
                    $('#paid_amount').val((order_total+tax_amount).toFixed(2));
                } else {
                    $('#total_amount').text('{{$taxval->currency}}'+(order_total+tax_amount-discount_amount).toFixed(2));

                    $('#paid_amount').val((order_total+tax_amount-discount_amount).toFixed(2));
                }
            }
        });
    });


   var SITEURL = '{{URL::to('')}}';
   $.ajaxSetup({
     headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     }
   }); 
   $('body').on('click', '.buy_now', function(e){
    var order_total = parseFloat($('#order_total').val());
    var tax = parseFloat($('#tax').val());
    var delivery_charge = parseFloat($('#delivery_charge').val());
    var discount_amount = parseFloat($('#discount_amount').val());
    var paid_amount = parseFloat($('#paid_amount').val());
    var notes = $('#notes').val();
    var address = $('#address').val();
    var promocode = $('#getpromo').val();
    var tax_amount = $('#tax_amount').val();
    var discount_pr = $('#discount_pr').val();
    var lat = $('#lat').val();
    var lang = $('#lang').val();
    var building = $('#building').val();
    var landmark = $('#landmark').val();
    var postal_code = $('#postal_code').val();
    var order_type = $("input:radio[name=cart-delivery]:checked").val();

    if (order_type == "1") {
        if (address == "" && lat == "" && lang == "") {
            $('#ermsg').text('Address is required');            
            $('#error-msg').addClass('alert-danger');
            $('#error-msg').css("display","block");

            setTimeout(function() {
                $("#error-msg").hide();
            }, 5000);
        } else if (lat == "") {
            $('#ermsg').text('Please select the address from suggestion');
            $('#error-msg').addClass('alert-danger');
            $('#error-msg').css("display","block");

            setTimeout(function() {
                $("#error-msg").hide();
            }, 5000);

        } else if (lang == "") {
            $('#ermsg').text('Please select the address from suggestion');
            $('#error-msg').addClass('alert-danger');
            $('#error-msg').css("display","block");

            setTimeout(function() {
                $("#error-msg").hide();
            }, 5000);

        } else if (building == "") {
            $('#ermsg').text('Door / Flat no. is required');
            $('#error-msg').addClass('alert-danger');
            $('#error-msg').css("display","block");

            setTimeout(function() {
                $("#error-msg").hide();
            }, 5000);

        } else if (landmark == "") {
            $('#ermsg').text('Landmark is required');
            $('#error-msg').addClass('alert-danger');
            $('#error-msg').css("display","block");

            setTimeout(function() {
                $("#error-msg").hide();
            }, 5000);
        } else {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:"{{ URL::to('/home/checkpincode') }}",
                data: {
                    postal_code: postal_code,
                    order_total: order_total,
                },
                method: 'POST',
                success: function(result) {
                    if (result.status == 1) {
                        var options = {
                            "key": $('#razorpay').val(),
                            "amount": (parseInt(paid_amount*100)), // 2000 paise = INR 20
                            "name": "Food App",
                            "description": "Order Value",
                            "image": "{!! asset('public/front/images/logo.png') !!}",
                            "handler": function (response){
                                $('#preloader').show();
                                $.ajax({
                                    url: SITEURL + '/payment',
                                    type: 'post',
                                    dataType: 'json',
                                    data: {
                                        order_total : paid_amount ,
                                        razorpay_payment_id: response.razorpay_payment_id , 
                                        address: address , 
                                        promocode: promocode , 
                                        discount_amount: discount_amount , 
                                        discount_pr: discount_pr , 
                                        tax : tax ,
                                        tax_amount : tax_amount ,
                                        delivery_charge : delivery_charge ,
                                        notes : notes,
                                        order_type : order_type,
                                        lat : lat,
                                        lang : lang,
                                        building : building,
                                        landmark : landmark,
                                        postal_code : postal_code,
                                    }, 
                                    success: function (msg) {
                                    $('#preloader').hide();
                                    window.location.href = SITEURL + '/orders';
                                }
                            });
                           
                        },
                            "prefill": {
                                "contact": '{{@$userinfo->mobile}}',
                                "email":   '{{@$userinfo->email}}',
                                "name":   '{{@$userinfo->name}}',
                            },
                            "theme": {
                                "color": "#fe734c"
                            }
                        };

                        var rzp1 = new Razorpay(options);
                        rzp1.open();
                        e.preventDefault();
                    } else {
                        $('#ermsg').text(result.message);
                        $('#error-msg').addClass('alert-danger');
                        $('#error-msg').css("display","block");

                        setTimeout(function() {
                            $("#error-msg").hide();
                        }, 5000);
                    }
                },
            });
        }
    } else {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{ URL::to('/home/checkpincode') }}",
            data: {
                order_total: order_total,
            },
            method: 'POST',
            success: function(result) {
                if (result.status == 1) {
                    var options = {
                        "key": $('#razorpay').val(),
                        "amount": (parseInt(paid_amount*100)), // 2000 paise = INR 20
                        "name": "Food App",
                        "description": "Order Value",
                        "image": "{!! asset('public/front/images/logo.png') !!}",
                        "handler": function (response){
                            $('#preloader').show();
                            $.ajax({
                                url: SITEURL + '/payment',
                                type: 'post',
                                dataType: 'json',
                                data: {
                                    order_total : paid_amount ,
                                    razorpay_payment_id: response.razorpay_payment_id , 
                                    address: address , 
                                    promocode: promocode , 
                                    discount_amount: discount_amount , 
                                    discount_pr: discount_pr , 
                                    tax : tax ,
                                    tax_amount : tax_amount ,
                                    delivery_charge : '0.00',
                                    notes : notes,
                                    order_type : order_type,
                                    lat : lat,
                                    lang : lang,
                                    building : building,
                                    landmark : landmark,
                                    postal_code : postal_code,
                                }, 
                                success: function (msg) {
                                $('#preloader').hide();
                                window.location.href = SITEURL + '/orders';
                            }
                        });
                       
                    },
                        "prefill": {
                            "contact": '{{@$userinfo->mobile}}',
                            "email":   '{{@$userinfo->email}}',
                            "name":   '{{@$userinfo->name}}',
                        },
                        "theme": {
                            "color": "#fe734c"
                        }
                    };

                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                    e.preventDefault();
                } else {
                    $('#ermsg').text(result.message);
                    $('#error-msg').addClass('alert-danger');
                    $('#error-msg').css("display","block");

                    setTimeout(function() {
                        $("#error-msg").hide();
                    }, 5000);
                }
            },
        });
    }
});
/*document.getElementsClass('buy_plan1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}*/
    
    function WalletOrder() 
    {
        var total_order = parseFloat($('#order_total').val());
        var tax = parseFloat($('#tax').val());
        var delivery_charge = parseFloat($('#delivery_charge').val());
        var discount_amount = parseFloat($('#discount_amount').val());
        var paid_amount = parseFloat($('#paid_amount').val());
        var notes = $('#notes').val();
        var address = $('#address').val();
        var promocode = $('#getpromo').val();
        var tax_amount = $('#tax_amount').val();
        var discount_pr = $('#discount_pr').val();
        var lat = $('#lat').val();
        var lang = $('#lang').val();
        var postal_code = $('#postal_code').val();
        var building = $('#building').val();
        var landmark = $('#landmark').val();
        var order_type = $("input:radio[name=cart-delivery]:checked").val();

        $('#preloader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{ URL::to('/orders/walletorder') }}",
            data: {
                order_total : paid_amount ,
                total_order : total_order ,
                address: address , 
                promocode: promocode , 
                discount_amount: discount_amount , 
                discount_pr: discount_pr , 
                tax : tax,
                tax_amount : tax_amount,
                delivery_charge : delivery_charge ,
                notes : notes,
                order_type : order_type,
                lat : lat,
                lang : lang,
                postal_code : postal_code,
                building : building,
                landmark : landmark,
            }, 
            method: 'POST',
            success: function(response) {
                $('#preloader').hide();
                if (response.status == 1) {
                    window.location.href = SITEURL + '/orders';
                } else {
                    $('#ermsg').text(response.message);
                    $('#error-msg').addClass('alert-danger');
                    $('#error-msg').css("display","block");

                    setTimeout(function() {
                        $("#error-msg").hide();
                    }, 5000);
                }
            },
            error: function(error) {

                // $('#errormsg').show();
            }
        });
    }

    function CashonDelivery(code = "") 
    {
        var banc = code;
        // alert(code);
        var total_order = parseFloat($('#order_total').val());
        var tax = parseFloat($('#tax').val());
        var delivery_charge = parseFloat($('#delivery_charge').val());
        var discount_amount = parseFloat($('#discount_amount').val());
        var paid_amount = parseFloat($('#paid_amount').val());
        var notes = $('#notes').val();
        var address = $('#address').val();
        var promocode = $('#getpromo').val();
        var tax_amount = $('#tax_amount').val();
        var discount_pr = $('#discount_pr').val();
        var lat = 5464.675;
        var lang = 56756.54654;
        var postal_code = $('#postal_code').val();
        var building = $('#building').val();
        var landmark = $('#landmark').val();
        var order_type = $("input:radio[name=cart-delivery]:checked").val();

        $('#preloader').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{ URL::to('/orders/cashondelivery') }}",
            data: {
                order_total : paid_amount ,
                total_order : total_order ,
                address: address , 
                promocode: promocode , 
                discount_amount: discount_amount , 
                discount_pr: discount_pr , 
                tax : tax,
                tax_amount : tax_amount,
                delivery_charge : delivery_charge ,
                notes : notes,
                order_type : order_type,
                lat : lat,
                lang : lang,
                postal_code : postal_code,
                building : building,
                landmark : landmark,
                banc : banc,
            }, 
            method: 'POST',
            success: function(response) {
                $('#preloader').hide();
                if (response.status == 1) {
                    if(code == "1"){
                        $('#banc').submit();
                    }else{
                        window.location.href = SITEURL + '/orders';
                    }
                   
                } else {
                    $('#ermsg').text(response.message);
                    $('#error-msg').addClass('alert-danger');
                    $('#error-msg').css("display","block");

                    setTimeout(function() {
                        $("#error-msg").hide();
                    }, 5000);
                }
            },
            error: function(error) {

                // $('#errormsg').show();
            }
        });
    }

    function stripe() {
        var postal_code = $('#postal_code').val();
        var order_total = $('#order_total').val();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{ URL::to('/home/checkpincode') }}",
            data: {
                postal_code: postal_code,
                order_total: order_total,
            },
            method: 'POST',
            success: function(result) {
                if (result.status == 1) {
                    $("#customButton").click();
                } else {
                    $('#ermsg').text(result.message);
                    $('#error-msg').addClass('alert-danger');
                    $('#error-msg').css("display","block");

                    setTimeout(function() {
                        $("#error-msg").hide();
                    }, 5000);
                }
            },
        });
    }
</script>

<script>
jQuery(document).ready(function(){
jQuery('#ajaxSubmit').click(function(e){
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });
    $('#preloader').show();
    jQuery.ajax({
        url: "{{ URL::to('/cart/applypromocode') }}",
        method: 'post',
        data: {
            promocode: jQuery('#promocode').val()
        },
        success: function(response){
            $('#preloader').hide();

            if (response.status == 1) {

                $('.offer_amount').css("display","flex");
                var order_total = parseFloat($('#order_total').val());
                var delivery_charge = parseFloat($('#delivery_charge').val());
                var tax_amount =  parseFloat($('#tax_amount').val());
                var offer_amount = (order_total*response.data.offer_amount/100);

                $('#discount_pr').val(response.data.offer_amount);
                $('#getpromo').val(response.data.offer_code);

                $('#offer_amount').text('-$'+(order_total*response.data.offer_amount/100).toFixed(2));
                $('#discount_amount').val((order_total*response.data.offer_amount/100));

                $('#total_amount').text('{{$taxval->currency}}'+((order_total+delivery_charge-offer_amount)+tax_amount).toFixed(2));

                $('#paid_amount').val(((order_total+delivery_charge-offer_amount)+tax_amount).toFixed(2));

                $('#msg').text(response.message);
                $('#success-msg').addClass('alert-success');
                $('#success-msg').css("display","block");

                location.reload();
            } else {
                $('#ermsg').text(response.message);
                $('#error-msg').addClass('alert-danger');
                $('#error-msg').css("display","block");

                setTimeout(function() {
                    $("#success-msg").hide();
                }, 5000);
            }

        }});
    });
});

jQuery(document).ready(function(){
jQuery('#ajaxRemove').click(function(e){
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });
    $('#preloader').show();
    jQuery.ajax({
        url: "{{ URL::to('/cart/removepromocode') }}",
        method: 'post',
        data: {
            promocode: jQuery('#promocode').val()
        },
        success: function(response){

            $('#preloader').hide();
            if (response.status == 1) {
                $('.offer_amount').css("display","none");
                var order_total = parseFloat($('#order_total').val());
                var delivery_charge = parseFloat($('#delivery_charge').val());
                var tax_amount =  parseFloat($('#tax_amount').val());

                $('#discount_pr').val('');

                $('#discount_amount').val('');

                $('#total_amount').text('{{$taxval->currency}}'+((order_total+delivery_charge)+tax_amount).toFixed(2));

                $('#paid_amount').val(((order_total+delivery_charge)+tax_amount).toFixed(2));

                $('#msg').text(response.message);
                $('#success-msg').addClass('alert-success');
                $('#success-msg').css("display","block");

                location.reload();
            } else {

            }            
        }});
    });
});

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

$('body').on('click','.btn-copy',function(e) {
            
    var text = $(this).attr('data-id');
    // navigator.clipboard.writeText(text).then(function() {
        $('#promocode').val(text);
        $('#staticBackdrop').modal('hide');
    // }, function(err) {
         // console.error('Async: Could not copy text: ', err);
    // });
    
});
</script>