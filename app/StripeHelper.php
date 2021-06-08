<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Stripe\Exception\ApiErrorException;
use \App\Models\User;
use \App\Models\StripeAccount;

class StripeHelper
{

    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function getStripeEmail(): string {
        return $this->getCustomerAccount()->email;
    }

    public function getCustomerAccount(): \Stripe\Customer {
        if (Cache::has('stripe.account.' . $this->user->id)) return Cache::get('stripe.account.' . $this->user->id);
        \Stripe\Stripe::setApiKey(env('STRIPE_CLIENT_SECRET'));
        try {
            Cache::put('stripe.account.' . $this->user->id, \Stripe\Customer::retrieve($this->user->StripeConnect->customer_id), 60 * 10);
            return Cache::get('stripe.account.' . $this->user->id);
        } catch (ApiErrorException $e) {
        }
        return null;
    }

    public function isExpressUser(): bool {
        $stripe_account = StripeAccount::where('user_id', $this->user->id)->first();
        return $stripe_account->express_id !== null;
    }

    public function getBalance() {
        if(Cache::has('stripe.balance.' . $this->user->id)) return Cache::get('stripe.balance.' . $this->user->id);

        $stripe_account = StripeAccount::where('user_id', $this->user->id)->first();

        $balance = \Stripe\Balance::retrieve(
            ['stripe_account' => $stripe_account->express_id]
        );

        Cache::put('stripe.balance.' . $this->user->id, $balance, 60 * 5);
        return Cache::get('stripe.balance.' . $this->user->id);
    }

    public function getLoginURL(): string {
        \Stripe\Stripe::setApiKey(env('STRIPE_CLIENT_SECRET'));
        $stripe_account = StripeAccount::where('user_id', $this->user->id)->first();
        return $this->isExpressUser() ? \Stripe\Account::createLoginLink($stripe_account->express_id)->url : null;
    }

    public static function getAccountFromStripeConnect(string $code): \Stripe\Account {
        $token_request_body = array(
            'client_secret' => env('STRIPE_CLIENT_SECRET'),
            'grant_type' => 'authorization_code',
            'client_id' => env('STRIPE_CLIENT_ID'),
            'code' => $code,
        );

        $req = curl_init('https://connect.stripe.com/oauth/token');
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($req, CURLOPT_POST, true);
        curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
        // TODO: Additional error handling
        $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
        $resp = json_decode(curl_exec($req), true);
        curl_close($req);

        \Stripe\Stripe::setApiKey(env('STRIPE_CLIENT_SECRET'));

        return \Stripe\Account::retrieve($resp['stripe_user_id']);
    }

}