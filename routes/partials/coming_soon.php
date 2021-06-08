<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use \App\Models\ShippingInfo;
use \App\AlertHelper;

Route::get('{any}', function() {
    return view('coming_soon');
 })->where('any', '.*');


Route::post('/subscribe_email', function() {
    try {
        $email_subscriber = new \App\Models\EmailSubscriber();
        $email_subscriber->email = \request('email');
        $email_subscriber->save();
    } catch(\Exception $e) {}

    return view('coming_soon')->with('subscribed', true);
 })->where('any', '.*');

Route::get('/coming_soon', function () {
    return view('coming_soon');
});
