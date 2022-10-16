@include('front.theme.header')>
<style>
    .order-box:hover {
        box-shadow: 0 0 10px 1px rgba(168,0,19,0.7);
        transition: .3s; 
    }
</style>
@if(Session::has('success'))
    <div class="alert alert-success"> {{ Session::get('success') }}</div>
@endif
<section class="favourite">
    <div class="container">
        <h2 class="sec-head">My Orders</h2>
        <script>
    function send_email(vare) {
      
        data = vare;
            $.ajax({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                },

                url:"{{ URL::to('/getmail/0') }}",

                data: JSON.stringify(data),
                datatype: 'JSON',
                method: 'GET',
                contentType: 'application/json',
                success: function(response) {

                  

                    swal({
                            title: "Payment!",
                            text: "Payment Failed.",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "Ok",
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true,
                        },
                        function(isConfirm) {
                            if (isConfirm) {
                                swal.close();
                                window.location = "https://mazadorantwerp.com/orders";
                            }else{
                                window.location = "https://mazadorantwerp.com/orders";
                            }
                        });

                  
               
                    
                },

                error: function(e) {

                    window.location = "https://mazadorantwerp.com/orders";

                }

            });
        }
            </script>
        <?php
        //  $ahah_first = DB::table('order')->latest('created_at')->first();
        //  DB::table('order')->where('id', $ahah_first->id)->delete();
        if(@$_GET['status'] === '0'){
            // DB::table('order')->latest('created_at')->first()->delete();
            $ahah_first = DB::table('order')->latest('created_at')->first();
            if($ahah_first){
            DB::table('order')->where('id', $ahah_first->id)->delete();
        }
            echo "<script>send_email(0);</script>";
        





    //         echo '<script type="text/javascript">
    //         window.location = "https://mazadorantwerp.com/orders"
    //     </script>';
        }else{


        }
        
        
        
      
        
        ?>






        <div class="row">
            @if (count($orderdata) == 0) 
                <p>No Orders Yet</p>
            @else 
                @foreach ($orderdata as $orders)
                <div class="col-lg-4">
                    <a href="{{URL::to('order-details/'.$orders->id)}}" class="order-box">
                        <div class="order-box-no">
                            {{$orders->date}}
                            <h4>Order ID : <span>{{$orders->order_number}}</span></h4>
                            <span style="color: #fe734c; font-weight: 400">
                                @if($orders->payment_type == 1)
                                    Razorpay Payment
                                @elseif($orders->payment_type == 2)
                                    Stripe Payment
                                @elseif($orders->payment_type == 3)
                                    Stripe Payment
                                @elseif($orders->payment_type == 4)
                                    Bancontact
                                @else
                                    Cash Payment
                                @endif
                            </span>
                            @if($orders->status == 1)
                                <p class="order-status">Order Status : <span>Order Placed</span></p>
                            @elseif($orders->status == 2)
                                <p class="order-status">Order Status : <span>Order Ready</span></p>
                            @elseif($orders->status == 3)
                                <p class="order-status">Order Status : <span>Order on the way</span></p>
                            @elseif($orders['status'] == 5)
                                <p class="order-status">Order Status : <span>Order Cancelled by You</span></p>
                            @elseif($orders['status'] == 6)
                                <p class="order-status">Order Status : <span>Order Cancelled by Admin</span></p>
                            @else
                                <p class="order-status">Order Status : <span>Order Delivered</span></p>
                            @endif
                        </div>
                        <div class="order-box-price">
                            <h5>{{$getdata->currency}}{{number_format($orders->total_price, 2)}}</h5>
                            @if($orders->order_type == 1)
                                Delivery
                            @else
                                Pickup
                            @endif
                        </div>
                    </a>
                </div>
                @endforeach
            @endif
        </div>
        {!! $orderdata->links() !!}
    </div>
</section>

@include('front.theme.footer')