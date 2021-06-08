@extends('layouts.app')

@section('head')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.2/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('foot')
<!--===============================================================================================-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.2/js/plugins/piexif.min.js" type="text/javascript"></script>
<!--===============================================================================================-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.2/js/plugins/sortable.min.js" type="text/javascript"></script>
<!--===============================================================================================-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.2/js/plugins/purify.min.js" type="text/javascript"></script>
<!--===============================================================================================-->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
<!--===============================================================================================-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.2/js/fileinput.min.js"></script>
<!--===============================================================================================-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.9/themes/fas/theme.min.js"></script>
<!--===============================================================================================-->
<link href="{{ asset('/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">


<script type="text/javascript">
    $("#fileupload").fileinput({
        theme: 'fas',
		showRemove: false,
		showUpload: false,
		showBrowse: false,
		showCaption: false,
		browseOnZoneClick: true,
        maxFileSize:200000,
		minFilesNum: 1,
        maxFilesNum: 8
    });

</script>

@auth
	@if(! \App\Models\ShippingInfo::where('user_id', auth()->user()->id)->where('default', true)->exists())
		@include('partials.shipping_info_form_js')
		<script type="text/javascript">
			$(document).ready(function() {
				$.magnificPopup.open({
					items: {
						src: '#shipping-popup'
					},
					type: 'inline',
					showCloseBtn: false,
					mainClass: 'mfp-3d-unfold',
					midClick: true,
					closeOnBgClick: false,
					enableEscapeKey: false
				});
			});
		</script>
	@endif
@endauth


<script type="text/javascript">

$(document).ready(function() {
	var sub_categories = [];

	$('#select-subcategory option').each(function(index, elem) {
		var name = $(elem).text();
		var sub_category_id = $(elem).attr('value');
		var parent_category_id = $(elem).data('parent');

		if(parent_category_id in sub_categories) {
			sub_categories[parent_category_id].push({'id' : sub_category_id, 'name' : name});
		} else {
			sub_categories[parent_category_id] = [{'id' : sub_category_id, 'name' : name}];
		}
	});

	var sizes = [];

	$('#select-size option').each(function(index, elem) {
		var name = $(elem).text();
		var id = $(elem).attr('value');
		var category_id = $(elem).data('category');

		if(category_id in sizes) {
			sizes[category_id].push({'id' : id, 'name' : name});
		} else {
			sizes[category_id] = [{'id' : id, 'name' : name}];
		}

	});

	$('#select-category').change(function(e) {
		var selectedCategory = $(this).children("option:selected").val();
		$('.bknd00').remove();
		
		if(sub_categories[selectedCategory] !== undefined) {
			$('#select-subcategory').attr('disabled', false);

			sub_categories[selectedCategory].forEach(element => {
				$('#select-subcategory').append(`<option class="bknd00" value="${element['id']}" data-parent="${selectedCategory}">${element['name']}</option>`);
			});
		} else {
			$('#select-subcategory').attr('disabled', true);
		}

		if(sizes[selectedCategory] !== undefined) {
			$('#select-size').attr('disabled', false);

			sizes[selectedCategory].forEach(element => {
				console.log(element);
				$('#select-size').append(`<option class="bknd00" value="${element['id']}" data-category="${selectedCategory}">${element['name']}</option>`);
			});
		} else {
			$('#select-size').attr('disabled', true);
		}
	});

	const minimum_purchase_price = parseInt("{{ env('MINIMUM_PURCHASE_PRICE', 3) }}");
	const minimum_rental_price = parseInt("{{ env('MINIMUM_RENTAL_PRICE', 3) }}");

	const commission = parseFloat("{{ auth()->user()->getCommission() }}");

	$('#price-purchase').on('keyup', function(event) {
		var amount = $(this).val();
		var earnings = "$" + (amount * (1 - commission)).toFixed(2);
		$('#earnings-purchase').val(earnings);
	});

	$('#price-rent').on('keyup', function(event) {
		var amount = $(this).val();
		var earnings = "$" + (amount * (1 - commission)).toFixed(2);
		$('#earnings-rent').val(earnings);
	});

	$('#create-listing').submit(function(event) {
		event.preventDefault();

		if((! $('#price-purchase').val()) && (! $('#price-rent').val())) {
			$('#error-price').text('You must specify either a purchase price or rent price.');
			return;
		}

		if($('#price-purchase').val() && ($('#price-purchase').val() < minimum_purchase_price)) {
			event.preventDefault();
			$('#error-price').text(`Purchase price must be more than ${minimum_purchase_price}.`);
			return;
		}

		if($('#price-rent').val() && ($('#price-rent').val() < minimum_rental_price)) {
			event.preventDefault();
			$('#error-price').text(`Rental price must be more than ${minimum_rental_price}.`);
			return;
		}

		if($('#price-rent').val()) {
			var selectedCategory = $('#select-category').children("option:selected").text();
			if(! selectedCategory.includes("Full Outfit")) {
				Swal.fire({
					text: 'Only Full Outfits are rentable.',
					icon: 'warning',
					showCloseButton: true,
					showCancelButton: false,
					showConfirmButton: true,
				});
				return;
			}
		}

		$('#create-btn').attr('disabled', true);
		$('#create-btn').addClass('disabled');

		Swal.fire({
			title: 'Please wait...',
			text: 'This may take a few seconds',
			showCloseButton: false,
			showCancelButton: false,
			showConfirmButton: false,
			focusConfirm: false,
			allowOutsideClick: false
		});

		Swal.showLoading();

		var form = $(this);

		setTimeout(function() {
			form.unbind('submit').submit();
		}, 1500);

	});

});

