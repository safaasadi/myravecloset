<?php

namespace App\Http\Controllers;

use App\AlertHelper;
use Illuminate\Http\Request;

class OrderController extends Controller {

    public function setup(Request $request) {
        if(!\App\Models\Product::where('id', $request['product_id'])->exists()) {
            return response()->json(['success' => false, 'msg' => 'This product is no longer active.']);
        }

        $product = \App\Models\Product::where('id', $request['product_id'])->first();

        $redirect_link = \App\PayPalHelper::setupCheckout($product, $request['rent'] == 'true');

        return $redirect_link;

        // $closet = \App\Models\Closet::where('id', $product->closet_id)->first();
        // $stripe_account = \App\Models\StripeAccount::where('user_id', $closet->user_id)->first();
        // $application_fee_percent = $stripe_account->application_fee_percent == null ? env('DEFAULT_COMMISSION') : $stripe_account->application_fee_percent;
        // $stripe_express_id = $stripe_account->express_id;
        // $shipping_cost = $product->estimateShipping() * 100;
        // $total_price = $shipping_cost;

        // if($product->user_id == auth()->user()->id) {
        //     return response()->json(['success' => false, 'msg' => 'You cannot buy/rent from yourself.']);
        // }

        // if($request['rent'] == 'true') {
        //     if($product->rental_price_id == null) {
        //         return response()->json(['success' => false, 'msg' => 'This product is not for rent.']);
        //     }

        //     if(!$product->active) {
        //         return response()->json(['success' => false, 'msg' => 'This product is already being rented.']);
        //     }

        //     $price_id = $product->rental_price_id;
        //     $total_price += $product->rental_price;
        //     // take out shipping costs from percentage we take so seller gets all shipping funds
        //     $application_fee_amount = $product->rental_price * $application_fee_percent;
        // } else {
        //     if($product->purchase_price_id == null) {
        //         return response()->json(['success' => false, 'msg' => 'This product is not for sale.']);
        //     }

        //     if(!$product->active) {
        //         return response()->json(['success' => false, 'msg' => 'This product is no longer available.']);
        //     }
            
        //     $price_id = $product->purchase_price_id;
        //     $total_price += $product->purchase_price;
        //     // take out shipping costs from percentage we take so seller gets all shipping funds
        //     $application_fee_amount = $product->purchase_price * $application_fee_percent;
        // }

        // $payment_intent_data = [
        //     'metadata' => [
        //         'rental' => $request['rent'] == 'true',
        //         'product_id' => $product->id,
        //         'stripe_id' => $product->stripe_id,
        //         'seller_id' => $stripe_account->user_id,
        //         'cost_shipping' => $shipping_cost,
        //         'shipping_id' => \App\Models\ShippingInfo::where('user_id', auth()->user()->id)->where('default', true)->first()->id,
        //         'cost_item' => $request['rent'] == 'true' ? $product->rental_price : $product->purchase_price
        //     ]
        // ];

        // if($stripe_express_id !== null) {
        //     $payment_intent_data['application_fee_amount'] = round($application_fee_amount + $shipping_cost);
        //     $payment_intent_data['transfer_data'] = ['destination' => $stripe_express_id];
        // }

        // $checkout_data = [
        //     'payment_method_types' => ['card'],
        //     'mode' => 'payment',
        //     'customer' => auth()->user()->StripeAccount()->customer_id,
        //     'line_items' => [
        //         [
        //             'price_data' => [
        //                 'currency' => 'USD',
        //                 'product' => $product->stripe_id,
        //                 'unit_amount_decimal' => $total_price
        //             ],
        //             'quantity' => 1
        //         ]
        //     ],
        //     'payment_intent_data' => $payment_intent_data,
        //     'success_url' => env('APP_URL') . '/checkout?success=true&product_id=' . $product->id,
        //     'cancel_url' => env('APP_URL') . '/checkout?success=false&product_id=' . $product->id,
        // ];

        // \Stripe\Stripe::setApiKey(env('STRIPE_CLIENT_SECRET'));
        // $session = \Stripe\Checkout\Session::create($checkout_data);

        // return response()->json(['success' => true, 'msg' => $session->id]);
    }

    public function checkout() {
        $success = \request('success');
        $product = \App\Models\Product::where('id', \request('product_id'))->first();

        if($success == 'true') {
            AlertHelper::alertSuccess('Payment successful!');
            $product->active = false;
            $product->save();
         } else 
            AlertHelper::alertWarning('Checkout canceled.');

        return redirect('/orders?tab=0');
    }

}