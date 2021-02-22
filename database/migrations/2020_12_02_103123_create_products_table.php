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
            $table->bigIncrements('id');
            $table->integer('category_id')->nullable();
            $table->integer('brand_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->string('article')->nullable();
            $table->string('gender')->nullable();
            $table->bigInteger('purchase_price')->nullable();
            $table->bigInteger('consumer_selling_price')->nullable();
            $table->bigInteger('retailer_selling_price')->nullable();
            $table->bigInteger('opening_quantity')->nullable();
            $table->bigInteger('moq')->nullable();
            $table->bigInteger('quantity_in_hand')->nullable();
            $table->string('product_picture')->nullable();
            $table->bigInteger('cost_value')->nullable();
            $table->bigInteger('sales_value')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('modified_by')->nullable();
            $table->timestamps();
            $table->softDeletes()->nullable();
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
