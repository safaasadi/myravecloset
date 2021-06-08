<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="MyRaveCloset">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">


    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:700">

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
    <!--===============================================================================================-->
    
    <link rel="stylesheet" type="text/css" href="{{ asset('addtohomescreen/addtohomescreen.css') }}">
    <script src="{{ asset('addtohomescreen/addtohomescreen.js') }}"></script>
    {!! NoCaptcha::renderJs() !!}
    
    @include('partials.messages.messages_css')

    @yield('head')
</head>


<body class="animsition">
    <div class="hun-page">
        @include('sections.header')
        @yield('content')
        @include('sections.footer')
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
     <!--===============================================================================================-->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

<script type="text/javascript">
    var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        encrypted: false,
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
    });

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 6000
    });

    @if(Session::has('alert'))
    $(document).ready(function () {
        Toast.fire({
            icon: '{{ Session::get('alert')['type'] }}',
            title: '{{ Session::get('alert')['msg'] }}'
        });
        {!! Session::forget('alert') !!}
    });
    @endif
</script>

<script type="text/javascript">
function openRefineMenu() {
    $('#mobile-menu-nav').css('visibility', 'hidden');
    $('#refine-search-nav').css('visibility', 'visible');
    $('.btn-toggle-menu').click();
}

$(document).ready(function() {

    @auth
        @if(! \Request::is('create-listing'))
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                $('.hun-page').after(
                    `<div class="btn-create-listing set-color active-btn" onclick="location.href='/create-listing';">` +
                    '<span class="symbol-btn">' +
                    '</span>' +
                    '</div>'
                );
            }
        @endif
    @endauth

    @guest
        @if(! \Session::has('add_homescreen'))
        @php
        \Session::put('add_homescreen', true);
        @endphp
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            setTimeout(function() {
            Swal.fire({
                text: "Add us to your home screen for a better experience!",
                //type: 'info',
                imageUrl: '{{ asset('add-to-homescreen.gif') }}',
                imageWidth: 600,
                // imageHeight: 400,
                imageAlt: 'Add to HomeScreen GIF',
                showCancelButton: false,
                confirmButtonText: "Done. Let's look around!",
            }).then(result => {
                
            });
        },1000);
        }
        @endif
    @endguest

    $('#nav-notifications').on('click', function(e) {
        var container = $("#nav-notifications").parent();
        container.find('.content-cart').css('opacity', 1);
        container.find('.content-cart').css('visibility', 'visible');
        container.find('.content-cart').css('pointer-events', 'auto');

        $.ajax({
            url: "{{ route('clear-notifications') }}",
            data: {
                _token: '{{ csrf_token() }}'
            },
            type: 'POST',
        }).done(function (resp) {
            document.getElementById('nav-notifications').classList.remove("btn-cart");
            document.getElementById('nav-notifications').setAttribute('data-count-cart', 0); 
        });
    });

    $('#account-btn').on('click', function(e) {
        var container = $("#account-btn").parent();
        container.find('.content-cart').css('opacity', 1);
        container.find('.content-cart').css('visibility', 'visible');
        container.find('.content-cart').css('pointer-events', 'auto');
    });

    $(document).mouseup(function(e) {
        var container = $("#refine-search-nav");
      
        if(!container.is(e.target) && container.has(e.target).length === 0) {
            $('#mobile-menu-nav').css('visibility', 'visible');
            $('#refine-search-nav').css('visibility', 'hidden');
        }

        var container = $("#nav-notifications").parent();
      
        if(!container.is(e.target) && container.has(e.target).length === 0) {
            container.find('.content-cart').css('opacity', '');
            container.find('.content-cart').css('visibility', '');
            container.find('.content-cart').css('pointer-events', '');
        }

        var container = $("#account-btn").parent();
      
        if(!container.is(e.target) && container.has(e.target).length === 0) {
            container.find('.content-cart').css('opacity', '');
            container.find('.content-cart').css('visibility', '');
            container.find('.content-cart').css('pointer-events', '');
        }
    });
});
</script>

