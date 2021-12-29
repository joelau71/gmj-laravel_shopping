<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelShoppingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_shopping_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");

            $table->enum("status", ['pending', 'processing', 'completed', 'decline'])->default("pending");
            $table->float('total');
            $table->boolean("is_paid")->default(false);
            $table->enum('payment_method', ['cash_on_delivery', 'paypal', 'stripe', 'card'])->default('cash_on_delivery');

            $table->string("shipping_fullname");
            $table->text("shipping_address");
            // $table->text("shipping_city");
            // $table->string("shipping_state");
            // $table->string("shipping_zipcode");
            $table->string("shipping_phone");
            $table->text("notes")->nullable();

            $table->string("billing_fullname");
            $table->text("billing_address");
            // $table->string("billing_city");
            // $table->string("billing_state");
            // $table->string("billing_zipcode");
            $table->string("billing_phone");
            $table->string("payment_ref_id")->nullable();

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
        Schema::dropIfExists('laravel_shopping_orders');
    }
}
