<!DOCTYPE html>

<html>



<head>

	<title>{{$getabout->title}}</title>



	<!-- meta tag -->

	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">



	<meta property="og:title" content="Mazador" />

	<meta property="og:description" content="" />

	<meta property="og:image" content='{!! asset("public/front/images/banner.png") !!}' />


	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- favicon-icon  -->

	<link rel="icon" href='{!! asset("public/images/about/".$getabout->favicon) !!}' type="image/x-icon">



	<!-- font-awsome css  -->

	<link rel="stylesheet" type="text/css" href="{!! asset('public/front/css/font-awsome.css') !!}">



	<!-- fonts css -->

	<link rel="stylesheet" type="text/css" href="{!! asset('public/front/fonts/fonts.css') !!}">



	<!-- bootstrap css -->

	<link rel="stylesheet" type="text/css" href="{!! asset('public/front/css/bootstrap.min.css') !!}">



	<!-- fancybox css -->

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />



	<!-- owl.carousel css -->

	<link rel="stylesheet" type="text/css" href="{!! asset('public/front/css/owl.carousel.min.css') !!}">



	<link href="{!! asset('public/assets/plugins/sweetalert/css/sweetalert.css') !!}" rel="stylesheet">

	<!-- style css  -->

	<link rel="stylesheet" type="text/css" href="{!! asset('public/front/css/style.css') !!}">



	<!-- responsive css  -->

	<link rel="stylesheet" type="text/css" href="{!! asset('public/front/css/responsive.css') !!}">

<!-- <script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&language=nl&output=json&key=AIzaSyAl3ANRDBxB8qaF385FCIk4bQeHhYCcEjw" async defer></script> -->


</head>



<body>



	<!--*******************

	    Preloader start

	********************-->

	<div id="preloader" style="display: none;">

	    <div class="loader">

	        <img src="{!! asset('public/front/images/loader.png') !!}">

	    </div>

	</div>

	<!--*******************

	    Preloader end

	********************-->



	<!-- navbar -->

	<header>

		<nav class="navbar navbar-expand-lg">

			<div class="container">

				<a class="navbar-brand" href="{{URL::to('/')}}"><img src='{!! asset("public/images/about/".$getabout->logo) !!}' alt=""></a>

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">

					<div class="menu-icon">

						<div class="bar1"></div>

						<div class="bar2"></div>

						<div class="bar3"></div>

					</div>

				</button>

				<div class="collapse navbar-collapse justify-content-end" id="navbarNav">

					<ul class="navbar-nav">

						<li class="nav-item {{ request()->is('/') ? 'active' : '' }}">

							<a class="nav-link" href="{{URL::to('/')}}">Home</a>

						</li>

						<li class="nav-item {{ request()->is('product') ? 'active' : '' }}">

							<a class="nav-link" href="{{URL::to('/product/2')}}">Our Menu</a>

						</li>

						@if (Session::get('id'))

						

							<li class="nav-item {{ request()->is('orders') ? 'active' : '' }}">

								<a class="nav-link" href="{{URL::to('/orders')}}">My Orders</a>

							</li>

							<!-- <li class="nav-item {{ request()->is('wallet') ? 'active' : '' }}">

								<a class="nav-link" href="{{URL::to('/wallet')}}">My Wallet</a>

							</li> -->

							<li class="nav-item search">

								<form method="get" action="{{URL::to('/search')}}">

									<div class="search-input">

										<input type="search" name="item" placeholder="Search here" required="">

									</div>

									<button type="submit" class="nav-link"><i class="far fa-search"></i></button>

								</form>

							</li>

							<li class="nav-item cart-btn">

								<a class="nav-link" href="{{URL::to('/cart')}}"><i class="fas fa-shopping-cart fa-lg"></i><span id="cartcnt">{{Session::get('cart')}}</span></a>

							</li>

						@else 

							<li class="nav-item search">

								<form method="get" action="{{URL::to('/search')}}">

									<div class="search-input">

										<input type="search" name="item" placeholder="Search here" required="">

									</div>

									<button type="submit" class="nav-link"><i class="far fa-search"></i></button>

								</form>

							</li>

							<li class="nav-item cart-btn">

								<a class="nav-link" href="{{URL::to('/signin')}}"><i class="fas fa-shopping-cart fa-lg"></i></a>

							</li>

						@endif

						@if (Session::get('id'))

							<li class="nav-item dropdown">

								<a class="nav-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="javascript:void(0)">

									<img src='{!! asset("public/images/profile/".Session::get("profile_image")) !!}' alt="">

								</a>

								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

									<a class="dropdown-item" href="/">Hi, {{Session::get('name')}}</a>

									<a class="dropdown-item" href="{{URL::to('/favorite')}}">Favourite List</a>

									<a class="dropdown-item" href="" data-toggle="modal" data-target="#AddReview">Add Review</a>

									<!-- <a class="dropdown-item" href="" data-toggle="modal" data-target="#Refer">Refer and Earn</a> -->

									<a class="dropdown-item" href="" data-toggle="modal" data-target="#ChangePasswordModal">Change Password</a>

									<a class="dropdown-item" href="{{URL::to('/logout')}}">Logout</a>

								</div>

							</li>

						@else 

							<li class="nav-item">
								<a class="nav-link btn btn-sm sign-btn w-75 mt-2 text-center" href="{{URL::to('/signin')}}">Login</a>
							</li>
							<li class="nav-item">
								<a class="nav-link btn btn-sm sign-btn w-75 mt-2 text-center" href="{{URL::to('/signup')}}">Signup</a>
							</li>

						@endif

						

					</ul>

				</div>

			</div>

		</nav>

	</header>

	<!-- navbar -->

	<div id="success-msg" class="alert alert-dismissible mt-3" style="display: none;">

	    <span id="msg"></span>

	</div>



	<div id="error-msg" class="alert alert-dismissible mt-3" style="display: none;">

	    <span id="ermsg"></span>

	</div>



	@include('cookieConsent::index')