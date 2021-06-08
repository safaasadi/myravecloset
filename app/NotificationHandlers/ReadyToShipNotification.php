<?php

namespace App\NotificationHandlers;

use Illuminate\Support\Facades\Session;

class ReadyToShipNotification extends Notification
{

    public $order;
    public $product;

    public function __construct($notification) {
        parent::__construct($notification);

        $this->order = \App\Models\Order::where('id', $notification->metadata['order_id'])->first();
        $this->product = \App\Models\Product::where('id', $this->order->product_id)->first();
    }

    public function getLink() {
        return "/seller-tools?tab=0";
    }

    public function getHTML() {
        $thumbnail = "";
        
        if(! empty($this->product->images)) {
            if(\App\Models\File::where('id', $this->product->images[0])->exists()) {
                $thumbnail_url = \App\Models\File::where('id', $this->product->images[0])->first()->getURL();
                $thumbnail = '<img style="max-width: 5em;height: 3em;" src="' . $thumbnail_url . '" />';
            }
        }

        $html = '
            <div class="row">
                <div class="col-3">
                    (thumbnail)
                </div>
                <div class="col-9">
                    <div class="row">
                        <h6 style="color: #6534ff">Ready to Ship!</h6>
                    </div>
                    <div class="row">
                        <small>(product_title)</small>
                    </div>
                </div>
            </div>
        ';

        $html = str_replace("(thumbnail)", $thumbnail, $html);
        $html = str_replace("(product_title)", $this->product->title, $html);

        return $html;
    }

}