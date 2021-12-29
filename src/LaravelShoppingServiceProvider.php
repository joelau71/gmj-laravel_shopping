<?php

namespace GMJ\LaravelShopping;

use GMJ\LaravelShopping\Http\Livewire\CategoryLivewire;
use GMJ\LaravelShopping\Http\Livewire\ProductLivewire;
use Illuminate\Support\ServiceProvider;
use Livewire;


class LaravelShoppingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadRoutesFrom(__DIR__ . "/routes/web.php", 'LaravelShopping');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'LaravelShopping');
        $this->loadViewsFrom(__DIR__ . '/resources/views/cart', 'LaravelShoppingCart');
        $this->loadViewsFrom(__DIR__ . '/resources/views/order', 'LaravelShoppingOrder');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'LaravelShopping');

        //Blade::component("LaravelShopping", Frontend::class);
        Livewire::component("CategoryLivewire", CategoryLivewire::class);
        Livewire::component("ProductLivewire", ProductLivewire::class);

        $this->publishes([
            __DIR__ . "/config/laravel_shopping_config.php" => config_path("gmj/laravel_shopping_config.php"),
            __DIR__ . '/resources/assets' => public_path('gmj'),
            __DIR__ . '/database/seeders' => database_path('seeders'),
        ], 'GMJ\LaravelShopping');
    }


    public function register()
    {
    }
}
