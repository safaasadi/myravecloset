@extends('layouts.app')

@section('foot')
<script type="text/javascript">
	$('#select-sort').on('change', function(e) {
		$('#form-sort').submit();
	});

	$('#hide-sold-items').on('change', function(e) {
		var checked = $('#hide-sold-items').attr('checked');
		$("#hide_sold").val(checked ? "0" : "1");
		$('#form-sort').submit();
	});
</script>
@endsection

@if(isset($criteria) && isset($hide_sold))
	@php
		$criteria_url = "hide_sold=" . $hide_sold . "&criteria=" . $criteria;
	@endphp
@else
	@php
		$criteria_url = "hide_sold=false&criteria=1";
	@endphp
@endif

@section('content')

<!-- Page Title -->
<div class="hidden-phone hun-section-page-title layout-1 set-color text-center off-parallax show-bg-img show-overlay-color">
	<div class="inner-section">
		<div class="bg-section parallax100"><span class="inner-parallax" style="background-image: url(images/shop-bg.png);"></span></div>

		<div class="container">
			<h2 class="main-title">
				@if(isset($category))
					@if($category->parent_category != null)
						{{ \App\Models\Category::where('id', $category->parent_category)->first()->name }} / {{ $category->name }}
					@else
						{{ $category->name }}
					@endif
				@else
					Featured
				@endif
			</h2>
		</div>
	</div>
</div>
<!-- end Page Title -->

<!-- All Products -->
<div class="pt-5 hun-section-all-products layout-2 js-call-magnificpopup outer-hun-element-product--type-1 price-color ratio-img-3-4 show-space-bottom">
	<div class="inner-section">
		<div class="container">
			
			<div class="row">
				<div class="col-12">
					<div class="content-sec">
						<div class="tool-products set-color">
							<div class="w-100">
								<a class="btn-clean" style="width: 10em;float: left;" href="javascript:openRefineMenu();">Refine Search</a>
							</div>
						</div>

						<div class="row">
							
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
