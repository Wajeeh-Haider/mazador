<!-- <!DOCTYPE html>
<html>
<head>
    <title>Laravel 6 - Stripe Payment Gateway Integration Example - ItSolutionStuff.com</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
</head>
<body>
<form action="your-server-side-code" method="POST">
  <script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key="pk_test_51L4oWyHxJxM7Rl8Eo9yLTiURnG1Uatv6bJqXXmU2P9Hm8InUaPmlnclrDEvSuOWEgPYCtAiUsuJIb6LAPGt4mcV900Omo6w7PX"
    data-amount="999"
    data-email="javier.estevez@gmail.com"
    data-name="Stripe.com"
    data-description="Widget"
    data-allow-remember-me="false"
    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
    data-locale="auto">
  </script>
</form>

<script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key="pk_test_51L4oWyHxJxM7Rl8Eo9yLTiURnG1Uatv6bJqXXmU2P9Hm8InUaPmlnclrDEvSuOWEgPYCtAiUsuJIb6LAPGt4mcV900Omo6w7PX"
    data-amount="999"
    data-email="javier.estevez@gmail.com"
    data-name="Stripe.com"
    data-description="Widget"
    data-billing-address="true"
    data-allow-remember-me="false"
    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
    data-locale="auto">
  </script>
</form>
<div class="container">
  
    <h1>Laravel 6 - Stripe Payment Gateway Integration Example <br/> ItSolutionStuff.com</h1>
  
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading display-table" >
                    <div class="row display-tr" >
                        <h3 class="panel-title display-td" >Payment Details</h3>
                        <div class="display-td" >                            
                            <img class="img-responsive pull-right" src="http://i76.imgup.net/accepted_c22e0.png">
                        </div>
                    </div>                    
                </div>
                <div class="panel-body">
  
                    @if (Session::has('success'))
                        <div class="alert alert-success text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            <p>{{ Session::get('success') }}</p>
                        </div>
                    @endif
  
                    <form 
                            role="form" 
                            action="{{ route('stripe.post') }}" 
                            method="post" 
                            class="require-validation"
                            data-cc-on-file="false"
                            data-stripe-publishable-key="pk_test_51L4oWyHxJxM7Rl8Eo9yLTiURnG1Uatv6bJqXXmU2P9Hm8InUaPmlnclrDEvSuOWEgPYCtAiUsuJIb6LAPGt4mcV900Omo6w7PX"
                            id="payment-form">
                        @csrf
  
                        <div class='form-row row'>
                            <div class='col-xs-12 form-group required'>
                                <label class='control-label'>Name on Card</label> <input
                                    class='form-control' size='4' type='text'>
                            </div>
                        </div>
  
                        <div class='form-row row'>
                            <div class='col-xs-12 form-group card required'>
                                <label class='control-label'>Card Number</label> <input
                                    autocomplete='off' class='form-control card-number' size='20'
                                    type='text'>
                            </div>
                        </div>
  
                        <div class='form-row row'>
                            <div class='col-xs-12 col-md-4 form-group cvc required'>
                                <label class='control-label'>CVC</label> <input autocomplete='off'
                                    class='form-control card-cvc' placeholder='ex. 311' size='4'
                                    type='text'>
                            </div>
                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                <label class='control-label'>Expiration Month</label> <input
                                    class='form-control card-expiry-month' placeholder='MM' size='2'
                                    type='text'>
                            </div>
                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                <label class='control-label'>Expiration Year</label> <input
                                    class='form-control card-expiry-year' placeholder='YYYY' size='4'
                                    type='text'>
                            </div>
                        </div>
  
                        <div class='form-row row'>
                            <div class='col-md-12 error form-group hide'>
                                <div class='alert-danger alert'>Please correct the errors and try
                                    again.</div>
                            </div>
                        </div>
  
                        <div class="row">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now ($100)</button>
                            </div>
                        </div>
                          
                    </form>
                </div>
            </div>        
        </div>
    </div>
      
</div>
  
</body>
  
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
  
<script type="text/javascript">
$(function() {
   
    var $form         = $(".require-validation");
   
    $('form.require-validation').bind('submit', function(e) {
        var $form         = $(".require-validation"),
        inputSelector = ['input[type=email]', 'input[type=password]',
                         'input[type=text]', 'input[type=file]',
                         'textarea'].join(', '),
        $inputs       = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),
        valid         = true;
        $errorMessage.addClass('hide');
  
        $('.has-error').removeClass('has-error');
        $inputs.each(function(i, el) {
          var $input = $(el);
          if ($input.val() === '') {
            $input.parent().addClass('has-error');
            $errorMessage.removeClass('hide');
            e.preventDefault();
          }
        });
   
        if (!$form.data('cc-on-file')) {
          e.preventDefault();
          Stripe.setPublishableKey($form.data('stripe-publishable-key'));
          Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
          }, stripeResponseHandler);
        }
  
  });
  
  function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
        } else {
            /* token contains id, last4, and card type */
            var token = response['id'];
               
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }
   
});
</script>
</html> -->





