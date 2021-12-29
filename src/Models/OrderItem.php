<?php

namespace GMJ\LaravelShopping\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded = [];
    protected $table = "laravel_shopping_order_items";

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
