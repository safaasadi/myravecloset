<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ReverseRegex\Lexer;
use ReverseRegex\Random\SimpleRandom;
use ReverseRegex\Parser;
use ReverseRegex\Generator\Scope;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    use HasFactory;

    protected $casts = [
        'images' => 'array',
        'tags' => 'array'
    ];

    public function getRating() {
        $count = \App\Models\ProductReview::where('product_id', $this->id)->count();
        $total = \App\Models\ProductReview::where('product_id', $this->id)->sum('rating');
        return $total / ($count == 0 ? 1 : $count);
    }

    public function getLoves() {
        return \App\Models\Love::where('product_id', $this->id)->count();
    }

    public function estimateShipping() {
        if(! ShippingInfo::where('user_id', $this->user_id)->where('default', true)->exists()) return null;
        if(! ShippingInfo::where('user_id', auth()->user()->id)->where('default', true)->exists()) return null;

        $from = ShippingInfo::where('user_id', $this->user_id)->where('default', true)->first();
        $to = ShippingInfo::where('user_id', auth()->user()->id)->where('default', true)->first();

        $cache_key = 'shipping_estimate_' . auth()->user()->id . '_' . $from->postal_code . '_' . $this->weight;

        if(Cache::has($cache_key)) {
            return Cache::get($cache_key);
        }

        $now = date("Y-m-d\TH:i:s\Z");
        $now_two_days = date('Y-m-d\TH:i:s\Z', strtotime($now . ' + 2 days')); 

        $ch = $this->curl_post("https://api.shipengine.com/v1/rates/estimate", [
            'carrier_ids' => [env('USPS_CARRIER_ID')],
            'from_country_code' => 'US',
            'from_postal_code' => $from->postal_code,
            'from_city_locality' => $from->city,
            'from_state_province' => $from->state,
            'to_country_code' => 'US',
            'to_postal_code' => $to->postal_code,
            'to_city_locality' => $to->city,
            'to_state_province' => $to->state,
            'weight' => [
                'value' => $this->weight,
                'unit' => 'pound'
            ],
            'ship_date' => $now_two_days
        ]);

        foreach(json_decode($ch) as $shipping_option) {
            if($shipping_option->service_code == 'usps_parcel_select') {
                Cache::put($cache_key, $shipping_option->shipping_amount->amount, 5 * 60);
                return $shipping_option->shipping_amount->amount;
            }
        }
        
        return null;
    }

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
        CURLOPT_POSTFIELDS => json_encode($post),
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

    public static function getCollection($category = null, $criteria, $hide_sold = false) {
        if($category != null) {
            if(!\App\Models\Category::where('id', $category)->exists()) abort(404);
            $category_obj = \App\Models\Category::where('id', $category)->first();

            if($category_obj->parent_category == null) {
                $products = \App\Models\Product::where('category', $category_obj->id)->get();
            } else {
                $products = \App\Models\Product::where('subcategory', $category_obj->id)->get();
            }
        } else {
            $products = \App\Models\Product::get();
        }

        // <option value="0">Featured</option>
        // <option value="1">Most Popular</option>
        // <option value="2">Most Recent</option>
        // <option value="3">Price (Low To High)</option>
        // <option value="4">Price (High To Low)</option>

        switch($criteria) {
            case 0:
                $products = $products->sortByDesc(function ($product, $key) {
                    $days_passed = time() - strtotime($product->created_at);
                    $ratio = $product->getLoves() / $days_passed;
                    return $ratio;
                });
            break;
            case 1:
                $products = $products->sortByDesc(function ($product, $key) {
                    return $product->getLoves();
                });
            break;
            case 2:
                $products = $products->sortByDesc('created_at');
            break;
            case 3:
                $products = $products->sortBy('purchase_price');
            break;
            case 4:
                $products = $products->sortByDesc('purchase_price');
            break;
        }
    
        if($hide_sold) {
            $products = $products->reject(function($product) {
                return ! $product->active;
            });
        }


        return $products;
    }

    public function isRented() {
        return Order::where('product_id', $this->id)->where('rental', true)->where('returned', false)->exists();
    }

    public function getHTML() {
        if(\Auth::check()) {
            $loved = auth()->user()->lovedProduct($this->id);
        } else {
            $loved = false;
        }

        $html = '
        <div class="col-6 col-md-6 col-lg-4 col-xl-3">
            <div class="hun-element-product--type-1">
                <a href="javascript:love(' . $this->id . ');" class="love-btn-feed ' . ($loved ? 'loved' : 'unloved') . '">
                    <i class="fa fa-2x ' . ($loved ? 'fa-heart' : 'fa-heart-o') . '" id="love-' . $this->id . '"></i>
                </a>

                <a href="/item?id=' . $this->id . '" class="pic-product">
                    <span class="gallery-product">
        ';

                foreach($this->images as $image) {
                    if(\App\Models\File::where('id', $image)->exists()) {
                        $url = \App\Models\File::where('id', $image)->first()->getURL();
                        $html .= '
                            <span class="item-gal">
                                <span class="image-gal" style="background-image: url(' . $url . ');"></span>
                            </span>
                        ';
                    }
                }

                $html .= '
                    </span>
                </a>

                <div class="text-product">
                    <h6 class="name-product set-color">
                        <a href="/item?id=' . $this->id . '">
                            ' . $this->title . '
                        </a>
                    </h6>

                    <div class="price-product set-color" style="margin-left: 1em;">
                        ' . ($this->purchase_price > 0 ? ('<div class="row"><span class="new-price">Buy for $' . (number_format(($this->purchase_price / 100), 2, '.', ',')) . '</span></div>') : '') . 
                         ($this->rental_price > 0 ? ('<div class="row"><span class="new-price">Rent for $' . (number_format(($this->rental_price / 100), 2, '.', ',')) . '</span></div>') : '') . '
                    </div>
                </div>

                <div class="buttons-product">
                    <div class="col-6" style="margin-left: -1em;margin-right: 1em;">
                        <a href="#" class="btn-addcart set-color" style="color:#ff4c62;">
                            <i class="fa fa-heart" style="padding-right: 0.5em;"></i><b>' . $this->getLoves(). '</b>
                        </a>
                    </div>
                    <div class="col-5 text-right" style="margin-top: 0.8em;">
                        <b style="color: #ff4c62;' . ($this->active ? 'visibility: hidden;' : '') . '">' . ($this->isRented() ? "RENTED" : "SOLD") . '</b>
                    </div>
                </div>
            </div>
        </div>
        ';

        return $html;
    }
}
