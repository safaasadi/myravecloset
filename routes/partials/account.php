<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use \App\Models\ShippingInfo;
use \App\AlertHelper;

Route::get('/seller-tools', function() {
    return view('seller-tools')->with('tab', request('tab'));
})->name('seller-tools');

Route::post('/add-shipping-info', [App\Http\Controllers\UserController::class, 'addShippingInfo'])->name('add-shipping-info');

Route::get('/account', function () {
    return view('account');
});

Route::post('/update-account', [App\Http\Controllers\UserController::class, 'updateAccount'])->name('update-account');

Route::post('/update-avatar', [App\Http\Controllers\UserController::class, 'updateAvatar'])->name('update-avatar');

Route::get('/connect-stripe', [App\Models\StripeAccount::class, 'connect'])->name('connect-stripe');

Route::post('/post-review', function(Request $request) {
    $id = $request('id');
    $for_product = $request['product'] == 'true';
    $for_user = $request['user'] == 'true';

    if($for_product) {
        $review = new \App\Models\ProductReview();
        $review->product_id = $id;
    } else if($for_user) {
        $review = new \App\Models\ProductReview();
        $review->user_id = $id;
    } else {
        abort(404);
    }

    $review->rating = $request['rating'];
    $review->poster_id = auth()->user()->id;
    $review->content = $request['content'];
    $review->save();
})->name('post-review');

Route::post('/love', function(Request $request) {
    $id = $request['id'];

    $product_exists = \App\Models\Product::where('id', $id)->exists();

    if(!$product_exists) {
        \App\Models\Love::where('product_id', $id)->where('user_id', auth()->user()->id)->delete();
        return response()->json(['success' => false]);
    }

    if(\App\Models\Love::where('product_id', $id)->where('user_id', auth()->user()->id)->exists()) {
        \App\Models\Love::where('product_id', $id)->where('user_id', auth()->user()->id)->delete();
        return response()->json(['success' => true, 'loved' => false]);
    } else {
        $love = new \App\Models\Love();
        $love->product_id = $id;
        $love->user_id = auth()->user()->id;
        $love->save();
        return response()->json(['success' => true, 'loved' => true]);
    }
})->name('love');

Route::post('/validate-shipping-info', function(Request $request) {
    $data = $request->all();
    $data['country_code'] = 'US';
    unset($data['_token']);
    unset($data['phone_number']);
    $ch = curl_post("https://api.shipengine.com/v1/addresses/validate", $data);
    return response()->json($ch);
})->name('validate-shipping-info');

Route::post('/save-shipping-info', function() {
    try {
        $shipping_info = ShippingInfo::create([
            'user_id' => auth()->user()->id,
            'name' => request('name'),
            'phone_number' => '+1 ' . request('phone_number'),
            'line1' => request('address_line1'),
            'line2' => request('address_line2'),
            'city' => request('city_locality'),
            'postal_code' => request('postal_code'),
            'state' => request('state_province')
        ]);

        if(!ShippingInfo::where('user_id', auth()->user()->id)->where('default', true)->exists()) {
            $shipping_info->default = true;
            $shipping_info->save();
        }

        AlertHelper::alertSuccess('Shipping address saved.');
    } catch(\Exception $e) {
        \Log::error($e);
        AlertHelper::alertWarning('Failed to save the shipping address. Try again, or try a new one.');
    }

    return back();
})->name('save-shipping-info');

Route::post('/print-shipping-label', function() {
    $order_id = request('order');
    $order = \App\Models\Order::where('id', $order_id)->first();
    
    if($order->refunded) {
        AlertHelper::alertWarning('Order has been refunded.');
        return redirect('/seller-tools?tab=0');
    }

    if($order->label_url == null) {
        $label_data = $order->purchaseLabel();
        if($label_data !== null) {
            if(array_key_exists('tracking_number', $label_data) && array_key_exists('label_url', $label_data)) {
                $order->tracking_number = $label_data['tracking_number'];
                $order->label_url = $label_data['label_url'];
                $order->save();

                $shipped_notification = \App\Models\Notification::create([
                    'user_id' => $order->user_id,
                    'handler' => '\App\NotificationHandlers\ShippedNotification',
                    'metadata' => [
                        'order_id' => $order->id
                    ]
                ]);

                event(new \App\Events\NewNotification($shipped_notification));
            }
        }
    }

    if($order->label_url !== null) {
        return redirect($order->label_url);
    } else {
        AlertHelper::alertWarning('Failed to purchase shipping label. Try again later.');
        return redirect('/seller-tools?tab=0');
    }
})->name('print-shipping-label');

Route::post('/clear-notifications', function(Request $request) {
    \App\Models\Notification::where('user_id', auth()->user()->id)->update(['read' => true]);
})->name('clear-notifications');

function curl_post($url, array $post = NULL)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS =>"[\n  " . json_encode($post) . "\n]",
      CURLOPT_HTTPHEADER => array(
        "Host: api.shipengine.com",
        "API-Key: " . env('SHIP_ENGINE_API_KEY'),
        "Content-Type: application/json"
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);

    return $response;
}
