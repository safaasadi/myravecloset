<!-- Header -->
<header class="hun-section-header hun-header layout-1 wide-header use-img-logo">

    <div class="container-header">
        <div class="wrapper-header element-for-stick">
            <div class="container">
                <div class="logo-header">
                    <a href="/" class="text-logo set-color">{{ env('APP_NAME') }}</a>

                    <a href="/" class="main-logo">
                        <img src="{{ asset('images/icons/logo.png') }}" alt="LOGO">
                    </a>

                    <a href="/" class="mobile-logo">
                        <img src="{{ asset('images/icons/mobile_logo.png') }}" alt="LOGO">
                    </a>

                    <a href="/" class="sticky-logo">
                        <img src="{{ asset('images/icons/sticky_logo.png') }}" alt="LOGO">
                    </a>
                </div>

                <nav class="main-navigation">
                    <ul class="list-menu set-color">
                        <li class="menu-item menu-item-has-children {{ request()->is('/') ? 'current-menu-item' : '' }}">
                            <a href="/">
                                Home
                            </a>
                        </li>

                        <li class="menu-item menu-item-has-children {{ request()->is('featured') ? 'current-menu-item' : '' }}">
                            <a href="#">
                                Shop
                            </a>

                            <ul class="sub-menu">
                                <li class="menu-item">
                                    <a href="/shop">Featured</a>
                                </li>
                                @foreach(\App\Models\Category::where('parent_category', null)->get() as $c)
                                <li class="menu-item">
                                    <a href="/shop?category={{ $c->id }}">{{ $c->name }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </li>

                        @auth
                        <li class="menu-item {{ request()->is('create-listing') ? 'current-menu-item' : '' }}">
                            <a href="/create-listing">
                                Create Listing
                            </a>
                        </li>
                        @endauth
                    </ul>
                </nav>

                <div class="group-feature">
                    <div class="hun-element-search--type-1 set-color js-call-search">
                        <span class="btn-open search-open">
                            <i class="icon ion-android-search"></i>
                        </span>

                        <div class="search-form">
                            <span class="search-close"><i>×</i></span>

                            <form role="search" method="get" action="/search">
                                <input type="search" class="search-field set-color" value="" name="criteria" required="">
                                <span class="search-notice">Hit enter to search or ESC to close</span>
                            </form>

                            <ul class="list-result"></ul>
                        </div>
                    </div>

                @auth
                    <div class="hun-element-wish--type-1 set-color">
                        @php
                            $unread_messages = \App\Models\Message::where('to_user_id', auth()->user()->id)->where('read', false)->count();
                        @endphp
                        <a href="javascript:loadConversations();" id="nav-messages" class="btn-open @if($unread_messages > 0) btn-wish @endif" data-count-wish="{{ $unread_messages }}">
                            <i class="icon ion-ios-chatbubble-outline" style="-webkit-text-stroke: 1px;"></i>
                        </a>
                    </div>

                    <div class="hun-element-cart--type-1 set-color">
                        @php
                            $unread_notifications = \App\Models\Notification::where('user_id', auth()->user()->id)->where('read', false)->count();
                            $unread_notifications += sizeof(auth()->user()->getReturnNotifications());
                            $unread_notifications += sizeof(auth()->user()->getOrdersToShipNotifications());
                        @endphp
                        <a href="#" id="nav-notifications" class="btn-open @if($unread_notifications > 0) btn-cart @endif" data-count-cart="{{ $unread_notifications }}">
                            <i class="icon ion-android-notifications-none"></i>
                        </a>

                        <div class="content-cart">
                            <div class="total-cart">
									<span class="text-total">Notifications</span>
                            </div>

                            <div class="products-cart" style="min-height: 4em;" id="notifications-container">
                                @foreach(auth()->user()->getReturnNotifications() as $return_notification)
                                    <div class="item-product" onclick="location.href='{{ $return_notification->getLink() }}';" >
                                        {!! $return_notification->getHTML() !!}
                                    </div>
                                @endforeach

                                @foreach(auth()->user()->getOrdersToShipNotifications() as $return_notification)
                                    <div class="item-product" onclick="location.href='{{ $return_notification->getLink() }}';" >
                                        {!! $return_notification->getHTML() !!}
                                    </div>
                                @endforeach

                                @foreach(\App\Models\Notification::where('user_id', auth()->user()->id)->where('created_at', '>=', \Carbon\Carbon::now()->subDays(10)->toDateTimeString())->get() as $notification)
                                    <div class="item-product" onclick="location.href='{{ $notification->getLink() }}';" >
                                        {!! $notification->getHTML() !!}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>

                    <div class="hidden-phone hun-element-wish--type-1 set-color">
                        <a href="/closet?id={{ \App\Models\Closet::where('user_id', auth()->user()->id)->first()->id }}&tab=loves" class="btn-open btn-wish">
                            <i class="icon ion-android-favorite-outline"></i>
                        </a>
                    </div>

                @endauth

                    <!-- ACCOUNT ICON -->
                @auth
                    <div class="hidden-phone hun-element-cart--type-1 set-color btn-acc">
                        <a class="btn-open mfp-inline" href="#" id="account-btn">
                            <i class="icon ion-android-person"></i>
                        </a> 
                        <div class="content-cart">
                            <div class="buttons-cart">

                                <a href="/closet?id={{ \App\Models\Closet::where('user_id', auth()->user()->id)->first()->id }}" class="btn-clean btn-white">
                                    My Closet
                                </a>

                                <a href="/orders" class="btn-clean btn-white">
                                    My Orders
                                </a>

                                @if(auth()->user()->isSeller())
                                    <a href="/seller-tools?tab=0" class="btn-clean btn-white">
                                        Seller Dashboard
                                    </a>

                                    @if(auth()->user()->PayPalAccount() == null)
                                        <button type="button" class="btn-clean btn-white w-100" onclick="window.open('{{ \App\PayPalHelper::getConnectUrl() }}', '_blank')">
                                                Connect PayPal
                                        </button>
                                    @endif
                                @endif


                                <a href="/account" class="btn-clean btn-white">
                                    Account Settings
                                </a>
                                <form method="POST" action="{{ route('logout') }}" style="margin-top: 4em;">
                                    @csrf
                                    <button type="submit" class="btn-clean btn-white" style="color: red;">
                                        Logout
                                    </button>
                                </form>

                                @if(auth()->user()->isSeller())
                                    @php
                                        $paypal_account = auth()->user()->PayPalAccount();
                                        $application_fee_percent = auth()->user()->getCommission();
                                        $credit_balance = \App\Models\Order::where('seller_id', auth()->user()->id)->where('paid_out', false)->sum('cost_item');
                                        $pending_credit_balance = $credit_balance - ($credit_balance * $application_fee_percent);
                                    @endphp
                                    <h5 style="margin-top: 1em;">Pending Balance: $<span id="pending_balance">{{ number_format($pending_credit_balance, 2, '.', ',') }}</span></h5>
                                @endif
                            </div>	
                        </div>
                    </div>

                @else

                    <div class="hun-element-account--type-1 set-color js-call-magnificpopup" data-popupinside="true">
                        <a class="btn-open mfp-inline js-open-popup" href="#account-form-01" id="account-btn">
                            <i class="icon ion-android-person"></i>
                        </a>

                        <div id="account-form-01" class="content-element mfp-hide">
                            <div class="tabs-account">
                                <!-- Login Tab -->
                                <input id="account-form-01-login" type="radio" class="toggle-tab" name="account-form-01-toggle" checked>
                                <label for="account-form-01-login" class="label-tab">Login</label>
                                <div class="content-tab" >
                                    <h2 class="title-form">
                                        Login
                                    </h2>

                                    <form id="login-form" method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <label>
                                            <input id="login-email" type="email" class="input-field" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                                        </label>

                                        <label>
                                            <input id="login-password" type="password" class="input-field" name="password" required autocomplete="current-password" placeholder="Password">
                                        </label>

                                        <label>
                                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            Remember me
                                        </label>

                                        <label id="login-error" style="color: red;text-align: center;"></label>

                                        <button type="submit" class="btn-clean">
                                            Sign In
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Register Tab -->
                                <input id="account-form-01-register" type="radio" class="toggle-tab" name="account-form-01-toggle">
                                <label for="account-form-01-register" class="label-tab">Register</label>
                                <div class="content-tab">
                                    <h2 class="title-form">
                                        Register
                                    </h2>

                                    <form method="POST" action="{{ route('register') }}" id="register-form">
                                        @csrf
                                        <label>
                                            <input id="register-username" type="text" class="input-field" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="Username">
                                            <label id="register-username-error" style="color:red;"></label>
                                        </label>

                                        <label>
                                            <input id="register-email" type="email" class="input-field" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email">
                                            <label id="register-email-error" style="color:red;"></label>
                                        </label>

                                        <label>
                                            <input id="register-password" type="password" class="input-field" name="password" required autocomplete="new-password" placeholder="Password">
                                        </label>

                                        <label>
                                            <input id="register-password-confirm" type="password" class="input-field" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                                        </label>

                                        <label>
                                            {!! NoCaptcha::display() !!}
                                            <label id="g-recaptcha-response-error" style="color:red;"></label>
                                        </label>

                                        <button id="btn-register" type="submit" class="btn-clean">
                                            Sign Up
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Lost Password Tab -->
                                <input id="account-form-01-lost-password" type="radio" class="toggle-tab" name="account-form-01-toggle">
                                <label for="account-form-01-lost-password" class="label-tab">Lost Password</label>
                                <div class="content-tab">
                                    <h2 class="title-form">
                                        Reset Password
                                    </h2>

                                    <form action="/reset-password" method="POST">
                                    @csrf
                                        <p class="description-form">
                                            Please enter your email address. You will receive a link to create a new password via email.
                                        </p>

                                        <label>
                                            <input class="input-field" type="email" name="email" placeholder="Email">
                                        </label>

                                        <button class="btn-clean" type="submit">
                                            Reset Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endauth
                    <!-- END ACCOUNT ICON -->

                    <div class="hun-menu-mobile set-color" style="margin-right: -1.2em;">
                        <a href="#" class="btn-toggle-menu"><span></span></a>

                        <div class="mobile-navigation" id="mobile-menu-nav" style="visibility: visible;">
                            <nav class="mobile-menu">
                                <ul class="list-menu set-color">
                                    @if(! \Request::is('home') && ! \Request::is('/'))
                                    <li class="menu-item">
                                        <a href="/">
                                            Home
                                        </a>
                                    </li>
                                    @endif

                                    <li class="menu-item menu-item-has-children {{ request()->is('featured') ? 'current-menu-item' : '' }}">
                                        <a href="#">
                                            Shop
                                        </a>

                                        <ul class="sub-menu">
                                            <li class="menu-item">
                                                <a href="/shop">Featured</a>
                                            </li>
                                            @foreach(\App\Models\Category::where('parent_category', null)->get() as $c)
                                            <li class="menu-item">
                                                <a href="/shop?category={{ $c->id }}">{{ $c->name }}</a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </li>

                                    @auth
                                    <li class="menu-item {{ request()->is('create-listing') ? 'current-menu-item' : '' }}">
                                        <a href="/create-listing">
                                            Create Listing
                                        </a>
                                    </li>

                                    <li class="menu-item {{ request()->is('/closet') ? 'current-menu-item' : '' }}">
                                        <a href="/closet?id={{ \App\Models\Closet::where('user_id', auth()->user()->id)->first()->id }}">
                                            My Closet
                                        </a>
                                    </li>

                                    <li class="menu-item {{ request()->is('/orders') ? 'current-menu-item' : '' }}">
                                        <a href="/orders">
                                            My Orders
                                        </a>
                                    </li>

                                    @if(auth()->user()->isSeller())
                                    <li class="menu-item {{ request()->is('/seller-tools') ? 'current-menu-item' : '' }}">
                                        <a href="/seller-tools?tab=0">
                                            Seller Tools
                                        </a>
                                    </li>
                                    @endif

                                    <li class="menu-item {{ request()->is('/account') ? 'current-menu-item' : '' }}">
                                        <a href="/account">
                                            Account Settings
                                        </a>
                                    </li>

                                    @endauth

                                </ul>
                            </nav>		

                            @auth
                             <form method="POST" action="{{ route('logout') }}" id="form-logout" style=" position: absolute;bottom: 0;left: 0;padding-left: 2em;margin-bottom: 2em;">
                                @csrf
                                <li class="menu-item">
                                    <a href="#" onclick="document.getElementById('form-logout').submit();">
                                        Logout
                                    </a>
                                </li>
                            </form>	
                            @endauth		
                        </div>

                        @if(\Request::is('shop'))
                        <div class="mobile-navigation" id="refine-search-nav" style="visibility: hidden;">
                            <nav class="mobile-menu">
                                <ul class="list-menu set-color">
                                    
                                @if(isset($criteria) && isset($hide_sold))
                                    @php
                                        $criteria_url = "hide_sold=" . $hide_sold . "&criteria=" . $criteria;
                                    @endphp
                                @else
                                    @php
                                        $criteria_url = "hide_sold=false&criteria=1";
                                    @endphp
                                @endif              

                                <div class="filter-product set-color">
                                    <div class="options-filter">

                                        <div class="group-options">
                                            <h6 class="title-group">
                                                Category @if(isset($category)) - {{ $category->name }} @endif
                                            </h6>

                                            <div class="list-options" id="filter-categories">
                                            @if(isset($category))
                                                <label class="item-option" style="padding-bottom: 1em;">
                                                    <a href="/shop?{{ $criteria_url }}">< All Categories</a>
                                                </label>
                                                @if($category->parent_category != null)
                                                    <label class="item-option">
                                                        <a href="/shop?category={{ $category->parent_category }}&{{ $criteria_url }}">< {{ \App\Models\Category::where('id', $category->parent_category)->first()->name }}</a>
                                                    </label>
                                                @endif
                                                
                                                @foreach(\App\Models\Category::where('parent_category', $category->id)->get() as $cat)
                                                <label class="item-option">
                                                    <a href="/shop?category={{ $cat->id }}&{{ $criteria_url }}">{{ $cat->name }}</a>
                                                </label>
                                                @endforeach
                                            @else
                                                @foreach(\App\Models\Category::where('parent_category', null)->get() as $cat)
                                                <label class="item-option">
                                                    <a href="/shop?category={{ $cat->id }}&{{ $criteria_url }}">{{ $cat->name }}</a>
                                                </label>
                                                @endforeach
                                            @endif
                                            </div>
                                        </div>

                                        @if(isset($category) && \App\Models\Size::where('category', ($category->parent_category != null ? $category->parent_category : $category->id))->count() > 0)
                                        <div class="group-options">
                                            <h6 class="title-group">
                                                <a href="#">Size</a>
                                            </h6>

                                            <div class="list-options" id="filter-sizes">
                                                @foreach(\App\Models\Size::where('category', ($category->parent_category != null ? $category->parent_category : $category->id))->get() as $size)
                                                <label class="item-option">
                                                    <input type="checkbox">
                                                    <span class="icon-checkbox"></span>
                                                    {{ $size->name }}
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif

                                        <div class="group-options" style="margin-top: 2em;">
                                            <h6 class="title-group">
                                                Sort By
                                            </h6>

                                            <div class="list-options" id="filter-sizes">
                                                <form action="/shop" method="GET" id="form-sort">
                                                    @if(isset($category))
                                                    <input type="hidden" value="{{ $category->id }}" name="category" />
                                                    @endif
                                                    <input type="hidden" value="{{ ($hide_sold ? 'true' : 'false') }}" id="hide_sold" name="hide_sold" />
                                                    <select class="select-field" id="select-sort" name="criteria">
                                                        <option value="0" @if($criteria == 0) selected @endif>Featured</option>
                                                        <option value="1" @if($criteria == 1) selected @endif>Most Popular</option>
                                                        <option value="2" @if($criteria == 2) selected @endif>Most Recent</option>
                                                        <option value="3" @if($criteria == 3) selected @endif>Price (Low To High)</option>
                                                        <option value="4" @if($criteria == 4) selected @endif>Price (High To Low)</option>
                                                    </select>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="group-options" style="margin-top: 2em;">
                                            <div class="list-options">
                                                <label>
                                                    @if($hide_sold)
                                                        <input type="checkbox" id="hide-sold-items" checked />
                                                    @else
                                                        <input type="checkbox" id="hide-sold-items" />
                                                    @endif
                                                    Hide Sold Items
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                               

                                </ul>
                            </nav>		
                        </div>
                        @endif

                    </div>



                </div>
            </div>
        </div>
    </div>
</header>
<!-- end Header -->
