@extends('layouts.app')

@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset('css/confetti.css') }}">
@endsection

@section('foot')
@include('partials.confetti.confetti_js')
@auth
<script type="text/javascript">
let has_shipping_info = `{!! \App\Models\ShippingInfo::where('user_id', auth()->user()->id)->where('default', true)->exists() ? "true" : "false" !!}`;

if(has_shipping_info == 'true') {
	has_shipping_info = true;
} else {
	has_shipping_info = false;
}

// let stripe = Stripe("{{ env('STRIPE_CLIENT_PUBLIC') }}");

function popupShippingInfo() {
	if(!has_shipping_info) {
		$('#shipping-info-btn').click();
		return;
	}
}
</script>

@include('partials.shipping_info_form_js')
@else
<script type="text/javascript">
	function checkout(rent) {
		$('#account-btn').click();
	}
</script>
@endauth

@endsection

@section('content')

@auth
	@if($product->active)
		@php
			$shipping_cost = $product->estimateShipping();
		@endphp
	@else
		@php
			$shipping_cost = null;
		@endphp
	@endif
@else
	@php
		$shipping_cost = null;
	@endphp
@endauth
<!-- Detail Product -->
<div class="hun-section-detail-product layout-1 show-space-bottom" style="padding-top: 6em;">
	<div class="inner-section">
		<div class="hun-element-detail-product--type-1">
			<div class="container">
				<div class="row">
					<div class="col-lg-6">
						<div class="images-product set-color">
							<div class="img-slider js-call-slick" data-custom-dots="true">
								<div class="slide-slick js-call-magnificpopup" data-slick='{"adaptiveHeight": true, "fade": true, "speed": 300, "dots": true, "arrows": false, "autoplay": false, "infinite": false, "slidesToShow": 1, "slidesToScroll": 1}' data-gallery="true" data-verticalfit="false">

									@foreach($product->images as $image_id)
										@if(\App\Models\File::where('id', $image_id)->exists())
											@php
												$url = \App\Models\File::where('id', $image_id)->first()->getURL();
											@endphp

											<div class="item-slick" data-inner-dot='<span class="inner-dot" class="img-dot" style="background-image: url({{ $url }})"></span>'>
												
												<div class="wrap-img">
													<img src="{{ $url }}" alt="IMG">

													@auth
														<a href="javascript:love('{{ $product->id }}');" style="margin-top: 1em;margin-right: 1em;" class="love-btn-feed @if(auth()->user()->lovedProduct($product->id)) loved @else unloved @endif">
															@if(auth()->user()->lovedProduct($product->id))
																<i class="fa fa-2x fa-heart" id="love-{{ $product->id }}"></i>
															@else
																<i class="fa fa-2x fa-heart-o" id="love-{{ $product->id }}"></i>
															@endif
														</a>
													@endauth
												</div>
											</div>
										@endif
									@endforeach
								</div>

								<div class="dots-slick"></div>
							</div>
						</div>
					</div>

					<div class="col-lg-6">
						
						<div class="text-product">
							<h3 class="name-product">
								{{ $product->title }}
							</h3>

							<!-- <div class="star-product">
								<span class="list-star">
									@php
										$rating = $product->getRating();
										$i = 0;
										$half_star = floor($rating) != round($rating);
									@endphp

									@for($i = 0; $i < floor($rating); $i+=1)
										<i class="fa fa-star fa-lg"></i>
									@endfor
									
									@if($half_star)
										@php
											$i += 1;
										@endphp
										<i class="fa fa-star-half-full fa-lg"></i>
									@endif

									@for($z = $i; $z < 5; $z+=1)
										<i class="fa fa-star-o fa-lg"></i>
									@endfor
								</span>

								({{ \App\Models\ProductReview::where('product_id', $product->id)->count() }} customer reviews)
							</div> -->

							<div class="price-product">
								@if($product->purchase_price != null)
									<span class="new-price">
									Buy for ${{ number_format(($product->purchase_price / 100), 2, '.', ',') }}
									</span>
								@endif

								@if($product->rental_price != null)
									@if($product->purchase_price != null)
									<br />
									<span>
									@else
									<span class="new-price">
									@endif
									Rent for ${{ number_format(($product->rental_price / 100), 2, '.', ',') }}
									</span>
								@endif

								
								@if($product->purchase_price != null)
									<br />
									<br />
									<span class="old-price" style="bottom: 0;right: 0;">
										${{ number_format(($product->original_price / 100), 2, '.', ',') }} retail
									</span>
									<span class="old-price" style="bottom: 0;right: 0;text-decoration: none;">
										({{ round(($product->purchase_price / $product->original_price) * 100) }} % off)
									</span>
								@endif
							</div>

							<div class="description-product">
								<p>
									{{ $product->description }} 
								</p>

							</div>

							<div class="description-product">
							@php
								$seller = \App\Models\User::where('id', $product->user_id)->first();
								$closet = \App\Models\Closet::where('user_id', $product->user_id)->first();
							@endphp
								<div class="row" style="margin-left: 0em;">
									<div style="col-2">
										<img class="avatar-md" style="margin-top: 0.4em;" src="{{ $seller->getAvatar() }}">
									</div>
									<div class="col-10" style="margin-left: 1em;margin-top: 1em;">
										<div class="row">
											<a href="/closet?id={{ $closet->id }}">{{ $seller->username }}</a>
										</div>
									</div>
								</div>
							</div>

							<form class="cart-form">

								<div class="quantity-and-addcart set-color">
									<div class="container">
									@auth
										@php
											$has_shipping_info = \App\Models\ShippingInfo::where('user_id', auth()->user()->id)->where('default', true)->exists();
										@endphp
									@else
										@php
											$has_shipping_info = false;
										@endphp
									@endauth

										<div class="row" style="margin-left: -0.5em;">
										@auth
											@if($has_shipping_info)
												@if($shipping_cost !== null)
													<small><b>${{ $shipping_cost }} shipping</b> via USPS Parcel Select Ground (1-3 days)</small>
													<br />
													<small>We ask sellers to ship within 1-2 days of your order. If you need it by a specific date, message the seller and ask when they can ship it.</small>
												@endif
											@endif
										@endauth
										</div>
										
										<div class="row" style="margin-top: 2em;">
											@if($product->active)
												@if($product->purchase_price != null)
												<div class="col-8">
													@auth
														<a href="@if($has_shipping_info) {{ \App\PayPalHelper::setupCheckout($product, false) }} @else javascript:popupShippingInfo(); @endif" class="btn-clean w-100">
															Buy
														</a>
													@else
														<a href="javascript:love(0)" class="btn-clean w-100">
															Buy
														</a>
													@endauth
												</div>
												@endif

												@if($product->rental_price != null)
												<div class="col-4">
													@auth
														<a href="@if($has_shipping_info) {{ \App\PayPalHelper::setupCheckout($product, true) }} @else javascript:popupShippingInfo(); @endif" class="btn-clean w-100">
															Rent
														</a>
													@else
														<a href="javascript:love(0)" class="btn-clean w-100">
															Rent
														</a>
													@endauth
												</div>
												@endif
											@else
												<button type="button" class="btn-clean @if($product->isRented()) btn-orange @else btn-red @endif w-100">
													@if($product->isRented())
														RENTED
													@else
														SOLD
													@endif
												</button>
											@endif
										</div>
									</div>
								</div>								
							</form>

							<ul class="meta-info set-color">
								<li>
									<span class="name-info">Categories:</span> 
									
									<a href="/shop?category={{ \App\Models\Category::where('id', $product->category)->first()->id }}">{{ \App\Models\Category::where('id', $product->category)->first()->name }}</a>
									@if($product->subcategory != null)
									, <a href="/shop?category={{ \App\Models\Category::where('id', $product->subcategory)->first()->id }}">{{ \App\Models\Category::where('id', $product->subcategory)->first()->name }}</a>
									@endif
								</li>
							</ul>
							
							<div class="row" style="margin-top: 2em;">
								<div class="col">
									<img src="{{ asset('images/icons/secure_payment.png') }}" style="max-height: 72px;" alt="IMG-PAYPAL">
								</div>
						</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<!-- end Detail Product -->

