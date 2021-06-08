<?php

namespace App\Http\Controllers;

use App\AlertHelper;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class ShopController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $hide_sold = false;
        $criteria = \request('criteria') ? \request('criteria') : 0;
        
        // if(\Auth::check()) {
        //     \Stripe\Stripe::setApiKey(env('STRIPE_CLIENT_SECRET'));

        //     $account = \Stripe\Account::create([
        //         'type' => 'standard',
        //     ]);

        //     \Log::info($account);

        //     $account_links = \Stripe\AccountLink::create([
        //         'account' => $account->id,
        //         'refresh_url' => env('APP_URL') . '/reauth',
        //         'return_url' => env('APP_URL') . '/return',
        //         'type' => 'account_onboarding',
        //     ]);

        //     return redirect($account_links->url);
        // }

        if(\request('hide_sold') == '1') {
            $hide_sold = true;
        }

        if(\request('category')) {
            if(!\App\Models\Category::where('id', \request('category'))->exists()) abort(404);
            $category = \App\Models\Category::where('id', \request('category'))->first();
            $products = Product::getCollection(\request('category'), $criteria, $hide_sold);
            return view('shop')->with('hide_sold', $hide_sold)->with('category', $category)->with('products', $products)->with('criteria', $criteria);
        } else {
            $products = Product::getCollection(null, $criteria, $hide_sold);
            return view('shop')->with('hide_sold', $hide_sold)->with('products', $products)->with('criteria', $criteria);
        }
    }
}
