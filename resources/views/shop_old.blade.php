@extends('layouts.app')

@section('content')

<!-- Page Title -->
<div class="hun-section-page-title layout-1 set-color text-center off-parallax show-bg-img show-overlay-color show-space-bottom">
	<div class="inner-section">
		<div class="bg-section parallax100"><span class="inner-parallax" style="background-image: url(images/shop-bg.png);"></span></div>

		<div class="container">
			<h2 class="main-title">
				@if($category->parent_category != null)
					@if(\App\Models\Category::where('id', $category->parent_category)->first())
						{{ \App\Models\Category::where('id', $category->parent_category)->first()->name }} / {{ $category->name }}
					@else
						{{ $category->name }}
					@endif
				@else
					{{ $category->name }}
				@endif
			</h2>
		</div>
	</div>
</div>
<!-- end Page Title -->

<!-- All Products -->
<div class="hun-section-all-products layout-2 js-call-magnificpopup outer-hun-element-product--type-1 price-color ratio-img-3-4 show-space-bottom">
	<div class="inner-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-9 order-lg-2">
					<div class="content-sec">
						<div class="tool-products set-color">
							<div class="sort-product">
								<label class="wrap-select">
									<select class="select-field">
										<option>Sort By Default</option>
										<option>Sort By Price</option>
										<option>Sort By Name</option>
										<option>Sort By Date</option>
									</select>
								</label>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 col-xl-4">
								<div class="hun-element-product--type-1">
									<div class="labels-product set-color">
										<span class="item-label label-new">New</span>
									</div>

									<a href="detail-product1.html" class="pic-product">
										<span class="gallery-product">
											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-04-1.jpg);"></span>
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-04-2.jpg);"></span>
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-04-3.jpg);"></span>
											</span>
										</span>
									</a>

									<div class="text-product">
										<h6 class="name-product set-color">
											<a href="detail-product1.html">
												Black Ribbed Bodysuit
											</a>
										</h6>

										<div class="price-product set-color">
											<span class="new-price">
												$32.65
											</span>
										</div>

										<div class="star-product">
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star-half-full"></i>
										</div>
									</div>

									<div class="buttons-product">
										<a href="#" class="btn-addcart set-color">
											Add To Cart +
										</a>

										<a class="btn-quickview set-color mfp-inline js-open-popup" href="#quickview-popup-01" title="Quick View">
											<i class="icon-eye"></i>
										</a>

										<a href="#" class="btn-addwish set-color" title="Add To Wishlist">
											<i class="fa fa-heart-o"></i>
										</a>
									</div>
								</div>
							</div>

							<div class="col-md-6 col-xl-4">
								<div class="hun-element-product--type-1">
									<a href="detail-product1.html" class="pic-product">
										<span class="gallery-product">
											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-06-1.jpg);"></span>
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-06-2.jpg);"></span>
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-06-3.jpg);"></span>
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-06-4.jpg);"></span>
											</span>
										</span>
									</a>

									<div class="text-product">
										<h6 class="name-product set-color">
											<a href="detail-product1.html">
												Neck Tie Satin Blouse
											</a>
										</h6>

										<div class="price-product set-color">
											<span class="new-price">
												$32.65
											</span>
										</div>

										<div class="star-product">
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star-half-full"></i>
										</div>
									</div>

									<div class="buttons-product">
										<a href="#" class="btn-addcart set-color">
											Add To Cart +
										</a>

										<a class="btn-quickview set-color mfp-inline js-open-popup" href="#quickview-popup-01" title="Quick View">
											<i class="icon-eye"></i>
										</a>

										<a href="#" class="btn-addwish set-color" title="Add To Wishlist">
											<i class="fa fa-heart-o"></i>
										</a>
									</div>
								</div>
							</div>

							<div class="col-md-6 col-xl-4">
								<div class="hun-element-product--type-1">
									<div class="labels-product set-color">
										<span class="item-label label-sale">Sale</span>
									</div>

									<a href="detail-product1.html" class="pic-product">
										<span class="gallery-product">
											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-05-3.jpg);"></span>
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-05-2.jpg);"></span>
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-05-1.jpg);"></span>
											</span>
										</span>
									</a>

									<div class="text-product">
										<h6 class="name-product set-color">
											<a href="detail-product1.html">
												Black Blouse
											</a>
										</h6>

										<div class="price-product set-color">
											<span class="new-price">
												$32.65
											</span>

											<span class="old-price">
												$23.65
											</span>
										</div>

										<div class="star-product">
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star-half-full"></i>
										</div>
									</div>

									<div class="buttons-product">
										<a href="#" class="btn-addcart set-color">
											Add To Cart +
										</a>

										<a class="btn-quickview set-color mfp-inline js-open-popup" href="#quickview-popup-01" title="Quick View">
											<i class="icon-eye"></i>
										</a>

										<a href="#" class="btn-addwish set-color" title="Add To Wishlist">
											<i class="fa fa-heart-o"></i>
										</a>
									</div>
								</div>
							</div>

							<div class="col-md-6 col-xl-4">
								<div class="hun-element-product--type-1">
									<a href="detail-product1.html" class="pic-product">
										<span class="gallery-product">
											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-21-1.jpg);"></span>	
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-21-2.jpg);"></span>
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-21-3.jpg);"></span>
											</span>
										</span>
									</a>

									<div class="text-product">
										<h6 class="name-product set-color">
											<a href="detail-product1.html">
												Pocket Detailed T-Shirt
											</a>
										</h6>

										<div class="price-product set-color">
											<span class="new-price">
												$32.65
											</span>
										</div>

										<div class="star-product">
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star-half-full"></i>
										</div>
									</div>

									<div class="buttons-product">
										<a href="#" class="btn-addcart set-color">
											Add To Cart +
										</a>

										<a class="btn-quickview set-color mfp-inline js-open-popup" href="#quickview-popup-01" title="Quick View">
											<i class="icon-eye"></i>
										</a>

										<a href="#" class="btn-addwish set-color" title="Add To Wishlist">
											<i class="fa fa-heart-o"></i>
										</a>
									</div>
								</div>
							</div>

							<div class="col-md-6 col-xl-4">
								<div class="hun-element-product--type-1">
									<a href="detail-product1.html" class="pic-product">
										<span class="gallery-product">
											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-10-1.jpg);"></span>	
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-10-2.jpg);"></span>
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-10-3.jpg);"></span>
											</span>
										</span>
									</a>

									<div class="text-product">
										<h6 class="name-product set-color">
											<a href="detail-product1.html">
												Navy Fur Coat
											</a>
										</h6>

										<div class="price-product set-color">
											<span class="new-price">
												$32.65
											</span>
										</div>

										<div class="star-product">
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star-half-full"></i>
										</div>
									</div>

									<div class="buttons-product">
										<a href="#" class="btn-addcart set-color">
											Add To Cart +
										</a>

										<a class="btn-quickview set-color mfp-inline js-open-popup" href="#quickview-popup-01" title="Quick View">
											<i class="icon-eye"></i>
										</a>

										<a href="#" class="btn-addwish set-color" title="Add To Wishlist">
											<i class="fa fa-heart-o"></i>
										</a>
									</div>
								</div>
							</div>

							<div class="col-md-6 col-xl-4">
								<div class="hun-element-product--type-1">
									<a href="detail-product1.html" class="pic-product">
										<span class="gallery-product">
											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-16-1.jpg);"></span>	
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-16-2.jpg);"></span>
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-16-3.jpg);"></span>
											</span>
										</span>
									</a>

									<div class="text-product">
										<h6 class="name-product set-color">
											<a href="detail-product1.html">
												Navy Long Sleeve Shirt
											</a>
										</h6>

										<div class="price-product set-color">
											<span class="new-price">
												$32.65
											</span>
										</div>

										<div class="star-product">
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star-half-full"></i>
										</div>
									</div>

									<div class="buttons-product">
										<a href="#" class="btn-addcart set-color">
											Add To Cart +
										</a>

										<a class="btn-quickview set-color mfp-inline js-open-popup" href="#quickview-popup-01" title="Quick View">
											<i class="icon-eye"></i>
										</a>

										<a href="#" class="btn-addwish set-color" title="Add To Wishlist">
											<i class="fa fa-heart-o"></i>
										</a>
									</div>
								</div>
							</div>

							<div class="col-md-6 col-xl-4">
								<div class="hun-element-product--type-1">
									<a href="detail-product1.html" class="pic-product">
										<span class="gallery-product">
											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-07-1.jpg);"></span>
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-07-2.jpg);"></span>
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-07-3.jpg);"></span>
											</span>
										</span>
									</a>

									<div class="text-product">
										<h6 class="name-product set-color">
											<a href="detail-product1.html">
												Black Boots
											</a>
										</h6>

										<div class="price-product set-color">
											<span class="new-price">
												$32.65
											</span>
										</div>

										<div class="star-product">
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star-half-full"></i>
										</div>
									</div>

									<div class="buttons-product">
										<a href="#" class="btn-addcart set-color">
											Add To Cart +
										</a>

										<a class="btn-quickview set-color mfp-inline js-open-popup" href="#quickview-popup-01" title="Quick View">
											<i class="icon-eye"></i>
										</a>

										<a href="#" class="btn-addwish set-color" title="Add To Wishlist">
											<i class="fa fa-heart-o"></i>
										</a>
									</div>
								</div>
							</div>

							<div class="col-md-6 col-xl-4">
								<div class="hun-element-product--type-1">
									<a href="detail-product1.html" class="pic-product">
										<span class="gallery-product">
											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-13-2.jpg);"></span>	
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-13-1.jpg);"></span>
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-13-3.jpg);"></span>
											</span>
										</span>
									</a>

									<div class="text-product">
										<h6 class="name-product set-color">
											<a href="detail-product1.html">
												Black Lace On Shoes
											</a>
										</h6>

										<div class="price-product set-color">
											<span class="new-price">
												$32.65
											</span>
										</div>

										<div class="star-product">
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star-half-full"></i>
										</div>
									</div>

									<div class="buttons-product">
										<a href="#" class="btn-addcart set-color">
											Add To Cart +
										</a>

										<a class="btn-quickview set-color mfp-inline js-open-popup" href="#quickview-popup-01" title="Quick View">
											<i class="icon-eye"></i>
										</a>

										<a href="#" class="btn-addwish set-color" title="Add To Wishlist">
											<i class="fa fa-heart-o"></i>
										</a>
									</div>
								</div>
							</div>

							<div class="col-md-6 col-xl-4">
								<div class="hun-element-product--type-1">
									<a href="detail-product1.html" class="pic-product">
										<span class="gallery-product">
											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-22-2.jpg);"></span>	
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-22-1.jpg);"></span>
											</span>

											<span class="item-gal">
												<span class="image-gal" style="background-image: url(images/p-22-3.jpg);"></span>
											</span>
										</span>
									</a>

									<div class="text-product">
										<h6 class="name-product set-color">
											<a href="detail-product1.html">
												Slip On Boots
											</a>
										</h6>

										<div class="price-product set-color">
											<span class="new-price">
												$32.65
											</span>
										</div>

										<div class="star-product">
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star-half-full"></i>
										</div>
									</div>

									<div class="buttons-product">
										<a href="#" class="btn-addcart set-color">
											Add To Cart +
										</a>

										<a class="btn-quickview set-color mfp-inline js-open-popup" href="#quickview-popup-01" title="Quick View">
											<i class="icon-eye"></i>
										</a>

										<a href="#" class="btn-addwish set-color" title="Add To Wishlist">
											<i class="fa fa-heart-o"></i>
										</a>
									</div>
								</div>
							</div>
						</div>

						<div class="link-view-all">
							<a href="#" class="set-color">Load More</a>
						</div>
					</div>
				</div>

				<div class="col-lg-3 order-lg-1 js-call-sticky-sidebar">
					<div class="sidebar-sec">
						<div class="search-product set-color">
							<form class="form-search">
								<label>
									<input type="text" placeholder="Search Product...">
								</label>

								<button>
									<i class="icon ion-android-search"></i>
								</button>
							</form>
						</div>

						<div class="filter-product set-color">
							<h3 class="title-filter">
								Filter By
							</h3>

							<div class="options-filter">

								<div class="group-options">
									<h6 class="title-group">
										<a href="#">Type</a>
									</h6>

									<div class="list-options">

										@foreach($types as $type)
										<label class="item-option">
											<input type="checkbox">
											<span class="icon-checkbox"></span>
											{{ $type->name }}
										</label>
										@endforeach
									</div>
								</div>

								<div class="group-options">
									<h6 class="title-group">
										<a href="#">Size</a>
									</h6>

									<div class="list-options">

										@foreach($sizes as $size)
										<label class="item-option">
											<input type="checkbox">
											<span class="icon-checkbox"></span>
											{{ $size->name }}
										</label>
										@endforeach
									</div>
								</div>

							</div>

							<div class="buttons-filter">
								<button class="btn-apply-filter">
									Apply Filter
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end All Products -->
@endsection
