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
		margin-top: 4em;
	}

	.status-form {
	  margin-top: 20%;
  }

}

</style>
@endsection

@section('foot')
<script type="text/javascript">
	$(document).ready(function() {
		$('#refund-order-form').on('submit', function(event) {
			event.preventDefault();

			var form = $(this);


			Swal.fire({
				title: 'Are you sure?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Issue Refund'
			}).then((result) => {
				if (result.isConfirmed) {
					form.unbind('submit').submit();
				}
			});

		});
	});
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

						<div class="row">
							<div class="col-12 col-md-6">
							@if(auth()->user()->isSeller())
								@php
									$paypal_account = auth()->user()->PayPalAccount();
									$application_fee_percent = auth()->user()->getCommission();
									$credit_balance = \App\Models\Order::where('seller_id', auth()->user()->id)->where('paid_out', false)->sum('cost_item');
									$pending_credit_balance = $credit_balance - ($credit_balance * $application_fee_percent);
								@endphp
								<h5 style="margin-top: 0em;">Pending Balance: ${{ number_format($pending_credit_balance, 2, '.', ',') }}</h5>
							@endif
							</div>
						</div>

						<div class="row" style="padding-bottom: 2em;cursor: pointer;">
							<div class="col text-center extra-nav @if($tab == 0) extra-active-nav @endif" onclick="location.href='/seller-tools?tab=0';">
								<p>SHIP NOW</p>
							</div>
							<div class="col text-center extra-nav @if($tab == 1) extra-active-nav @endif" onclick="location.href='/seller-tools?tab=1';">
								<p>ORDERS</p>
							</div> 
							<div class="col text-center extra-nav @if($tab == 3) extra-active-nav @endif" onclick="location.href='/seller-tools?tab=3';">
								<p>RENTALS</p>
								
								<!-- <div class="item w-100" style="margin-top: 0em;">
								<a href="/seller-tools?tab=3" class="btn-modern @if($tab == 3) btn-active @endif"  style="line-height: 20px;font-size: 1rem;">
										@if(sizeof(auth()->user()->getRentalsSeller()) > 0)
											<span class="notify-badge">{{ sizeof(auth()->user()->getRentalsSeller()) }}</span>
										@endif
										RENTALS
									</a>
								</div> -->
							</div>
							<!-- <div class="col text-center">
								<a href="/seller-tools?tab=2" class="closet-nav @if($tab == 2) active @endif" style="font-size: 0.8em;">
									<span class="notify-badge">0</span>
									Disputes
								</a>
							</div> -->
						</div>

						<div class="row">
							<div class="container col-md-10">
								@if($tab == 1)
									@include('sections.seller-tools.orders')
								@elseif($tab == 3)
									@include('sections.seller-tools.active_rentals')
								@else
									@include('sections.seller-tools.ready_to_ship')
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
