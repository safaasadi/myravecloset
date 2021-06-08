@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css" integrity="sha512-2eMmukTZtvwlfQoG8ztapwAH5fXaQBzaMqdljLopRSA0i6YKM8kBAOrSSykxu9NN9HrtD45lIqfONLII2AFL/Q==" crossorigin="anonymous" />
@endsection

@section('foot')
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js" integrity="sha512-Gs+PsXsGkmr+15rqObPJbenQ2wB3qYvTHuJO6YJzPe/dTLvhy0fmae2BcnaozxDo5iaF8emzmCZWbQ1XXiX2Ig==" crossorigin="anonymous"></script>

<script>
	var vanilla = null;
	$(document).ready(function() {
		$('.avatar-xxl').on('click', function() {
			if(vanilla != null) {
				vanilla.destroy();
				vanilla = null;
			}

			$('#avatar-img-form input').val('');
			$('#avatarimg').attr('src', '');
			$('#avatar-edit-box').addClass('hidden');
			
			selectImage();
		});

		$("#avatar-img-form input").on("change", function() {
			$('#avatar-popup-href').click();

			var path = $(this).val();
			var file = this.files[0];
			var reader = new FileReader();

			reader.onloadend = function () {
				$('#model-upload-prev').css('background-image', 'url("' + reader.result + '")');
			};

			if (file) {
				reader.readAsDataURL(file);
				var _URL = window.URL || window.webkitURL;
				var img = new Image();
				img.onload = function () {
				};
				img.src = _URL.createObjectURL(file);
			} else {
			}

			$('#avatarimg').attr('src', img.src);
			var el = document.getElementById('avatarimg');
			vanilla = new Croppie(el, {
				viewport: { width: 200, height: 200, type: 'circle' },
				boundary: { width: 300, height: 300 },
				enableOrientation: true
			});
			$('#avatar-edit-box').show( "slow" );
		});
	});

	function selectImage() {
		$('#avatar-img-form input').trigger("click");
	}

	function updateAvatar() {
		vanilla.result('blob').then(function(blob) {
			var fd = new FormData();
			fd.append('fname', 'test.png');
			fd.append('data', blob);
			fd.append('_token', '{{ csrf_token() }}');
			$.ajax({
				type: 'POST',
				url: '/update-avatar',
				data: fd,
				processData: false,
				contentType: false
			}).done(function(data) {
				location.reload();
			});
		});
	}
	
	function cancelAvatar() {
		$('.mfp-close').click();
	}
	
</script>
@endsection

@section('content')

<!-- All Products -->
<div class="hun-section-all-products layout-2 js-call-magnificpopup outer-hun-element-product--type-1 price-color ratio-img-3-4 show-space-bottom pt-5">
	<div class="inner-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="text-center" style="width: 20em;">
					<h2 class="title-form">
						Account
					</h2>

					<div class="upload" style="padding-bottom: 2em;">
						<div class="data">
							<a id="avatar-popup-href" class="js-open-popup" href="#avatar-popup" title="Quick View" hidden></a>
								<img class="avatar-xxl" id="upload-avatar-btn" src="{{ auth()->user()->getAvatar() }}" alt="image">
						</div>
					</div>

					<form method="POST" action="{{ route('update-account') }}">
						@csrf


						<label>
							<label for="username" class="text-left">Username</label>
							<input id="username" type="username" class="input-field" name="username" value="{{ auth()->user()->username }}" required autocomplete="username" autofocus placeholder="Username">
						</label>

						<label>
						<label for="email" class="text-left">Email</label>
						<input id="email" type="email" class="input-field disabled" name="email" value="{{ auth()->user()->email }}" disabled autocomplete="email" placeholder="Email">
						</label>

						<label>
						<label for="bio" class="text-left">Bio</label>
							<textarea id="bio" name="bio" class="input-field" placeholder="Bio" style="min-height: 100px;">{{ auth()->user()->bio }}</textarea>
						</label>

						<button type="submit" class="btn-clean">
							Save
						</button>
					</form>

					<div class="row justify-content-center" style="padding-top: 4em;">
						<a href="{{ \App\PayPalHelper::getConnectUrl() }}" >Connect PayPal</a>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<!-- end All Products -->

<!-- Quickview Popup -->
<div id="avatar-popup" class="mfp-hide">
	<div class="container">
		<div class="row justify-content-center">
			<div class="avatar-upload-div" id="avatar-edit-box" style="display: none;">
				<div class="row">
					<div class="container centered" style="width: 360px;height: 360px;overflow:hidden;">
						<img id="avatarimg" style="width: 256px;height: 256px;" />
					</div>
				</div>
				<div class="row">
					<div class="container" style="text-align: right;">
						<button class="btn btn-avatar" onclick="updateAvatar()"><i class="fa fa-check"></i></button>
						<button class="btn btn-avatar" onclick="cancelAvatar()"><i class="fa fa-times"></i></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end Quickview Popup -->

<form enctype="multipart/form-data" id="avatar-img-form" role="form" method="POST" action="" hidden>
	<input type="file" class="form-control" name="user_photo">
</form>
@endsection
