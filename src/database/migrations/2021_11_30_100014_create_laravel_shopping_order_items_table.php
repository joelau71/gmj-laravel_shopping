<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelShoppingOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_shopping_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id")->constrained("laravel_shopping_orders")->onDelete("cascade");
            $table->string("name");
            $table->text("excerpt");
            $table->integer("quantity");
            $table->integer("price");
            $table->integer("ref_product_id");
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
        Schema::dropIfExists('laravel_shopping_order_items');
    }
}
