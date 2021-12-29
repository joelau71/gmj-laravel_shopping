<?php

namespace GMJ\LaravelShopping\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
    protected $table = "laravel_shopping_orders";

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
