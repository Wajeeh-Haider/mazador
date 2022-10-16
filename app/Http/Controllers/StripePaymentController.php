<?php
   
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use Session;

   
class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        return view('stripe');
    }
    public function paymentemail(Request $request) { 

        // try{
        //     $ordermessage='Payment Successful';
        //     $email="afaq37447@gmail.com";
        //     $name="afaq";
        //     $data=['ordermessage'=>$ordermessage,'email'=>$email,'name'=>$name];

        //     Mail::send('Email.orderemail',$data,function($message)use($data){
        //         $message->from(env('MAIL_USERNAME'))->subject($data['ordermessage']);
        //         $message->to($data['email']);
        //     } );
            
        // }catch(\Swift_TransportException $e){
        //     $response = $e->getMessage() ;
        //     return response()->json(['status'=>0,'message'=>'Something went wrong while sending email Please try again...'],200);
        // } 

       
        
        return response()->json(['status'=>0,'message'=>'Something went wrong while sending email Please try again...'],200);
         }
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request) {
      
      
      
      // var_dump("okokokoko");die();
        // Stripe\Stripe::setApiKey('pk_test_51L4oWyHxJxM7Rl8Eo9yLTiURnG1Uatv6bJqXXmU2P9Hm8InUaPmlnclrDEvSuOWEgPYCtAiUsuJIb6LAPGt4mcV900Omo6w7PX');


       

    
        // header('Content-Type: application/json');
        
        // $YOUR_DOMAIN = 'http://localhost:4242/public';
        
        // $checkout_session = \Stripe\Checkout\Session::create([
        //   'line_items' => [[
        //     # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
        //     'price' => '{{PRICE_ID}}',
        //     'quantity' => 1,
        //   ]],
        //   'mode' => 'payment',
        //   'success_url' => $YOUR_DOMAIN . '/success.html',
        //   'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        //   'automatic_tax' => [
        //     'enabled' => true,
        //   ],
        // ]);
        
        // header("HTTP/1.1 303 See Other");
        // header("Location: " . $checkout_session->url);


        // $session = \Stripe\Checkout\Session::create([
        //     // 'payment_method_types' => ['card'],
        //     'payment_method_types' => ['card', 'bancontact'],
        //     'line_items' => [[
        //       'price_data' => [
        //         // 'currency' => 'usd',
        //         # To accept `bancontact`, all line items must have currency: eur
        //         'currency' => 'eur',
        //         'product_data' => [
        //           'name' => 'T-shirt',
        //         ],
        //         'unit_amount' => 2000,
        //       ],
        //       'quantity' => 1,
        //     ]],
        //     'mode' => 'payment',
        //     'success_url' => 'https://mazadorantwerp.com',
        //     'cancel_url' => 'https://mazadorantwerp.com/stripe',
        //   ]);


        // // Session::flash('success', 'Payment successful!');
          
        // // return 


     $ok =   json_encode(array('phone_number' => '+33123456789'), JSON_NUMERIC_CHECK);

return $ok;











      
        // Stripe\Charge::create ([
        //         "amount" => 100 * 100,
        //         "currency" => "usd",
        //         "source" => $request->stripeToken,
        //         "description" => "Test payment from itsolutionstuff.com." 
        // ]);
  
        // Session::flash('success', 'Payment successful!');
          
        // return back();
    }
}