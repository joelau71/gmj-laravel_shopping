<?php

namespace GMJ\LaravelShopping\Services;

use Gloudemans\Shoppingcart\Facades\Cart;
use GMJ\LaravelShopping\Models\Product;

class CheckoutService
{
    public $errors;

    public function validateCartProductIsset()
    {
        foreach (Cart::content() as $item) {
            $rowId = $item->rowId;
            $cart = Cart::get($rowId);
            $product = Product::findOrFail($cart->id);
            if (!$product) {
                $this->errors .= "{$cart->name} is not found.\r\n";
            }
        }

        return $this;
    }

    public function validateProductStoring()
    {
        foreach (Cart::content() as $item) {
            $rowId = $item->rowId;
            $cart = Cart::get($rowId);
            $product = Product::findOrFail($cart->id);

            if ($product->quantity < $cart->qty) {
                $this->errors .= "{$cart->name} not enough storing.\r\n";
            }
        }
        return $this;
    }
}
