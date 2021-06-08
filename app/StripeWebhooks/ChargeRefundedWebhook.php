<?php

namespace App\StripeWebhooks;

use Illuminate\Support\Facades\Session;

class ChargeRefundedWebhook
{

    public static function handle($data) 
    {
        if(\App\Models\Order::where('stripe_charge_id', $data['id'])->exists()) {
            $order = \App\Models\Order::where('stripe_charge_id', $data['id'])->first();
            $order->refunded = true;
            $order->save();
        }
    }

// [2020-10-02 22:30:36] local.INFO: array (
//   'id' => 'ch_1HXwdoKf7QdPAzcCH0yIiNG8',
//   'object' => 'charge',
//   'amount' => 5692,
//   'amount_captured' => 5692,
//   'amount_refunded' => 5692,
//   'application' => NULL,
//   'application_fee' => 'fee_1HXwdpJckai6NTib7PUTl6yM',
//   'application_fee_amount' => 1692,
//   'balance_transaction' => 'txn_1HXwdpKf7QdPAzcCO5P1KH1S',
//   'billing_details' => 
//   array (
//     'address' => 
//     array (
//       'city' => NULL,
//       'country' => 'US',
//       'line1' => NULL,
//       'line2' => NULL,
//       'postal_code' => '30328',
//       'state' => NULL,
//     ),
//     'email' => 'colby21@gmail.com',
//     'name' => 'Colby McHenry',
//     'phone' => NULL,
//   ),
//   'calculated_statement_descriptor' => 'Stripe',
//   'captured' => true,
//   'created' => 1601677336,
//   'currency' => 'usd',
//   'customer' => 'cus_I8CqC9GrTewaQf',
//   'description' => NULL,
//   'destination' => 'acct_1HXwRlJckai6NTib',
//   'dispute' => NULL,
//   'disputed' => false,
//   'failure_code' => NULL,
//   'failure_message' => NULL,
//   'fraud_details' => 
//   array (
//   ),
//   'invoice' => NULL,
//   'livemode' => false,
//   'metadata' => 
//   array (
//     'rental' => 'false',
//     'product_id' => '1',
//     'stripe_id' => 'prod_I8CtrqkwwyujYW',
//     'cost_shipping' => '692',
//     'shipping_id' => '3',
//     'cost_item' => '5000',
//   ),
//   'on_behalf_of' => NULL,
//   'order' => NULL,
//   'outcome' => 
//   array (
//     'network_status' => 'approved_by_network',
//     'reason' => NULL,
//     'risk_level' => 'normal',
//     'risk_score' => 12,
//     'seller_message' => 'Payment complete.',
//     'type' => 'authorized',
//   ),
//   'paid' => true,
//   'payment_intent' => 'pi_1HXwdYKf7QdPAzcCwlprPRee',
//   'payment_method' => 'pm_1HXwdnKf7QdPAzcCTwa7s6Om',
//   'payment_method_details' => 
//   array (
//     'card' => 
//     array (
//       'brand' => 'visa',
//       'checks' => 
//       array (
//         'address_line1_check' => NULL,
//         'address_postal_code_check' => 'pass',
//         'cvc_check' => 'pass',
//       ),
//       'country' => 'US',
//       'exp_month' => 7,
//       'exp_year' => 2022,
//       'fingerprint' => 'qAfPjItAsyxphqTh',
//       'funding' => 'credit',
//       'installments' => NULL,
//       'last4' => '4242',
//       'network' => 'visa',
//       'three_d_secure' => NULL,
//       'wallet' => NULL,
//     ),
//     'type' => 'card',
//   ),
//   'receipt_email' => NULL,
//   'receipt_number' => NULL,
//   'receipt_url' => 'https://pay.stripe.com/receipts/acct_1HR6kAKf7QdPAzcC/ch_1HXwdoKf7QdPAzcCH0yIiNG8/rcpt_I8D3zJJiFcg6HfppdWZ3WDfL4Hf6RZC',
//   'refunded' => true,
//   'refunds' => 
//   array (
//     'object' => 'list',
//     'data' => 
//     array (
//       0 => 
//       array (
//         'id' => 're_1HXwlrKf7QdPAzcCaEGEHX2I',
//         'object' => 'refund',
//         'amount' => 5692,
//         'balance_transaction' => 'txn_1HXwlsKf7QdPAzcCgt03V1Ka',
//         'charge' => 'ch_1HXwdoKf7QdPAzcCH0yIiNG8',
//         'created' => 1601677835,
//         'currency' => 'usd',
//         'metadata' => 
//         array (
//         ),
//         'payment_intent' => 'pi_1HXwdYKf7QdPAzcCwlprPRee',
//         'reason' => NULL,
//         'receipt_number' => NULL,
//         'source_transfer_reversal' => NULL,
//         'status' => 'succeeded',
//         'transfer_reversal' => NULL,
//       ),
//     ),
//     'has_more' => false,
//     'total_count' => 1,
//     'url' => '/v1/charges/ch_1HXwdoKf7QdPAzcCH0yIiNG8/refunds',
//   ),
//   'review' => NULL,
//   'shipping' => NULL,
//   'source' => NULL,
//   'source_transfer' => NULL,
//   'statement_descriptor' => NULL,
//   'statement_descriptor_suffix' => NULL,
//   'status' => 'succeeded',
//   'transfer' => 'tr_1HXwdpKf7QdPAzcC4KhFydfa',
//   'transfer_data' => 
//   array (
//     'amount' => NULL,
//     'destination' => 'acct_1HXwRlJckai6NTib',
//   ),
//   'transfer_group' => 'group_pi_1HXwdYKf7QdPAzcCwlprPRee',
// )  

      

}