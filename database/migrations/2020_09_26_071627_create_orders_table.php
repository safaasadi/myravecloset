<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('seller_id');
            $table->bigInteger('product_id');
            $table->bigInteger('shipping_id');
            $table->string('paypal_payment_id');
            $table->string('paypal_payer_id');
            $table->string('paypal_capture_id')->nullable();
            $table->string('rate_id')->nullable();
            $table->string('invoice_id');
            $table->bigInteger('cost_shipping');
            $table->bigInteger('cost_item');
            $table->boolean('rental')->default(false);
            $table->string('return_date')->nullable();
            $table->boolean('delivered')->default(false);
            $table->boolean('returned')->default(false);
            $table->string('tracking_number')->nullable();
            $table->string('label_url')->nullable();
            $table->string('return_tracking_number')->nullable();
            $table->string('return_label_url')->nullable();
            $table->boolean('refunded')->default(false);
            $table->boolean('paid_out')->default(false);
            $table->boolean('captured')->default(false);
            $table->string('refund_cutoff')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