<div class="hun-section-products layout-slider js-call-magnificpopup hide-link-view-all outer-hun-element-product--type-1 price-color ratio-img-3-4 show-space-bottom hidden" hidden>
	<div class="inner-section">
		<div class="container">
			<div class="slide-section js-call-slick">
				<div class="slide-slick" data-slick='{"dots": false, "arrows": true, "autoplay": false, "infinite": false, "slidesToShow": 4, "slidesToScroll": 4, "responsive": [{"breakpoint": 1199, "settings": {"slidesToShow": 3, "slidesToScroll": 3}}, {"breakpoint": 991, "settings": {"slidesToShow": 2, "slidesToScroll": 2}}, {"breakpoint": 767, "settings": {"slidesToShow": 1, "slidesToScroll": 1}}, {"breakpoint": 575, "settings": {"slidesToShow": 1, "slidesToScroll": 1}}]}'>

					<div class="item-slick">
						<div class="hun-element-product--type-1">
							<div class="buttons-product">
								<a class="btn-quickview set-color mfp-inline js-open-popup hidden" id="shipping-info-btn" href="#shipping-popup" title="Quick View" hidden></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@auth
<!-- Quickview Popup -->
@if(\App\Models\ShippingInfo::where('user_id', auth()->user()->id)->exists())
<div id="shipping-popup" class="hun-element-quickview-popup--type-1 mfp-hide">
	<div class="hun-element-detail-product--type-1">
		<div class="container">
			<div class="row">

				<div class="col-md-5 col-lg-6">
					
				</div>

				<div class="col-md-7 col-lg-6">

				</div>

			</div>
		</div>
	</div>
</div>
@else
<div id="shipping-popup" class="hun-element-quickview-popup--type-1 mfp-hide">
	<div class="hun-element-detail-product--type-1">
		<div class="container">
			<div class="row justify-content-center">

				<div class="col-md-5 col-lg-6">
					<h6 class="text-center" style="font-size: 2em;">Add New Shipping Address</h6>
					@include('partials.shipping_info_form_html')
				</div>

			</div>
		</div>
	</div>
</div>
@endif
<!-- end Quickview Popup -->
@endauth
@endsection


