<?php

namespace App\StripeWebhooks;

use Illuminate\Support\Facades\Session;

class ApplicationFeeRefundedWebhook
{

    public static function handle($data) 
    {
        \Log::info('APP FEE REFUND WEBHOOK!');
        \Log::info($data);
    }

    // array (
    //     'id' => 'cs_test_HR7Zl7XthbnVMyrrKVeLF6XqPOXGPH1C6mTb6e2yORqBltyc0gOBT8dH',
    //     'object' => 'checkout.session',
    //     'allow_promotion_codes' => NULL,
    //     'amount_subtotal' => 5000,
    //     'amount_total' => 5000,
    //     'billing_address_collection' => NULL,
    //     'cancel_url' => 'https://myravecloset.com/checkout?success=false&product_id=1',
    //     'client_reference_id' => NULL,
    //     'currency' => 'usd',
    //     'customer' => 'cus_I4wOYIPmK1rBBe',
    //     'customer_email' => 'safa999@gmail.com',
    //     'livemode' => false,
    //     'locale' => NULL,
    //     'metadata' => 
    //     array (
    //     ),
    //     'mode' => 'payment',
    //     'payment_intent' => 'pi_1HUmUZKf7QdPAzcCtWOEcEQD',
    //     'payment_method_types' => 
    //     array (
    //       0 => 'card',
    //     ),
    //     'payment_status' => 'paid',
    //     'setup_intent' => NULL,
    //     'shipping' => 
    //     array (
    //       'address' => 
    //       array (
    //         'city' => 'Buckhead',
    //         'country' => 'US',
    //         'line1' => 'Park Ave NE',
    //         'line2' => 'Unit 1553',
    //         'postal_code' => '30326',
    //         'state' => 'GA',
    //       ),
    //       'name' => 'Safa Asadi',
    //     ),
    //     'shipping_address_collection' => 
    //     array (
    //       'allowed_countries' => 
    //       array (
    //         0 => 'US',
    //       ),
    //     ),
    //     'submit_type' => NULL,
    //     'subscription' => NULL,
    //     'success_url' => 'https://myravecloset.com/checkout?success=true&product_id=1',
    //     'total_details' => 
    //     array (
    //       'amount_discount' => 0,
    //       'amount_tax' => 0,
    //     ),
    //   )  
      

}