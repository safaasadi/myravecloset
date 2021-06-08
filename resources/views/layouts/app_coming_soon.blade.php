<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

	<link rel="icon" type="image/png" href="{{ asset('images/icons/favicon-01.png') }}"/>
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('fonts/font-awesome-4/css/font-awesome.min.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('fonts/ionicons/css/ionicons.min.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('fonts/simple-line-icons/css/simple-line-icons.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==" crossorigin="anonymous" />
	<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/magnific-popup/magnific-popup.css') }}">
	<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/animate/animate.css') }}">
	<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/animsition/animsition.min.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('libs/parallax100/parallax100.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <!--===============================================================================================-->
    <link href="{{ asset('/assets/vendor/nucleo/css/nucleo.css') }}" rel="stylesheet">
    <!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('css/extra.css') }}">

    @yield('head')
</head>
<body class="animsition">
    <div class="hun-page">
        @yield('content')
    </div>

    <!--===============================================================================================-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('libs/jquery/jquery-3.4.1.min.js') }}"></script>
    <!--===============================================================================================-->
    <!-- <script src="https://unpkg.com/@popperjs/core@2"></script> -->
    <!--===============================================================================================-->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!--===============================================================================================-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('libs/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('libs/animsition/animsition.min.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('libs/parallax100/parallax100.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('libs/countdowntime/moment.min.js') }}"></script>
    <script src="{{ asset('libs/countdowntime/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('libs/countdowntime/moment-timezone-with-data.min.js') }}"></script>
    <script src="{{ asset('libs/countdowntime/countdowntime.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('libs/sticky-sidebar/theia-sticky-sidebar.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('js/main.js') }}"></script>

<script type="text/javascript">
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 6000
    });
    
    $(document).ready(function () {
        @if(Session::has('alert'))
        Toast.fire({
            type: '{{ Session::get('alert')['type'] }}',
            title: '{{ Session::get('alert')['msg'] }}'
        });
        {!! Session::forget('alert') !!}
        @endif
    });
</script>

<script type="text/javascript">
    $('#register-form').on('submit', function(event) {
        event.preventDefault();

        var form = $(this);
		var username = $('#register-username').val();
        var email = $('#register-email').val();

        $.ajax({
            url: '/validate-register',
            type: 'POST',
            data: {
                'username': username,
                'email': email,
                _token: '{{ csrf_token() }}'
            },
        }).done(function (msg) {
            if(username != '') {
                if(msg['username']) {
                    $('#register-username').css('border','1px solid red');
                    $('#register-username-error').text(msg['username']);
                } else {
                    $('#register-username').css('border', '');
                    $('#register-username-error').text('');
                }
            }

            if(email != '') {
                if(msg['email']) {
                    $('#register-email').css('border', '1px solid red');
                    $('#register-email-error').text(msg['email']);
                } else {
                    $('#register-email').css('border', '');
                    $('#register-email-error').text('');
                }
            }

            if(!msg['username'] && !msg['email']) {
                form.unbind('submit').submit();
            }

        });
	});

    $('#login-form').submit(function(event) {
		event.preventDefault();
        var form = $(this);
        var email = $('#login-email').val();
        var password = $('#login-password').val();

        $.ajax({
            url: '/validate-login',
            type: 'POST',
            data: {
                'email': email,
                'password': password,
                _token: '{{ csrf_token() }}'
            },
        }).done(function (msg) {
            if(msg['success']) {
                form.unbind('submit').submit();
            } else {
                $('#login-error').text('Invalid login credentials.');
            }
        });
	});

</script>

<script type="text/javascript">
    function love(product_id) {
        $.ajax({
            url: '/love',
            type: 'POST',
            data: {
                'id': product_id,
                _token: '{{ csrf_token() }}'
            },
        }).done(function (msg) {
            if(msg['success']) {
                if(msg['loved']) {
                    $(`#love-${product_id}`).removeClass('fa-heart-o');
                    $(`#love-${product_id}`).addClass('fa-heart');
                    $(`#love-${product_id}`).parent().removeClass('unloved');
                    $(`#love-${product_id}`).parent().addClass('loved');
                } else {
                    $(`#love-${product_id}`).removeClass('fa-heart');
                    $(`#love-${product_id}`).addClass('fa-heart-o');
                    $(`#love-${product_id}`).parent().removeClass('loved');
                    $(`#love-${product_id}`).parent().addClass('unloved');
                }
            } else {
                Toast.fire({
                    type: 'error',
                    title: 'Could not find product.'
                });
            }
        });
    }
</script>

@yield('foot')
</body>
</html>