<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Bancontact</title>

    <link  rel="stylesheet" type="text/css" href="css/base.css" />
    <script type="application/javascript" src="https://js.stripe.com/v3/"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script type="application/javascript" src="{{ URL::asset('/public/assets/js/utils.js') }}" defer></script> 
    <!-- <script type="application/javascript" src="{{ URL::asset('/public/assets/js/bancontact.js') }}" defer></script> -->







<script type="application/javascript" >
    
    
    
    document.addEventListener('DOMContentLoaded', async () => {
//   Load the publishable key from the server. The publishable key
//   is set in your .env file. In practice, most users hard code the
//   publishable key when initializing the Stripe object.
//   const {publishableKey} = await fetch('/config').then((r) => r.json());
//   if (!publishableKey) {
//     addMessage(
//       'No publishable key returned from the server. Please check `.env` and try again'
//     );
//     alert('Please set your Stripe publishable API key in the .env file');
//   }
alert("i");
const publishableKey = "pk_test_51L4oWyHxJxM7Rl8Eo9yLTiURnG1Uatv6bJqXXmU2P9Hm8InUaPmlnclrDEvSuOWEgPYCtAiUsuJIb6LAPGt4mcV900Omo6w7PX";
  const stripe = Stripe(publishableKey);

//   When the form is submitted...
  var form = document.getElementById('payment-form');
  form.addEventListener('submit', async (e) => {
    e.preventDefault();





//     const session = await stripe.create({
// //   payment_method_types: ['card'],
//   payment_method_types: ['card', 'bancontact'],
//   line_items: [{
//     price_data: {
//     //   currency: 'usd',
//     //   To accept `bancontact`, all line items must have currency: eur
//       currency: 'eur',
//       product_data: {
//         name: 'T-shirt',
//       },
//       unit_amount: 2000,
//     },
//     quantity: 1,
//   }],
//   mode: 'payment',
//   success_url: 'https://example.com/success',
//   cancel_url: 'https://example.com/cancel',
// });



// alert(session);














$.ajax({
  type: "POST",
  url: "{{ route('stripe.post') }}",
  data: "myusername",
  headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-Token": $('input[name="_token"]').val(),
        },
  cache: false,
  success: function(data){
    console.log(data.phone_number)
  },
            error: function (error) {
                alert(error);
            }
});




    // Make a call to the server to create a new
    // payment intent and store its client_secret.
//  const {error: backendError, clientSecret} = await fetch(
//    "{{ route('stripe.post') }}",
//       {     
//         method: 'POST',
//         credentials: "same-origin",
//         headers: {
//           "Content-Type": "application/json",
//           "Accept": "application/json",
//           "X-Requested-With": "XMLHttpRequest",
//           "X-CSRF-Token": $('input[name="_token"]').val(),
//         },
        
//         body: JSON.stringify({
//           currency: 'eur',
//           paymentMethodType: 'bancontact',
//         }),
//       }
//     ).then(res => res.text())          // convert to plain text
//   .then(text => console.log(text));
    // if (backendError) {
    //   addMessage(backendError.message);
    //   return;
    // }

    addMessage(`Client secret returned.`);

    const nameInput = document.querySelector('#name');

    // Confirm the card payment given the clientSecret
    // from the payment intent that was just created on
    // the server.
    // const {
    //   error: stripeError,
    //   paymentIntent,
    // } = await stripe.confirmBancontactPayment(clientSecret, {
    //   payment_method: {
    //     billing_details: {
    //       name: nameInput.value,
    //     },
    //   },
    //   return_url: `${window.location.origin}/return.html`,
    // });

    // if (stripeError) {
    //   addMessage(stripeError.message);
    // }

    // addMessage(`Payment ${paymentIntent.status}: ${paymentIntent.id}`);

    stripe.redirectToCheckout({
 sessionId: "8d2dbd96-d4dd-4d49-89d7-3659285cd7208c9507",
   });


  });
});
 
 
 
 </script>




  </head> 
  <body>
    <main>
      <a href="/">home</a>

      <h1>Bancontact</h1>

      <form id="payment-form">
        <label for="name">
          Name
        </label>
        <input id="name" value="Jenny Rosen" required />
        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
        <button type="submit">Pay</button>

     
       <div id="error-message" role="alert"></div>
      </form>

      <div id="messages" role="alert"></div>
    </main>

  </body>
</html> 


