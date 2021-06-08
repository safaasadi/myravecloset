<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('closet_id');
            $table->bigInteger('original_price');
            $table->bigInteger('purchase_price')->nullable();
            $table->bigInteger('rental_price')->nullable();
            $table->boolean('rentable')->default(false);
            $table->string('title');
            $table->text('description');
            $table->integer('category');
            $table->integer('subcategory')->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('color')->nullable();
            $table->integer('size')->nullable();
            $table->boolean('new_with_tags')->default(false);
            $table->string('brand_designer')->nullable();
            $table->json('tags')->nullable();
            $table->json('images');
            $table->boolean('active')->default(true);
            $table->double('weight');
            $table->integer('refund_period')->default(0);
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
        Schema::dropIfExists('products');
    }
}
