@extends('layouts.app')

@section('content')
<div class="hun-section-detail-product layout-1 show-space-bottom" style="padding-top: 6em;">
	<div class="inner-section">
		<div class="content-tab">
			<div class="row justify-content-center">
				<div class="col-12 col-md-4">
					<div class="col-12 text-center">
						<h2 class="title-form">
							Reset Password
						</h2>
					</div>
					<div class="col-12">
						<form action="/set-password" method="POST" style="padding-top: 2em;">
						@csrf

							<input type="hidden" name="token" value="{{ $tokenData->token }}">

							<p class="description-form text-center" style="padding-bottom: 1em;">
								{{ $tokenData->email }}
							</p>

							<label>
								<input type="password" class="input-field" name="password" required placeholder="New Password">
							</label>

							<label>
								<input type="password" class="input-field" name="password_confirmation" required placeholder="Confirm Password">
							</label>

							@if(isset($errors))
								@if($errors->any())
									@foreach($errors->getMessages() as $this_error)
										<p style="color: red;">{{$this_error[0]}}</p>
									@endforeach
								@endif 
							@endif

							<button class="btn-clean" type="submit" style="margin-top: 2em;">
								Reset Password
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
