<?php

namespace App\StripeWebhooks;

use Illuminate\Support\Facades\Session;
use \App\Models\Order;
use \App\Models\StripeAccount;
use \App\Models\Product;
use Nexmo\Laravel\Facade\Nexmo;
use \App\Http\Controllers\MailController;

class PaymentSucceededWebhook
{

    public static function handle($data) 
    {
        // check that the payment was successful
        if($data['status'] !== 'succeeded') return;

        // double check that the payment was fully paid
        if(! $data['charges']['data'][0]['paid']) return;

        $customer_id = $data['charges']['data'][0]['customer'];
        $receipt_url = $data['charges']['data'][0]['receipt_url'];
        $rental = $data['metadata']['rental'] == 'true';
        $cost_shipping = $data['metadata']['cost_shipping'];
        $shipping_id = $data['metadata']['shipping_id'];
        $cost_item = $data['metadata']['cost_item'];
        $user_id = StripeAccount::where('customer_id', $customer_id)->first()->user_id;
        $seller_id = $data['metadata']['seller_id'];
        $product_id = $data['metadata']['product_id'];
        $charge_id = $data['charges']['data'][0]['id'];

        $user = \App\Models\User::where('id', $user_id)->first();
        $seller = \App\Models\User::where('id', $seller_id)->first();
        $product = \App\Models\Product::where('id', $product_id)->first();

        $order = Order::create([
            'product_id' => $product_id,
            'seller_id' => $seller_id,
            'user_id' => $user_id,
            'shipping_id' => $shipping_id,
            'stripe_charge_id' => $charge_id,
            'cost_shipping' => $cost_shipping,
            'cost_item' => $cost_item,
            'rental' => $rental,
            'receipt_url' => $receipt_url,
            'credit' => $data['charges']['data'][0]['destination'] == null
        ]);

        $order_notification = \App\Models\Notification::create([
            'user_id' => $seller_id,
            'handler' => '\App\NotificationHandlers\NewOrderNotification',
            'metadata' => [
                'order_id' => $order->id
            ]
        ]);

        event(new \App\Events\NewNotification($order_notification));

        $send_to_number_user = str_replace('-', '', \App\Models\ShippingInfo::where('id', $shipping_id)->first()->phone_number);
        $send_to_number_user = str_replace('+', '', $send_to_number_user);
        $send_to_number_user = trim($send_to_number_user);

        $send_to_number_seller = str_replace('-', '', \App\Models\ShippingInfo::where('user_id', $seller_id)->where('default', true)->first()->phone_number);
        $send_to_number_seller = str_replace('+', '', $send_to_number_seller);
        $send_to_number_seller = trim($send_to_number_seller);

        $basic  = new \Nexmo\Client\Credentials\Basic(env('NEXMO_KEY'), env('NEXMO_SECRET'));
        $client = new \Nexmo\Client($basic);

        // MailController::basic_email('cmchenry1996@icloud.com', 'colbymchenry@gmail.com', 'New Order!', 'You got a new order!');

        if($user->TextSettings()->order_receipt) {

            $message = "MyRaveCloset.com\n\n";
        
            if($rental) {
                $message .= "Thank you for your rental!\n\n";
                $message .= "Item: " . $product->title;
                $message .= "\n\nPlease return the item by: " . date('F, d', strtotime($now . ' + 7 days'));
                $message .= "\n\nYou are fully responsible if the item is damaged or stolen. Please take care of other ravers belongings while you use them. :)\n\n";
            } else {
                $message .= "Thank you for your purchase!\n\n";
                $message .= "Item: " . $product->title . "\n\n";
            }
    
            $message .= "Receipt: " . $receipt_url;

            $client->message()->send([
                'to' => $send_to_number_user,
                'from' => '13463937612',
                'text' => $message . "\n\n"
            ]);
        }

        if($seller->TextSettings()->order_new) {

            $message = "MyRaveCloset.com\n\nYou have a new order!";

            $message .= "\n\nItem: " . $product->title; 
        
            $message .= "\n\nPlease ship the item by: " . date('F, d', strtotime($now . ' + 2 days'));
    
            $client->message()->send([
                'to' => $send_to_number_seller,
                'from' => '13463937612',
                'text' => $message . "\n\n"
            ]);
        }
    }

