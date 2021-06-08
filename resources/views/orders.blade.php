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
.title-group a:hover {
	text-decoration: underline;
}
.active {
	color: #6534ff;
}

.ready-to-ship {
	background-color: #f5f5f5;
	margin-bottom: 1em;
	border: 1px solid #e5e5e5;
}

.ready-to-ship:hover {
	box-shadow:0 5px 20px 5px rgba(0,0,0,.15);
	transition: all .2s;
}

@media (min-width: 600px) {

	.status-payments {
		margin-bottom: -2em;
		margin-top: 2em;
	}

	.status-form {
	margin-top: 20%;
	}

}
</style>
@endsection


@section('foot')
<script type="text/javascript">
	
</script>
@endsection

@section('content')


@if(! isset($tab))
	@php
		$tab = 0;
	@endphp
@endif

<!-- All Products -->
<div class="hun-section-all-products layout-2 js-call-magnificpopup outer-hun-element-product--type-1 price-color ratio-img-3-4 show-space-bottom" style="padding-top: 2em;">
	<div class="inner-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-11 col-lg-9 order-lg-2">
					<div class="content-sec">

						<div class="row" style="padding-bottom: 2em;cursor: pointer;">

							<div class="col text-center extra-nav @if($tab == 0) extra-active-nav @endif" onclick="location.href='/orders?tab=0';">
								<p>PURCHASES</p>
							</div>
							<div class="col text-center extra-nav @if($tab == 1) extra-active-nav @endif" onclick="location.href='/orders?tab=1';">
								<p>RENTALS</p>
							</div> 

						</div>

						<div class="row">
							<div class="container col-md-10">
								@if($tab == 1)
									@include('sections.orders.active_rentals')
								@else
									@include('sections.orders.my_orders')
								@endif
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
