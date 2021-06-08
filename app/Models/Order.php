<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
      'product_id',
      'user_id',
      'seller_id',
      'shipping_id',
      'rental',
      'return_date',
      'cost_shipping',
      'cost_item',
      'receipt_url',
      'invoice_id',
      'paid_out',
      'paypal_payment_id',
      'paypal_payer_id'
    ];

    public function isOverDue() {
        return $this->rental && $this->return_tracking_number == null && $this->created_at < \Carbon\Carbon::now()->subDays(env('RENTAL_PERIOD', 7));
    }

    public function isEnding() {
        return $this->rental && $this->return_tracking_number == null && $this->created_at <= \Carbon\Carbon::now()->subDays(env('RENTAL_PERIOD', 7) - 3);
    }

    public function isShipped() {
        return $this->tracking_number !== null;
    }

    public function isReturnShipped() {
        return $this->return_tracking_number !== null;
    }

    public function purchaseLabel($return = false) {
      if(! ShippingInfo::where('user_id', $this->seller_id)->where('default', true)->exists()) return null;
      if(! ShippingInfo::where('user_id', $this->user_id)->where('default', true)->exists()) return null;

      $from = ShippingInfo::where('user_id', $this->seller_id)->where('default', true)->first();
      $to = ShippingInfo::where('user_id', $this->user_id)->where('default', true)->first();
      $now = date("Y-m-d\TH:i:s\Z");
      $now_two_days = date('Y-m-d\TH:i:s\Z', strtotime($now . ' + 2 days')); 

      $product = \App\Models\Product::where('id', $this->product_id)->first();

      $ch = $this->curl_post("https://api.shipengine.com/v1/labels", [
          'shipment' => [
              'carrier_id' => env('USPS_CARRIER_ID'),
              'service_code' => 'usps_parcel_select',
              'ship_date' => $now_two_days,
              'ship_to' => [
                  'name' => $return ? $from->name : $to->name,
                  'phone' => $return ? $from->phone_number : $to->phone_number,
                  'address_line1' => $return ? $from->line1 : $to->line1,
                  'address_line2' => $return ? $from->line2 : $to->line2,
                  'city_locality' => $return ? $from->city : $to->city,
                  'state_province' => $return ? $from->state : $to->state,
                  'postal_code' => $return ? $from->postal_code : $to->postal_code,
                  'country_code' => 'US'
              ],
              'ship_from' => [
                  'name' => $return ? $to->name : $from->name,
                  'phone' => $return ? $to->phone_number : $from->phone_number,
                  'address_line1' => $return ? $to->line1 : $from->line1,
                  'address_line2' => $return ? $to->line2 : $from->line2,
                  'city_locality' => $return ? $to->city : $from->city,
                  'state_province' => $return ? $to->state : $from->state,
                  'postal_code' => $return ? $to->postal_code : $from->postal_code,
                  'country_code' => 'US'
              ],
              'packages' => [
                  [
                      // 'package_code'
                      'weight' => [
                        "value" => $product->weight,
                        "unit" => "pound"
                      ]
                  ]
              ]
          ]
      ]);

      $json = json_decode($ch);

                        \Log::info($json);

      if(isset($json->errors)) {
        \Log::info("==== FAILED TO PRINT LABEL FOR ORDER #: " . $this->id);
        \Log::info($json->errors);
        return [];
      } else {
        return ['tracking_number' => $json->tracking_number, 'label_url' => $json->label_download->pdf];
      }
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
}