    // [2020-09-26 08:42:42] local.INFO: array (
    //     'id' => 'pi_1HVYz7Kf7QdPAzcChSSLEx6g',
    //     'object' => 'payment_intent',
    //     'amount' => 5692,
    //     'amount_capturable' => 0,
    //     'amount_received' => 5692,
    //     'application' => NULL,
    //     'application_fee_amount' => 750,
    //     'canceled_at' => NULL,
    //     'cancellation_reason' => NULL,
    //     'capture_method' => 'automatic',
    //     'charges' => 
    //     array (
    //       'object' => 'list',
    //       'data' => 
    //       array (
    //         0 => 
    //         array (
    //           'id' => 'ch_1HVYzLKf7QdPAzcCOzJCvPFs',
    //           'object' => 'charge',
    //           'amount' => 5692,
    //           'amount_captured' => 5692,
    //           'amount_refunded' => 0,
    //           'application' => NULL,
    //           'application_fee' => 'fee_1HVYzMFMWlH6sRXnMkb7M6QI',
    //           'application_fee_amount' => 750,
    //           'balance_transaction' => 'txn_1HVYzMKf7QdPAzcCJrL2fR1j',
    //           'billing_details' => 
    //           array (
    //             'address' => 
    //             array (
    //               'city' => NULL,
    //               'country' => 'US',
    //               'line1' => NULL,
    //               'line2' => NULL,
    //               'postal_code' => '30326',
    //               'state' => NULL,
    //             ),
    //             'email' => 'safa777@gmail.com',
    //             'name' => 'Safa Asadi',
    //             'phone' => NULL,
    //           ),
    //           'calculated_statement_descriptor' => 'Stripe',
    //           'captured' => true,
    //           'created' => 1601109759,
    //           'currency' => 'usd',
    //           'customer' => 'cus_I5kUOttIyfkx9k',
    //           'description' => NULL,
    //           'destination' => 'acct_1HVYLxFMWlH6sRXn',
    //           'dispute' => NULL,
    //           'disputed' => false,
    //           'failure_code' => NULL,
    //           'failure_message' => NULL,
    //           'fraud_details' => 
    //           array (
    //           ),
    //           'invoice' => NULL,
    //           'livemode' => false,
    //           'metadata' => 
    //           array (
    //             'rental' => 'false',
    //           ),
    //           'on_behalf_of' => NULL,
    //           'order' => NULL,
    //           'outcome' => 
    //           array (
    //             'network_status' => 'approved_by_network',
    //             'reason' => NULL,
    //             'risk_level' => 'normal',
    //             'risk_score' => 39,
    //             'seller_message' => 'Payment complete.',
    //             'type' => 'authorized',
    //           ),
    //           'paid' => true,
    //           'payment_intent' => 'pi_1HVYz7Kf7QdPAzcChSSLEx6g',
    //           'payment_method' => 'pm_1HVYzKKf7QdPAzcCrEDt4qGe',
    //           'payment_method_details' => 
    //           array (
    //             'card' => 
    //             array (
    //               'brand' => 'visa',
    //               'checks' => 
    //               array (
    //                 'address_line1_check' => NULL,
    //                 'address_postal_code_check' => 'pass',
    //                 'cvc_check' => 'pass',
    //               ),
    //               'country' => 'US',
    //               'exp_month' => 7,
    //               'exp_year' => 2022,
    //               'fingerprint' => 'qAfPjItAsyxphqTh',
    //               'funding' => 'credit',
    //               'installments' => NULL,
    //               'last4' => '4242',
    //               'network' => 'visa',
    //               'three_d_secure' => NULL,
    //               'wallet' => NULL,
    //             ),
    //             'type' => 'card',
    //           ),
    //           'receipt_email' => NULL,
    //           'receipt_number' => NULL,
    //           'receipt_url' => 'https://pay.stripe.com/receipts/acct_1HR6kAKf7QdPAzcC/ch_1HVYzLKf7QdPAzcCOzJCvPFs/rcpt_I5kUDLisGO4xPCzpL0G7zWGzUIOtCGI',
    //           'refunded' => false,
    //           'refunds' => 
    //           array (
    //             'object' => 'list',
    //             'data' => 
    //             array (
    //             ),
    //             'has_more' => false,
    //             'total_count' => 0,
    //             'url' => '/v1/charges/ch_1HVYzLKf7QdPAzcCOzJCvPFs/refunds',
    //           ),
    //           'review' => NULL,
    //           'shipping' => NULL,
    //           'source' => NULL,
    //           'source_transfer' => NULL,
    //           'statement_descriptor' => NULL,
    //           'statement_descriptor_suffix' => NULL,
    //           'status' => 'succeeded',
    //           'transfer' => 'tr_1HVYzLKf7QdPAzcC3SjlhQwV',
    //           'transfer_data' => 
    //           array (
    //             'amount' => NULL,
    //             'destination' => 'acct_1HVYLxFMWlH6sRXn',
    //           ),
    //           'transfer_group' => 'group_pi_1HVYz7Kf7QdPAzcChSSLEx6g',
    //         ),
    //       ),
    //       'has_more' => false,
    //       'total_count' => 1,
    //       'url' => '/v1/charges?payment_intent=pi_1HVYz7Kf7QdPAzcChSSLEx6g',
    //     ),
    //     'client_secret' => 'pi_1HVYz7Kf7QdPAzcChSSLEx6g_secret_PPq3BLHBqmaoMDjCTAbhgD8mj',
    //     'confirmation_method' => 'automatic',
    //     'created' => 1601109745,
    //     'currency' => 'usd',
    //     'customer' => 'cus_I5kUOttIyfkx9k',
    //     'description' => NULL,
    //     'invoice' => NULL,
    //     'last_payment_error' => NULL,
    //     'livemode' => false,
    //     'metadata' => 
    //     array (
    //       'rental' => 'false',
    //     ),
    //     'next_action' => NULL,
    //     'on_behalf_of' => NULL,
    //     'payment_method' => 'pm_1HVYzKKf7QdPAzcCrEDt4qGe',
    //     'payment_method_options' => 
    //     array (
    //       'card' => 
    //       array (
    //         'installments' => NULL,
    //         'network' => NULL,
    //         'request_three_d_secure' => 'automatic',
    //       ),
    //     ),
    //     'payment_method_types' => 
    //     array (
    //       0 => 'card',
    //     ),
    //     'receipt_email' => NULL,
    //     'review' => NULL,
    //     'setup_future_usage' => NULL,
    //     'shipping' => NULL,
    //     'source' => NULL,
    //     'statement_descriptor' => NULL,
    //     'statement_descriptor_suffix' => NULL,
    //     'status' => 'succeeded',
    //     'transfer_data' => 
    //     array (
    //       'destination' => 'acct_1HVYLxFMWlH6sRXn',
    //     ),
    //     'transfer_group' => 'group_pi_1HVYz7Kf7QdPAzcChSSLEx6g',
    //   )  
      

}