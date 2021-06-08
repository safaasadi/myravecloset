@extends('layouts.app')

@section('head')
<style>
.star-rating {
  color: #999;
  margin-bottom: 15px;
}
.star-rating .list-star {
  color: #ffb14b;
  display: inline-block;
  margin-right: 5px;
}
</style>
@endsection

@section('content')

<!-- All Products -->
<div class="hun-section-all-products layout-2 js-call-magnificpopup outer-hun-element-product--type-1 price-color ratio-img-3-4 show-space-bottom pt-5">
	<div class="inner-section">
		<div class="container">
			<div class="row">


				<div class="col-sm-3">
					<div class="text-center" style="padding-bottom: 1em;">
						<img class="avatar-xxl" src="{{ $closet->getOwner()->getAvatar() }}">
					</div>
					<h1 class="text-center">{{ $closet->getOwner()->username }}</h1>
					<div class="container">
						<div class="row">
							<div class="col">
								<div class="row justify-content-center">
									<div class="star-rating">
										<span class="list-star">
											@php
												$rating = \App\Models\User::where('id', $closet->getOwner()->id)->first()->getRating();
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
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col text-center">
								<h5 style="margin-bottom: -0.5em;">{{ \App\Models\Order::where('seller_id', $closet->getOwner()->id)->count() }}</h5>
								<small>Sold</small>
							</div>
							<div class="col text-center">
								<h5 style="margin-bottom: -0.5em;">{{ \App\Models\Order::where('user_id', $closet->getOwner()->id)->count() }}</h5>
								<small>Bought</small>
							</div>
						</div>
						<div class="row pt-5">
							<i>{{ $closet->getOwner()->bio }}</i>
						</div>

						<div class="row pt-5 pb-5">
							Joined {{ date("F Y", strtotime($closet->getOwner()->created_at)) }}
						</div>
					</div>
				</div>

				<div class="col-sm-9">
					<div class="content-sec">

						<div class="row" style="height: 20px;">
							<div class="col">
								@if(! isset($loves))
								<a href="/closet?id={{ $closet->id }}" class="closet-nav-active" style="padding-right: 5em;">My Closet</a>
								<a href="/closet?id={{ $closet->id }}&tab=loves" class="closet-nav">My Loves</a>
								@else
								<a href="/closet?id={{ $closet->id }}" class="closet-nav" style="padding-right: 5em;">My Closet</a>
								<a href="/closet?id={{ $closet->id }}&tab=loves" class="closet-nav-active">My Loves</a>
								@endif
							</div>
						</div>

						<hr />

						<div class="row">
						@if(! isset($loves))
							@php
								$products = $closet->getProducts();
							@endphp
						@else
							@php
								$products = $loves;
							@endphp
						@endif
						
						@foreach($products as $product)

							{!! $product->getHTML() !!}

						@endforeach

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<!-- end All Products -->
@endsection
