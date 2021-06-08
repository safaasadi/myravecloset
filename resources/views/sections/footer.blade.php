@auth
<div id="chatbox-popup" class="hun-element-quickview-popup--type-1 chatbox mfp-hide">
	<div class="row" style="margin-top: -1em;">
		<div class="col-2 text-center">
			<a href="javascript:loadConversations();" id="chatbox-back" hidden><i class="chat-nav icon ion-ios-arrow-back" style="font-size: 1.5em;"></i></a>
		</div>
		<div class="col-8 text-center">
			<h5 id="chatbox-title">Messages</h5>
		</div>
		<div class="col-2 text-center">
			<a href="javascript:closeChat();"><i class="chat-nav icon ion-close" style="font-size: 1.2em;"></i></a>
		</div>
	</div>

	<hr style="margin-left: -1em;width: 110%;" />

	<div class="container" id="chatbox-container" style="width: 95%;height: 70%;overflow-y:scroll;"></div>

	<hr style="margin-left: -1em;width: 110%;" />

	<div class="row justify-content-center" style="margin-top: 0.5em;">
		<form method="POST" id="new-message-form" style="width: 100%" hidden>
			<input id="new-message-form-send-to" type="text" class="hidden" readyonly="readyonly" name="to_user_id" hidden></input>
			<label>
				<div class="row">
					<div class="col-10">
						<input id="new-message-form-message" type="text" class="input-field chat-input" name="message" required placeholder="Type your message...">
					</div>
					<div class="col-2">
						<a href="javascript:sendMessage();"><i class="chat-nav icon ion-android-send chat-send" style="font-size: 1.6em;"></i></a>
					</div>
				</div>
			</label>
		</form>
	</div>
</div>
@endauth

<!-- Footer -->
<footer class="hun-section-footer hun-footer full-width layout-1">
	<div class="content-footer set-color">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-xl-4">
					<div class="widget">
						<h3 class="title-widget">
							ABOUT MYRAVECLOSET
						</h3>

						<div class="text-widget">
							<p style="max-width: 469px;">
								Have old fits sitting in your closet? Now you can make some money while helping other ravers save!
							</p>

							<p>
								<img src="{{ asset('images/icons/powered_by_paypal.png') }}" style="max-height: 64px;" alt="IMG-PAYPAL">
							</p>
						</div>
					</div>
				</div>

				<div class="col-sm-4 col-lg-2 col-xl-2">
					<div class="widget">
						<h3 class="title-widget">
							CATEGORIES
						</h3>

						<div class="text-widget">
							<ul>
								@foreach(\App\Models\Category::where('parent_category', null)->offset(0)->limit(4)->get() as $category)
								<li><a href="/shop?category={{ $category->id }}">{{ $category->name }}</a></li>
								@endforeach
							</ul>
						</div>
					</div>
				</div>

				<div class="col-sm-4 col-lg-2 col-xl-2">
					<div class="widget">
						<h3 class="title-widget">
							LINKS
						</h3>

						<div class="text-widget">
							<ul>
								<li><a href="/privacy_policy.pdf" target="_blank">Privacy Policy</a></li>
								<li><a href="/terms_of_service.pdf" target="_blank">Terms of Service</a></li>
								<li><a href="#">Contact Us</a></li>
								<li><a href="#">Returns</a></li>
							</ul>
						</div>
					</div>
				</div>

				<div class="col-sm-4 col-lg-2 col-xl-2">
					<div class="widget">
						<h3 class="title-widget">
							HELP
						</h3>

						<div class="text-widget">
							<ul>
								<li><a href="#">Track Order</a></li>
								<li><a href="#">Returns</a></li>
								<li><a href="#">Shipping</a></li>
								<li><a href="#">FAQs</a></li>
							</ul>
						</div>
					</div>
				</div>

				<div class="col-sm-6 col-xl-2">
					<div class="widget">
						<h3 class="title-widget">
							NEWSLETTER
						</h3>

						<div class="text-widget">
							<div class="subscribe-form-01 set-color">
								<form action="/subscribe_email" method="POST">
								@csrf
								<label>
									<input type="email" name="email" placeholder="Enter your email">
								</label>

									<button type="submit">
										Subscribe
									</button>
								</form>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- <div class="copyright-footer set-color">
		<div class="contaner">
			<div class="content-copyright">
				Copyright © 2020 MyRaveCloset, LLC
			</div>
		</div>
	</div> -->
</footer>
<!-- end Footer -->