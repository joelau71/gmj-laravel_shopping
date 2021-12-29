# gmj-laravel_shopping

Laravel Block for backend and frontend - need tailwindcss support

**_composer require bumbummen99/shoppingcart_**<br>
https://github.com/bumbummen99/LaravelShoppingcart<br>
php artisan vendor:publish --provider="Gloudemans\Shoppingcart\ShoppingcartServiceProvider" --tag="config"<br>

**_composer require srmklive/paypal:~3.0_**<br>
https://github.com/srmklive/laravel-paypal<br>

**_composer require stripe/stripe-php_**<br>
https://github.com/stripe/stripe-php<br>
**composer require gmj/laravel_shopping**

in terminal run:<br/>
php artisan vendor:publish --provider="GMJ\LaravelShopping\LaravelShoppingServiceProvider" --force

php artisan migrate

php artisan db:seed --class=LaravelShoppingSeeder

env:<br />
PAYPAL_MODE=sandbox
PAYPAL_SANDBOX_CLIENT_ID={your paypal client id}
PAYPAL_SANDBOX_CLIENT_SECRET={your paypal client secret}
STRIPE_KEY={your stripe key}
STRIPE_SECRET={your stripe secret}

package for test<br>
composer.json#autoload-dev#psr-4: "GMJ\\LaravelShopping\\": "package/laravel_shopping/src/",<br>
config: GMJ\LaravelShopping\LaravelShoppingServiceProvider::class,