</script>

@endsection


@section('content')

<!-- Create Listing -->
<div class="hun-section-all-products layout-2 js-call-magnificpopup outer-hun-element-product--type-1 price-color ratio-img-3-4 show-space-bottom">
	<div class="inner-section">
		<div class="container" style="width: 90%;">

			<form action="/create-product" method="POST" enctype="multipart/form-data" id="create-listing"> 
			@csrf
				<div class="row pt-5">
					<h1>Create Listing</h1>
				</div>

				<hr />

				<div class="row pt-5">
					<div class="tabs-account w-100">
						<div class="content-tab w-100">
							<div class="row">
								<div class="col-12 col-md-4">
									<h2><b>UPLOAD PHOTOS</b></h2>
									<small class="hidden-phone" style="color: darkgrey;">Add up to 16 photos to give your buyers an idea of what you’re selling.</small>
								</div>
								<div class="col-12 col-md-8">
									<label>
										<div class="file-loading w-100">
											<input id="fileupload" type="file" name="images[]" multiple data-preview-file-type="text" accepts="image/*" multiple="true" data-browse-on-zone-click="true">
										</div>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row pt-5">
					<div class="tabs-account w-100">
						<div class="content-tab w-100">
							<div class="row">
								<div class="col-12 col-md-4">
									<h2><b>TITLE</b></h2>
									<small class="hidden-phone" style="color: darkgrey;">Include words that your buyers would search for.</small>
								</div>
								<div class="col-12 col-md-8">
									<label>
										<input id="title" type="text" class="input-field w-100" style="font-size:25px;" name="title" required autocomplete="title" placeholder="What are you selling?">
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row pt-5">
					<div class="tabs-account w-100">
						<div class="content-tab w-100">
							<div class="row">
								<div class="col-12 col-md-4">
									<h2><b>DESCRIPTION</b></h2>
									<small class="hidden-phone" style="color: darkgrey;">Add a detailed description of what you’re selling.</small>
								</div>
								<div class="col-12 col-md-8">
									<label>
										<input id="description" type="text" class="input-field w-100" style="font-size:25px;" name="description" required autocomplete="description" placeholder="Describe it!">
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row pt-5">
					<div class="tabs-account w-100">
						<div class="content-tab w-100">
							<div class="row">
								<div class="col-12 col-md-4">
									<h2><b>CATEGORY</b></h2>
									<small class="hidden-phone" style="color: darkgrey;">Add details to help your buyers find your item quickly.</small>
								</div>
								<div class="col-12 col-md-8" style="height: 4em;">
									<label>
										<div class="row">

											<div class="col">
												<label class="select_label">
													<select id="select-category" name="category">
														<option selected disabled>Select Category</option>
														@foreach(\App\Models\Category::where('parent_category', null)->get() as $category)
														<option value="{{ $category->id }}">{{ $category->name }}</option>
														@endforeach
													</select>	
												</label>						
											</div>

											<div class="col">
												<label class="select_label">
													<select id="select-subcategory" name="subcategory" disabled>
														<option selected disabled>Select Subcategory</option>
														@foreach(\App\Models\Category::where('parent_category', '!=', null)->get() as $sub_category)
														<option class="bknd00" value="{{ $sub_category->id }}" data-parent="{{ $sub_category->parent_category }}">{{ $sub_category->name }}</option>
														@endforeach
													</select>	
												</label>							
											</div>

										</div>

									</label>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row pt-5" hidden>
					<div class="tabs-account w-100">
						<div class="content-tab w-100">
							<div class="row">
								<div class="col-12 col-md-4">
									<h2><b>QUANTITY</b></h2>
									<small class="hidden-phone" style="color: darkgrey;">Add a detailed description of what you’re selling.</small>
								</div>
								<div class="col-12 col-md-8">
									<label>
										<input id="quantity" name="quantity" class="input-field" type="text" value="1">
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row pt-5">
					<div class="tabs-account w-100">
						<div class="content-tab w-100">
							<div class="row">
								<div class="col-12 col-md-4">
									<h2><b>SIZE</b></h2>
									<small class="hidden-phone" style="color: darkgrey;"></small>
								
								</div>
								<div class="col-12 col-md-8" style="height: 4em;">
									<label class="select_label">
										<select id="select-size" name="size" disabled>
											<option selected disabled>Select Size</option>
											@foreach(\App\Models\Size::get() as $size)
											<option class="bknd00" value="{{ $size->id }}" data-category="{{ $size->category }}">{{ $size->name }}</option>
											@endforeach
										</select>	
									</label>					
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row pt-5">
					<div class="tabs-account w-100">
						<div class="content-tab w-100">
							<div class="row">
								<div class="col-12 col-md-4">
									<h2><b>NEW WITH TAGS</b></h2>
									<p>Optional</p>
									<small class="hidden-phone" style="color: darkgrey;">An item is New With Tags (NWT) if it is brand-new, unused, and has all of its original tags attached. For more information, please visit MyRaveCloset's NWT Policy.</small>
								</div>
								<div class="col-12 col-md-8" style="height: 4em;">
									<label class="select_label">
										<select name="new_with_tags">
											<option value="0">No</option>
											<option value="1">Yes</option>
										</select>	
									</label>				
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row pt-5">
					<div class="tabs-account w-100">
						<div class="content-tab w-100">
							<div class="row">
								<div class="col-12 col-md-4">
									<h2><b>BRAND</b></h2>
									<p>Optional</p>
									<small class="hidden-phone" style="color: darkgrey;">Add details to help your buyers find your item quickly.</small>
								</div>
								<div class="col-12 col-md-8">
									<label>
										<input id="brand" type="text" class="input-field w-100" style="font-size:25px;" name="brand" required autocomplete="brand" placeholder="Enter the Brand/Designer">
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- <hr /> -->

				<!-- <div class="row pt-5">
					<div class="tabs-account w-100">
						<div class="content-tab w-100">
							<div class="row">
								<div class="col-12 col-md-4">
									<h2><b>COLOR</b></h2>
									<p>Optional</p>
									<small style="color: darkgrey;">Select up to 2 colors.</small>
								</div>
								<div class="col-12 col-md-8">
									<label class="select_label">
										<select name="color">
											<option>Yes</option>
											<option>No</option>
										</select>	
									</label>						
								</div>
							</div>
						</div>
					</div>
				</div> -->

				<div class="row pt-5">
					<div class="tabs-account w-100">
						<div class="content-tab w-100">
							<div class="row">
								<div class="col-12 col-md-4">
									<h2><b>SHIPPING</b></h2>
									<p style="padding-bottom: 1em;">Weight in pounds.</p>
								</div>
								<div class="col-12 col-md-8">
									<label>
										<input id="weight" name="weight" class="input-field" type="number" step="0.01" value="1.0">
									</label>					
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row pt-5">
					<div class="tabs-account w-100">
						<div class="content-tab w-100">
							<div class="row">
								<div class="col-12 col-md-4">
									<h2><b>REFUNDS</b></h2>
									<p style="padding-bottom: 1em;">Refund period in days. 0 being no refunds. Funds will be released after the refund period is passed.</p>
								</div>
								<div class="col-12 col-md-8">
									<label>
										<input id="refund_days" name="refund_days" class="input-field" type="number" step="1" value="0" min="0" max="14">
									</label>					
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row pt-5">
					<div class="tabs-account w-100">
						<div class="content-tab w-100">
							<div class="row">
								<div class="col-12 col-md-4">
									<h2><b>PRICING</b></h2>
									<small class="hidden-phone" style="color: darkgrey;">View MyRaveClosets’s seller fee policy</small>
								</div>
								<div class="col-12 col-md-8">
									<div class="row">
										<div class="col">
											<label>
												<input id="price-original" type="text" class="input-field w-100" style="font-size:25px;" name="price-original" required autocomplete="price-original" placeholder="Original Price">
											</label>
										</div>
									</div>
									<div class="row pt-2">
										<div class="col-12 col-md-6">
											<label>
												<input id="price-purchase" type="number" class="input-field w-100" style="font-size:25px;" name="price-purchase" autocomplete="price-purchase" placeholder="Purchase Price">
											</label>
										</div>
										<div class="col-12 col-md-6" style="display: none;">
											<label>
												<input id="price-rent" type="number" class="input-field w-100" style="font-size:25px;" name="price-rent" autocomplete="price-rent" placeholder="Rent Price">
											</label>
										</div>
									</div>

									<label id="error-price" style="color:red"></label>

									<div class="row pt-5">
										<div class="col">
											<label>
												<label for="earnings-purchase">Your Earnings (when sold)</label>
												<input id="earnings-purchase" type="text" class="input-field w-100 disabled" style="font-size:25px;background-color:lightgray;" name="earnings-purchase" placeholder="$0.00" disabled>
											</label>
										</div>
										<div class="col" style="display: none;">
											<label>
												<label for="earnings-rent">Your Earnings (when rented)</label>
												<input id="earnings-rent" type="text" class="input-field w-100 disabled" style="font-size:25px;background-color:lightgray;" name="earnings-rent" placeholder="$0.00" disabled>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<br />
				<br />
				<br />

				<button type="submit" class="btn-clean" id="create-btn">Create Listing</button>

			</form>
		</div>
	</div>
</div>
<!-- end Create Listing -->

<div id="shipping-popup" class="hun-element-quickview-popup--type-1 mfp-hide">
	<div class="hun-element-detail-product--type-1">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-5 col-lg-6 text-center">
					<h6 class="text-center" style="font-size: 2em;">Add New Shipping Address</h6>
					<small class="text-center" style="padding-bottom: 1em;">You need to add a default shipping address before you can begin selling items.</small>
					<div style="padding-bottom: 1em;"></div>
					@include('partials.shipping_info_form_html')
				</div>

			</div>
		</div>
	</div>
</div>

@endsection
