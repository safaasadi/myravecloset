@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
                    
                <div class="hun-element-account--type-1 set-color js-call-magnificpopup" data-popupinside="true">
                        <div id="account-form-02" class="content-element">
                            <div class="tabs-account">
                                <!-- Login Tab -->
                                <input id="account-form-02-login" type="radio" class="toggle-tab" name="account-form-02-toggle" checked>
                                <label for="account-form-02-login" class="label-tab">Login</label>
                                <div class="content-tab">
                                    <h2 class="title-form">
                                        Login
                                    </h2>

                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <label>
                                            <input id="email" type="email" class="input-field" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                                        </label>

                                        <label>
                                            <input id="password" type="password" class="input-field" name="password" required autocomplete="current-password" placeholder="Password">
                                        </label>

                                        <label>
                                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>>
                                            Remember me
                                        </label>

                                        <button type="submit" class="btn-clean">
                                            Sign In
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Register Tab -->
                                <input id="account-form-02-register" type="radio" class="toggle-tab" name="account-form-02-toggle">
                                <label for="account-form-02-register" class="label-tab">Register</label>
                                <div class="content-tab">
                                    <h2 class="title-form">
                                        Register
                                    </h2>

                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf
                                        <label>
                                            <input id="username" type="text" class="input-field" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="Username">
                                        </label>

                                        <label>
                                            <input id="email" type="email" class="input-field" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email">
                                        </label>

                                        <label>
                                            <input id="password" type="password" class="input-field" name="password" required autocomplete="new-password" placeholder="Password">
                                        </label>

                                        <label>
                                            <input id="password-confirm" type="password" class="input-field" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                                        </label>

                                        <button type="submit" class="btn-clean">
                                            Sign Up
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Lost Password Tab -->
                                <input id="account-form-02-lost-password" type="radio" class="toggle-tab" name="account-form-02-toggle">
                                <label for="account-form-02-lost-password" class="label-tab">Lost Password</label>
                                <div class="content-tab">
                                    <h2 class="title-form">
                                        Reset Password
                                    </h2>

                                    <form>
                                        <p class="description-form">
                                            Please enter your username or email address. You will receive a link to create a new password via email.
                                        </p>

                                        <label>
                                            <input class="input-field" type="text" placeholder="Username or Email">
                                        </label>

                                        <button class="btn-clean">
                                            Reset Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


        </div>
    </div>
</div>
@endsection
