<?php

namespace App;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payee;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\ShippingAddress;
use PayPal\Api\PaymentExecution;
use PayPal\Api\OpenIdSession;
use PayPal\Api\Invoice;
use App\Models\Product;
use App\Models\Order;
use App\Models\PayPalAccount;
use Carbon\Carbon;
use App\Models\User;

class PayPalHelper
{

    public static function getApiContext() {
        if(env('DEV_ENV') != 'true') {
            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    env('PAYPAL_CLIENT_ID'),
                    env('PAYPAL_CLIENT_SECRET')
                )
            );
        } else {
            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    env('DEV_PAYPAL_CLIENT_ID'),
                    env('DEV_PAYPAL_CLIENT_SECRET')
                )
            );
        }

        if(env('DEV_ENV') != 'true') {
            $apiContext->setConfig([
                'mode' => 'live',
                'log.LogEnabled' => true,
                'log.FileName' => 'PayPal.log',
                'log.LogLevel' => 'FINE'
            ]);
        }

        return $apiContext;
    }

    public static function setupCheckout(Product $product, bool $rental) {
        $apiContext = PayPalHelper::getApiContext();

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $price = ($rental ? $product->rental_price : $product->purchase_price) / 100;
        $shipping = $product->estimateShipping();
        $tax = $price * 0.05;
        $subtotal = $price;
        $total = $subtotal + $tax + $shipping;

        $item1 = new Item();
        $item1->setName($product->title)
        ->setCurrency('USD')
        ->setQuantity(1)
        ->setPrice($price);

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        $shipping_info = \App\Models\ShippingInfo::where('user_id', auth()->user()->id)->where('default', true)->first();

        $shipping_address = new ShippingAddress();
        $shipping_address->setCity($shipping_info->city)
        ->setCountryCode($shipping_info->country)
        ->setPostalCode($shipping_info->postal_code)
        ->setLine1($shipping_info->line1)
        ->setState($shipping_info->state)
        ->setRecipientName($shipping_info->name);

        if($shipping_info->line2 !== null) {
            $shipping_address->setLine2($shipping_info->line2);
        }

        $itemList->setShippingAddress($shipping_address);

        $details = new Details();
        $details->setShipping($shipping)
        ->setTax($tax)
        ->setSubtotal($subtotal);

        $amount = new Amount();
        $amount->setCurrency("USD")
        ->setTotal($total)
        ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription(($rental ? "Rental" : "Purchase") . " from MyRaveCloset.")
        ->setInvoiceNumber(uniqid());

        $baseUrl = env("APP_URL");
        $product_id = $product->id;
        $redirectUrls = new RedirectUrls();

        $urlData = [
            'item' => $product_id,
            'rental' => $rental ? 'true' : 'false',
            'shipping' => \App\Models\ShippingInfo::where('user_id', auth()->user()->id)->where('default', true)->first()->id,
            'shipping_cost' => $shipping
        ];

        $redirectUrls->setReturnUrl("$baseUrl/paypal/checkout?success=true&" . http_build_query($urlData))
        ->setCancelUrl("$baseUrl/paypal/checkout?success=false&" . http_build_query($urlData));

        $payment = new Payment();
        $payment->setIntent("order")
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrls)
        ->setTransactions(array($transaction));

        $request = clone $payment;

        try {
            $payment->create($apiContext);
        }  catch(\PayPal\Exception\PayPalConnectionException $e) {
            \Log::error($e->getData());
            AlertHelper::alertWarning($e->getMessage());
        }

        return $payment->getApprovalLink();
    }

    public static function performCheckout() {
        $apiContext = PayPalHelper::getApiContext();

        if(request('success') && request('success') == 'true') {
            $paymentId = request('paymentId');
            $token = request('token');
            $payerId = request('PayerID');


            try {
                $product = Product::where('id', request('item'))->first();
                $execution = new PaymentExecution();
                $execution->setPayerId($payerId);
                $payment = Payment::get($paymentId, $apiContext);
                $result = $payment->execute($execution, $apiContext);
                $invoice_id = $result->getTransactions()[0]->invoice_number;

                $shipping_id = \App\Models\ShippingInfo::where('id', request('shipping'))->first()->id;
                $shipping_cost = request('shipping_cost');
                $rental = request('rental') == 'true';

                $order = Order::create([
                    'product_id' => $product->id,
                    'seller_id' => $product->user_id,
                    'user_id' => auth()->user()->id,
                    'shipping_id' => $shipping_id,
                    'paypal_payment_id' => $paymentId,
                    'paypal_payer_id' => $payerId,
                    'invoice_id' => $invoice_id,
                    'cost_shipping' => $shipping_cost,
                    'cost_item' => ($rental ? $product->rental_price : $product->purchase_price) / 100,
                    'rental' => $rental
                ]);

                if($product->refund_period > 0) {
                    $order->refund_cutoff = Carbon::now()->addDays($product->refund_period);
                    $order->save();
                }

                $order_notification = \App\Models\Notification::create([
                    'user_id' => $product->user_id,
                    'handler' => '\App\NotificationHandlers\NewOrderNotification',
                    'metadata' => [
                        'order_id' => $order->id
                    ]
                ]);
        
                event(new \App\Events\NewNotification($order_notification));
        
                $send_to_number_seller = str_replace('-', '', \App\Models\ShippingInfo::where('user_id', $product->user_id)->where('default', true)->first()->phone_number);
                $send_to_number_seller = str_replace('+', '', $send_to_number_seller);
                $send_to_number_seller = trim($send_to_number_seller);
        
                $basic  = new \Nexmo\Client\Credentials\Basic(env('NEXMO_KEY'), env('NEXMO_SECRET'));
                $client = new \Nexmo\Client($basic);
        
                // MailController::basic_email('cmchenry1996@icloud.com', 'colbymchenry@gmail.com', 'New Order!', 'You got a new order!');
        
                if(\App\Models\User::where('id', $product->user_id)->first()->TextSettings()->order_new) {
        
                    $message = "MyRaveCloset.com\n\nYou have a new order!";
        
                    $message .= "\n\nItem: " . $product->title; 
                
                    $message .= "\n\nPlease ship the item by: " . date('F, d', strtotime($now . ' + 2 days'));
            
                    $client->message()->send([
                        'to' => $send_to_number_seller,
                        'from' => '13463937612',
                        'text' => $message . "\n\n"
                    ]);

                    $client->message()->send([
                        'to' => '14704955859',
                        'from' => '13463937612',
                        'text' => $message . "\n\n"
                    ]);
                }

            } catch (Exception $ex) {
                Log::error($ex);
                AlertHelper::alertWarning('Order failed.');
                return redirect('/shop');
            }
    
            AlertHelper::alertSuccess('Order successful!');
            return redirect('/orders');
        }
    
        AlertHelper::alertWarning('Order failed.');
        return redirect('/shop');
    }

    public static function getPendingBalance($user_id) {
        $apiContext = \App\PayPalHelper::getApiContext();
        foreach(\App\Models\Order::where('seller_id', $user_id)->where('paid_out', false)->get() as $order) {
            $payment = Payment::get($order->paypal_payment_id, $apiContext);
        }
    }

    public static function payout($orders_obj) {
        \Log::info("DOING PAYOUT.");

        $payouts = new \PayPal\Api\Payout();
        $senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
        $senderBatchHeader->setSenderBatchId(uniqid())
        ->setEmailSubject("You have a payment");
        $payouts->setSenderBatchHeader($senderBatchHeader);
        $orders = array();

        // orders that have not been paid out or shipped
        foreach($orders_obj as $order) {
            if($order->captured) {
                $subtotal = $order->cost_item;
                $seller = User::where('id', $order->seller_id)->first();
                $paypal_acc = PayPalAccount::where('user_id', $seller->id)->first();
                if($paypal_acc !== null) {
                    $app_fee = $seller->getCommission();
                    
                    $amount = $subtotal * (1 - $app_fee);
                    $payout = new \PayPal\Api\PayoutItem();
                    $payout->setRecipientType('Email')
                    ->setNote('Payout from MyRaveCloset.')
                    ->setReceiver($paypal_acc->email)
                    ->setSenderItemId($order->id)
                    ->setAmount(new \PayPal\Api\Currency('{
                                    "value":"' . $amount . '",
                                    "currency":"USD"
                                    }'));
                    
                    $payouts->addItem($payout);
                    array_push($orders, $order);
                } else {
                    // no paypal account found.
                    \Log::info("NO PAYPAL ACCOUNT FOUND FOR USER " . $seller->email);
                }
            }
        }

        if(sizeof($orders) > 0) {
            $request = clone $payouts;

            try {
                $output = $payouts->create(null, PayPalHelper::getApiContext());
                foreach($orders as $order) {
                    $order->paid_out = true;
                    $order->save();
                }
            } catch(\PayPal\Exception\PayPalConnectionException $e) {
                \Log::error($e->getData());
            }
        }
        
        \Log::info("PAYOUT COMPLETE");
    }

    public static function getConnectUrl() {
        $apiContext = PayPalHelper::getApiContext();
        // $redirectUrl = OpenIdSession::getAuthorizationUrl(
        //     env('APP_URL'),
        //     array('openid', 'profile', 'email',
        //         'https://uri.paypal.com/services/paypalattributes',
        //         'https://uri.paypal.com/services/expresscheckout',
        //         'https://uri.paypal.com/services/invoicing'),
        //     null,
        //     null,
        //     null,
        //     $apiContext
        // );

        $baseUrl = 'https://myravecloset.com/paypal/connect';
        
        if(env('DEV_ENV') == 'true') {
            $clientId = env('DEV_PAYPAL_CLIENT_ID');
            return "https://www.sandbox.paypal.com/connect?flowEntry=static&client_id=$clientId&scope=openid+email&redirect_uri=$baseUrl";
        } else {
            $clientId = env('PAYPAL_CLIENT_ID');
            return "https://www.paypal.com/connect?flowEntry=static&client_id=$clientId&scope=openid+email&redirect_uri=$baseUrl";
        }
    }

}