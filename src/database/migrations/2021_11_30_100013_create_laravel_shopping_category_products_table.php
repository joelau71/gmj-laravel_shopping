<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelShoppingCategoryProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_shopping_category_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_id")->constrained("laravel_shopping_products")->onDelete("cascade");
            $table->foreignId("category_id")->constrained("laravel_shopping_categories")->onDelete("cascade");
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
        Schema::dropIfExists('laravel_shopping_category_products');
    }
}
