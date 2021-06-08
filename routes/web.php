<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use \App\Models\PayPalAccount;
use \App\Models\ShippingInfo;
use \App\Models\User;
use \App\Models\Order;
use \App\AlertHelper;
use \App\PayPalIPN;
use \App\PayPalHelper;
use PayPal\Api\Amount;
use PayPal\Api\Authorization;
use PayPal\Api\Capture;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// require_once __DIR__ . "/partials/coming_soon.php";

Route::webhooks('stripe_webhooks');
Auth::routes();

require_once __DIR__ . "/partials/site.php";
require_once __DIR__ . "/partials/shop.php";
require_once __DIR__ . "/partials/auth.php";

Route::get('/terms_of_service', function() {
    return view('terms_of_service');
})->name('terms_of_service');

Route::post('ipn_handler', function() {
    $ipn = new PaypalIPN();
    $apiContext = PayPalHelper::getApiContext();

    // Use the sandbox endpoint during testing.
    $ipn->useSandbox();
    $verified = $ipn->verifyIPN();

    $data = [];

    if ($verified) {
        foreach ($_POST as $key => $value) {
            $data[$key] = $value;
        }

        if($data['payment_status'] == 'Pending') {
            $authorization = Authorization::get($data['txn_id'], $apiContext);
            $amt = new Amount();
            $amt->setCurrency("USD")
                ->setTotal($data['payment_gross']);

            ### Capture
            $capture = new Capture();
            $capture->setAmount($amt);
            $getCapture = $authorization->capture($capture, $apiContext);

            \Log::info($data);
            \Log::info($getCapture);

            if(array_key_exists('invoice', $data)) {
                if(Order::where('invoice_id', $data['invoice'])->exists()) {
                    $order = Order::where('invoice_id', $data['invoice'])->first();
                    $order->captured = true;
                    $order->paypal_capture_id = $getCapture->id;
                    $order->save();
                }
            }
        } else if ($data['payment_status'] == 'Completed') {
           // do nothing
        }
    }

})->name('ipn_handler');

Route::group(['middleware' => ['auth', 'web']], function () {

    require_once __DIR__ . "/partials/account.php";
    require_once __DIR__ . "/partials/messages.php";
    require_once __DIR__ . "/partials/order.php";
    require_once __DIR__ . "/partials/paypal.php";
    
});