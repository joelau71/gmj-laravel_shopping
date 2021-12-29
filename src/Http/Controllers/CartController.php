<?php

namespace GMJ\LaravelShopping\Http\Controllers;

use App\Http\Controllers\Controller;
use Alert;
use App\Models\Page;
use App\Models\Setting;
use App\Services\LanguagesMatchService;
use GMJ\LaravelShopping\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use GMJ\LaravelShopping\Services\CheckoutService;

class CartController extends Controller
{
    public function index()
    {
        $collections = Cart::content();
        $locale = app()->getLocale();
        $locale = LanguagesMatchService::execute($locale);

        $collections->map(function ($item) use ($locale) {
            $product = Product::findOrFail($item->id);
            $item->title = $product->getTranslation("title", $locale);
        });
        //dd($collections);

        $setting = Setting::first();
        $copyright = Page::where("slug", "copyright")->first();
        $disclaimer = Page::where("slug", "disclaimer")->first();
        $terms = Page::where("slug", "terms")->first();

        return view("LaravelShoppingCart::index", compact("collections", "setting", "copyright", "disclaimer", "terms"));
    }

    public function add_to_cart($id)
    {
        $product = Product::findOrFail($id);
        Cart::add(
            $product->id,
            $product->title,
            1,
            $product->price,
            0,
            [
                "storage" => $product->quantity,
                "thumbnail" => $product->getFirstMedia("laravel_shopping_thumbnail")->getUrl()
            ]
        );
        Alert::success(trans("LaravelShopping::web.add new product to cart success"));
        return redirect()->back();
    }

    public function store()
    {
        request()->validate([
            "qty.*" => ["required", "numeric", "min:0", "not_in:0"],
        ], [
            "qty.*.required" => trans("LaravelShopping::web.please enter quantity"),
            "qty.*.numeric" => trans("LaravelShopping::web.quantity must be number"),
            "qty.*.min" => trans("LaravelShopping::web.quantity can't be negative number"),
            "qty.*.not_in" => trans("LaravelShopping::web.quantity can't be zero"),
        ]);
        $i = 0;

        foreach (Cart::content() as $item) {
            $rowId = $item->rowId;
            Cart::update($rowId, request()->qty[$i]);
            $i++;
        }

        $cs = new CheckoutService;
        $result = $cs->validateCartProductIsset()->validateProductStoring();
        if ($result->errors) {
            alert()->error($result->errors);
            return back();
        }

        if (request()->action == "save") {
            Alert::success(trans("LaravelShopping::web.save cart change success"));
            return back();
        } else {
            return redirect()->route("LaravelShopping.order.checkout");
        }
    }

    public function delete($rowId)
    {
        Cart::remove($rowId);
        return back();
    }
}
