<?php

use PayPal\Api\OpenIdSession;
use PayPal\Api\OpenIdTokeninfo;
use PayPal\Api\OpenIdUserinfo;
use PayPal\Api\Payment;
use PayPal\Api\Invoice;
use \App\Models\PayPalAccount;
use \App\AlertHelper;

Route::get('/paypal/webhooks', function () {
});

Route::get('/paypal/checkout', [App\PayPalHelper::class, 'performCheckout']);

Route::get('/paypal/connect', function() {
    if(request('code')) {
        $apiContext = \App\PayPalHelper::getApiContext();
        try {
            $tokenInfo = OpenIdTokeninfo::createFromAuthorizationCode(array('code' => request('code')), null, null, $apiContext);
            $params = array('access_token' => $tokenInfo->getAccessToken());
            $userInfo = OpenIdUserinfo::getUserinfo($params, $apiContext);

            if(! PayPalAccount::where('payer_id', $userInfo->payer_id)->exists()) {
                PayPalAccount::create([
                    'user_id' => auth()->user()->id,
                    'email' => $userInfo->email,
                    'payer_id' => str_replace('https://www.paypal.com/webapps/auth/identity/user/', '', $userInfo->user_id),
                    'refresh_token' => $tokenInfo->refresh_token
                ]);
            } 

            AlertHelper::alertSuccess('PayPal account connected!');
        } catch(\PayPal\Exception\PayPalConnectionException $e) {
            \Log::error($e->getData());
            AlertHelper::alertWarning($e->getMessage());
        }
    } else {
        AlertHelper::alertWarning('Invalid code.');
    }
    
    return redirect('/account');
});

Route::post('/paypal/withdraw', function() {
    $apiContext = \App\PayPalHelper::getApiContext();
    try {

        foreach(\App\Models\Order::get() as $order) {
            $payment = Payment::get($order->paypal_payment_id, $apiContext);

            
            \Log::info($payment);
        }

    } catch(\PayPal\Exception\PayPalConnectionException $e) {
        \Log::error($e->getData());
        AlertHelper::alertWarning($e->getMessage());
    }
});