<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelShoppingProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_shopping_products', function (Blueprint $table) {
            $table->id();
            $table->longText('title');
            $table->longText("excerpt");
            $table->longText('text');
            $table->integer("original_price")->nullable();
            $table->integer("price");
            $table->integer("quantity");
            $table->boolean("on_sale")->default(true);
            $table->integer("display_order");
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
        Schema::dropIfExists('laravel_shopping_products');
    }
}