<script type="text/javascript">



    function withdrawPopup() {
        Swal.fire({
            title: 'Withdraw to PayPal',
            html:
            'You can withdraw: <b>$' + $('#pending_balance').text() + '</b>',
            icon: 'warning',
            input: 'text',
            inputValue: $('#pending_balance').text(),
            showCancelButton: true,
            showLoaderOnConfirm: true,
            customClass: {
                confirmButton: 'btn-clean w-40 mr-5',
                cancelButton: 'btn-clean btn-red w-40'
            },
            buttonsStyling: false,
            confirmButtonText: 'Transfer',
            cancelButtonText: 'Cancel',
            preConfirm: (amount) => {
                if(!isValid(amount)) {
                    Swal.showValidationMessage('Invalid amount');
                } else {
                    // if(parseFloat($('#pending_balance').text()) < parseFloat(amount)) {
                    //     Swal.showValidationMessage('Insufficient funds');
                    // } else {
                        // do ajax request
                        return $.ajax({
                            url: '/paypal/withdraw',
                            type: 'POST',
                            data: {
                                'amount': parseFloat(amount),
                                _token: '{{ csrf_token() }}'
                            },
                        }).done(function (msg) {
                            return msg;
                        });
                    // }
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                $('#pending_balance').text(result['pending_balance']);
            }
        });

        function isValid(str){
         return !/[~`!#$%\^&*+=\-\[\]\\';,/{}|\\":<>\?]/g.test(str);
        }
    }

    $('#register-form').on('submit', function(event) {
        event.preventDefault();

        var form = $(this);
		var username = $('#register-username').val();
        var email = $('#register-email').val();

        $.ajax({
            url: '/validate-register',
            type: 'POST',
            data: form.serialize(),
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

            if(msg['g-recaptcha-response']) {
                $('#g-recaptcha-response-error').text('Please confirm that you are not a robot.');
            }

            if(!msg['username'] && !msg['email'] && !msg['g-recaptcha-response']) {
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
            data: form.serialize(),
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
        @auth
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
        @else
            $('#account-btn').click();
        @endauth
    }

    function getProductHTML(product_id, product_name, purchase_price, rental_price, love_count, loved, image_urls) {
        var html = `
        <div class="col-6 col-md-6 col-lg-4 col-xl-3">
            <div class="hun-element-product--type-1">
                <a href="javascript:love('${product_id}');" class="love-btn-feed ${loved ? 'loved' : 'unloved'}">
                    <i class="fa fa-2x ${loved ? 'fa-heart' : 'fa-heart-o'}" id="love-${product_id}"></i>
                </a>

                <a href="/item?id=${product_id}" class="pic-product">
                    <span class="gallery-product">
                    `;
                    image_urls.forEach(url => {
                        html += `
                            <span class="item-gal">
                                <span class="image-gal" style="background-image: url(${url});"></span>
                            </span>
                        `;
                    });
                html += `
                    </span>
                </a>

                <div class="text-product">
                    <h6 class="name-product set-color">
                        <a href="/item?id=${product_id}">
                            ${product_name}
                        </a>
                    </h6>

                    <div class="price-product set-color">
                        ${purchase_price > 0 ? `<span class="new-price">Buy for $${purchase_price.toFixed(2)}</span>` : ''}
                        ${rental_price > 0 ? `<span class="new-price"><small style="color: gray;">Rent for $${rental_price.toFixed(2)}</small></span>` : ''}
                    </div>
                </div>

                <div class="buttons-product">
                    <a href="#" class="btn-addcart set-color" style="color:#ff4c62;">
                        <i class="fa fa-heart" style="padding-right: 0.5em;"></i><b>${love_count}</b>
                    </a>
                </div>
            </div>
        </div>
        `;

        return html;
    }
</script>

@auth
    @include('partials.messages.messages_js')
    @include('partials.notifications_js')
@endauth

@yield('foot')
</body>
</html>
