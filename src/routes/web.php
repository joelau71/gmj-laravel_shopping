<?php

use GMJ\LaravelShopping\Http\Controllers\CartController;
use GMJ\LaravelShopping\Http\Controllers\OrderController;
use GMJ\LaravelShopping\Http\Controllers\ProductController;
use GMJ\LaravelShopping\Http\Livewire\CategoryLivewire;
use GMJ\LaravelShopping\Http\Livewire\ProductLivewire;
use Illuminate\Support\Facades\Route;


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
    ],
    function () {
        Route::get("/product/{id}", [ProductController::class, "show"])->name("LaravelShopping.show");
        Route::group([
            "middleware" => ["web"],
            "prefix" => "shopping-cart",
            "as" => "LaravelShopping."
        ], function () {
            Route::get("index", [CartController::class, "index"])->name("cart.index");
            Route::post("store", [CartController::class, "store"])->name("cart.store");
            Route::get("delete/{rowId}", [CartController::class, "delete"])->name("cart.delete");
            Route::get("add_to_cart/{id}", [CartController::class, "add_to_cart"])->name("cart.add_to_cart");
        });

        Route::group([
            "middleware" => ["web", "auth"],
            "prefix" => "order",
            "as" => "LaravelShopping.order."
        ], function () {
            Route::get("checkout", [OrderController::class, "checkout"])->name("checkout");
            Route::post("checkout2", [OrderController::class, "checkout2"])->name("checkout2");
            Route::get("success", [OrderController::class, "success"])->name("success");
            Route::get("cancel", [OrderController::class, "cancel"])->name("cancel");
        });
    }
);


Route::group([
    "middleware" => ["web", "auth"],
    "prefix" => "admin/gmj/laravel_shopping",
    "as" => "LaravelShopping."
], function () {
    Route::get("index", ProductLivewire::class)->name("index");
    Route::get("create", [ProductController::class, "create"])->name("create");
    Route::post("store", [ProductController::class, "store"])->name("store");
    Route::get("edit/{product:id}", [ProductController::class, "edit"])->name("edit");
    Route::patch("update/{product:id}", [ProductController::class, "update"])->name("update");
});


Route::group([
    "middleware" => ["web", "auth"],
    "prefix" => "admin/gmj/laravel_shopping/category",
    "as" => "LaravelShoppingCategory"
], function () {
    Route::get("", CategoryLivewire::class);
});
