<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTextSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('text_settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unique();
            $table->boolean('order_new')->default(true);
            $table->boolean('order_shipped')->default(true);
            $table->boolean('order_dispute_new')->default(false);
            $table->boolean('order_dispute_update')->default(false);
            $table->boolean('order_refunded')->default(false);
            $table->boolean('order_receipt')->default(false);
            $table->boolean('rental_overdue')->default(true);
            $table->boolean('rental_shipped')->default(true);
            $table->boolean('rental_returned')->default(false);
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
        Schema::dropIfExists('text_settings');
    }
}
