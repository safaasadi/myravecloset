@extends('layouts.app')

@section('content')

<!-- Main Slider -->
<div class="hun-section-mainslider layout-1 low-height show-small-space-bottom">
		<div class="inner-section">
			<div class="main-slider set-color js-call-slick" data-animate="true">
				<div class="slide-slick" data-slick='{"fade": true, "speed": 900, "dots": true, "arrows": true, "autoplay": false, "autoplaySpeed": 5000, "infinite": true, "slidesToShow": 1, "slidesToScroll": 1}'>

					<div class="item-slick full-height-with-header" style="background-image: url(images/homepage.jpg)">
						<div class="content-slide">
							<div class="subtitle-slide" data-appear="fadeInDown" data-delay="0">
								BUY, <b>RENT</b>, SELL
							</div>

							<h2 class="title-slide" data-appear="fadeInUp" data-delay="800">
								FESTIVAL CLOTHES
							</h2>

							<div class="row">
								<div class="col-sm-6">
									<div class="buttons-slide" data-appear="zoomIn" data-delay="1600">
										<a href="/shop" class="btn-slide">
											Buy / Rent
										</a>
									</div>
								</div>
								<div class="col-sm-6 home-sell-div">
									<div class="buttons-slide" data-appear="zoomIn" data-delay="1600">
										<a href="/create-listing" class="btn-slide1">
											Sell
										</a>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end Main Slider -->

	<!-- Products -->
	<div class="hun-section-products layout-tab js-call-magnificpopup outer-hun-element-product--type-1 price-color ratio-img-3-4 show-space-bottom">
		<div class="inner-section">
			<div class="container">
				<div class="heading-section text-center">
					<h3 class="title-sec">
						Product Overview
					</h3>
				</div>

				<div class="tab-section set-color js-call-slick" data-custom-dots="true">
					<div class="dots-slick"></div>

					<div class="slide-slick" data-slick='{"adaptiveHeight": true, "fade": true, "speed": 300, "dots": true, "arrows": false, "autoplay": false, "autoplaySpeed": 5000, "infinite": false, "slidesToShow": 1, "slidesToScroll": 1}'>
						<div class="item-slick" data-inner-dot='Featured'>
							<div class="row">
							@foreach(\App\Models\Product::getCollection(null, 0, false) as $product)

								{!! $product->getHTML() !!}

							@endforeach
							</div>
						</div>

						<div class="item-slick" data-inner-dot='Sets'>
							<div class="row">
							@foreach(\App\Models\Product::getCollection(3, 0, false) as $product)

								{!! $product->getHTML() !!}

							@endforeach
							</div>
						</div>

						<div class="item-slick" data-inner-dot='Tops'>
							<div class="row">
							@foreach(\App\Models\Product::getCollection(1, 0, false) as $product)

								{!! $product->getHTML() !!}

							@endforeach
							</div>
						</div>

						<div class="item-slick" data-inner-dot='Shoes'>
							<div class="row">
							@foreach(\App\Models\Product::getCollection(11, 0, false) as $product)

								{!! $product->getHTML() !!}

							@endforeach
							</div>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</div>
	<!-- end Products -->

	<!-- Box Info -->
	<!-- <div class="hun-section-box-info layout-1 full-width show-small-space-bottom">
		<div class="inner-section">
			<div class="container">
				<div class="row">
					<div class="col-md-4">
						<div class="item-box set-color">
							<h4 class="title-box">
								Free Delivery Worldwide
							</h4>

							<div class="description-box">
									<a href="#">Click here for more info</a>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="item-box set-color">
							<h4 class="title-box">
								30 Days Return
							</h4>

							<div class="description-box">
								Simply return it within 30 days for an exchange.
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="item-box set-color">
							<h4 class="title-box">
								Store Opening
							</h4>

							<div class="description-box">
								Shop open from Monday to Sunday.
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> -->
	<!-- end Box Info -->

@endsection
