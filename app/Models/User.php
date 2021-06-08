<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use \App\Models\StripeAccount;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function TextSettings() {
        return TextSetting::where('user_id', $this->id)->first();
    }

    public function StripeAccount() {
        return StripeAccount::where('user_id', $this->id)->first();
    }

    public function PayPalAccount() {
        return PayPalAccount::where('user_id', $this->id)->first();
    }

    public function getAvatar() {
        if($this->avatar != null && \App\Models\File::where('id', $this->avatar)->exists()) {
            return \App\Models\File::where('id', $this->avatar)->first()->getURL();
        }
        return asset('images/default-avatar.png');
    }

    public function getRating() {
        $count = \App\Models\UserReview::where('user_id', $this->id)->count();
        $total = \App\Models\UserReview::where('user_id', $this->id)->sum('rating');
        return $total / ($count == 0 ? 1 : $count);
    }

    public function lovedProduct($product_id) {
        return \App\Models\Love::where('product_id', $product_id)->where('user_id', $this->id)->exists();
    }

    public function getLovedProducts() {
        $products = [];
        foreach(\App\Models\Love::where('user_id', $this->id)->get() as $love) {
            if(\App\Models\Product::where('id', $love->product_id)->exists()) {
                array_push($products, \App\Models\Product::where('id', $love->product_id)->first());
            }
        }
        return $products;
    }

    public function isSeller() {
        return Product::where('user_id', $this->id)->exists() || Order::where('seller_id', $this->id)->exists();
    }


    public function getReturnNotifications() {
        $notifications = [];
        $processed = [];

        foreach($this->getOverdueRentals() as $order) {
            $notification = new \App\Models\Notification();
            $notification->metadata = ['order_id' => $order->id];
            array_push($notifications, new \App\NotificationHandlers\ReturnItemNotification($notification, true));
            array_push($processed, $order->id);
        }

        foreach($this->getEndingRentals() as $order) {
            if(! in_array($order->id, $processed)) {
                $notification = new \App\Models\Notification();
                $notification->metadata = ['order_id' => $order->id];
                array_push($notifications, new \App\NotificationHandlers\ReturnItemNotification($notification));
            }
        }

        return $notifications;
    }

    public function getOrdersToShip() {
        return Order::where('seller_id', auth()->user()->id)->where('refunded', false)->where('tracking_number', null)->get();
    }

    public function getOrdersToShipNotifications() {
        $notifications = [];

        foreach($this->getOrdersToShip() as $order) {
            $notification = new \App\Models\Notification();
            $notification->metadata = ['order_id' => $order->id];
            array_push($notifications, new \App\NotificationHandlers\ReadyToShipNotification($notification));
        }

        return $notifications;
    }

    public function getRentals() {
        $processed = [];
        $orders = [];

        foreach($this->getOverdueRentals() as $order) {
            array_push($orders, $order);
            array_push($processed, $order->id);
        }

        foreach($this->getEndingRentals() as $order) {
            if(! in_array($order->id, $processed)) {
                array_push($orders, $order);
            }
        }

        foreach($this->getActiveRentals() as $order) {
            if(! in_array($order->id, $processed)) {
                array_push($orders, $order);
            }
        }

        return $orders;
    }

    public function getOverdueRentals() {
        return Order::where('user_id', $this->id)->where('refunded', false)->where('rental', true)->where('returned', false)->where('created_at', '<', \Carbon\Carbon::now()->subDays(env('RENTAL_PERIOD', 7)))->get();
    }

    public function getEndingRentals() {
        return Order::where('user_id', $this->id)->where('refunded', false)->where('rental', true)->where('returned', false)->where('created_at', '<=', \Carbon\Carbon::now()->subDays(env('RENTAL_PERIOD', 7) - 3))->get();
    }

    public function getActiveRentals() {
        return Order::where('user_id', $this->id)->where('refunded', false)->where('rental', true)->where('returned', false)->get();
    }

    public function getRentalsSeller() {
        $processed = [];
        $orders = [];

        foreach($this->getOverdueRentalsSeller() as $order) {
            array_push($orders, $order);
            array_push($processed, $order->id);
        }

        foreach($this->getEndingRentalsSeller() as $order) {
            if(! in_array($order->id, $processed)) {
                array_push($orders, $order);
            }
        }

        foreach($this->getActiveRentalsSeller() as $order) {
            if(! in_array($order->id, $processed)) {
                array_push($orders, $order);
            }
        }

        return $orders;
    }

    public function getOverdueRentalsSeller() {
        return Order::where('seller_id', $this->id)->where('refunded', false)->where('rental', true)->where('returned', false)->where('created_at', '<', \Carbon\Carbon::now()->subDays(env('RENTAL_PERIOD', 7)))->get();
    }

    public function getEndingRentalsSeller() {
        return Order::where('seller_id', $this->id)->where('refunded', false)->where('rental', true)->where('returned', false)->where('created_at', '<=', \Carbon\Carbon::now()->subDays(env('RENTAL_PERIOD', 7) - 3))->get();
    }

    public function getActiveRentalsSeller() {
        return Order::where('seller_id', $this->id)->where('refunded', false)->where('rental', true)->where('returned', false)->get();
    }

    public function getCommission() {
        $paypal_acc = PayPalAccount::where('user_id', $this->id)->first();
        if($paypal_acc !== null) {
            $app_fee = $paypal_acc->application_fee_percent;
            if($app_fee == null) $app_fee = env('DEFAULT_COMMISSION');
        }
        return env('DEFAULT_COMMISSION');
    }

}
