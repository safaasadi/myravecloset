@extends('layouts.app_coming_soon')

@section('foot')
<script type="text/javascript">

$(document).ready(function() {
	var desktop_bg = 'url(images/coming_soon.png)';
	var mobile_bg = 'url(images/coming_soon_mobile.png)';

	changeBG();

	$( window ).resize(function() {
		changeBG();
	});

	function changeBG() {
		var width = $( window ).width();
		var height = $( window ).height();
		// landscape
		if(width >= height) {
		console.log(desktop_bg);

			$('#background-img').css('background-image', desktop_bg);
		} else {
			$('#background-img').css('background-image', mobile_bg);
		}
	}
});
</script>
@endsection

@section('content')

<!-- Coming Soon -->
<div class="hun-section-coming-soon layout-1">
		<div class="inner-section">
			<div class="bg-sec parallax100"><span class="inner-parallax" id="background-img" style="background-image: url(images/coming_soon.png);"></span></div>

			<div class="container full-height-with-header">
				<div class="content-sec set-color">
					<h2 class="title-sec">
					@if(!isset($subscribed))
						COMING SOON
					@else
						THANK YOU!
					@endif
					</h2>

					<div class="countdown-sec js-call-countdown" data-year="2020" data-month="10" data-date="5" data-hours="1" data-minute="1" data-second="1" data-timezone="">
						<div class="item-countdown">
							<span class="number-item days">35</span> days
						</div>

						<div class="item-countdown">
							<span class="number-item hours">17</span> hours
						</div>

						<div class="item-countdown">
							<span class="number-item minutes">50</span> mins
						</div>

						<div class="item-countdown">
							<span class="number-item seconds">39</span> secs
						</div>
					</div>

					@if(!isset($subscribed))
					<form class="subscribe-form-sec" action="/subscribe_email" method="POST">
					@csrf
						<input type="email" name="email" placeholder="Enter your email">

						<button type="submit">
							Subscribe
						</button>
					</form>
					@endif

					<div class="social-sec">
						<a href="https://www.instagram.com/shopmyravecloset/" class="social-link"><i class="fa fa-instagram"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end Coming Soon -->

@endsection

@section('foot')
@if(isset($subscribed))
<script type="text/javascript">
    $(document).ready(function () {
        Toast.fire({
            type: 'success',
            title: 'Thank you for subscribing!'
        });
    });
</script>
@endif
@endsection