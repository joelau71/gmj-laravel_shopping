<?php

namespace GMJ\LaravelShopping\Http\Controllers;

use App\Http\Controllers\Controller;
use Alert;
use App\Models\Page;
use App\Models\Setting;
use GMJ\LaravelShopping\Models\Category;
use GMJ\LaravelShopping\Models\Product;

class ProductController extends Controller
{
    public function create()
    {
        $categories = Category::orderBy("display_order")->get();
        return view('LaravelShopping::product.create', compact("categories"));
    }

    public function store()
    {

        foreach (config("translatable.locales") as $locale) {
            $title[$locale] = request()["title_{$locale}"];
            $excerpt[$locale] = request()["excerpt_{$locale}"];
            $text[$locale] = request()["text_{$locale}"];

            $rules["title_{$locale}"] = "required";
            $rules["excerpt_{$locale}"] = "required";
            $rules["text_{$locale}"] = "required";
        }

        $rules["category"] = ["required"];
        $rules["price"] = ["required", "numeric"];
        $rules["quantity"] = ["required", "numeric"];
        $rules["uic_base64_banner"] = "required";
        $rules["uic_base64_thumbnail"] = "required";

        request()->validate($rules);

        $display_order = Product::max("display_order");
        $display_order++;

        $collection = Product::create([
            "title" => $title,
            "excerpt" => $excerpt,
            "text" => $text,
            "price" => request()->price,
            "quantity" => request()->quantity,
            "on_sale" => request()->on_sale ? true : false,
            "display_order" => $display_order
        ]);

        $collection->addMediaFromBase64(request()->uic_base64_banner, ["image/jpeg", "image/png"])->toMediaCollection('laravel_shopping_banner');

        $collection->addMediaFromBase64(request()->uic_base64_thumbnail, ["image/jpeg", "image/png"])->toMediaCollection('laravel_shopping_thumbnail');

        $collection->categories()->attach(request()->category);

        Alert::success("Create New Product Success");
        return redirect()->back();
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy("display_order")->get();
        $collection = $product;
        return view('LaravelShopping::product.edit', compact("categories", "collection"));
    }

    public function update(Product $product)
    {

        foreach (config("translatable.locales") as $locale) {
            $title[$locale] = request()["title_{$locale}"];
            $excerpt[$locale] = request()["excerpt_{$locale}"];
            $text[$locale] = request()["text_{$locale}"];

            $rules["title_{$locale}"] = "required";
            $rules["excerpt_{$locale}"] = "required";
            $rules["text_{$locale}"] = "required";
        }

        $rules["category"] = ["required"];
        $rules["price"] = ["required", "numeric"];
        $rules["quantity"] = ["required", "numeric"];


        request()->validate($rules);

        $product->update([
            "title" => $title,
            "excerpt" => $excerpt,
            "text" => $text,
            "price" => request()->price,
            "quantity" => request()->quantity,
            "on_sale" => request()->on_sale ? true : false,
        ]);

        if (request()->uic_base64_banner) {
            $product->addMediaFromBase64(request()->uic_base64_banner, ["image/jpeg", "image/png"])->toMediaCollection('laravel_shopping_banner');
        }

        if (request()->uic_base64_thumbnail) {
            $product->addMediaFromBase64(request()->uic_base64_thumbnail, ["image/jpeg", "image/png"])->toMediaCollection('laravel_shopping_thumbnail');
        }

        $product->categories()->sync(request()->category);

        Alert::success("Edit Product Success");
        return redirect()->route('LaravelShopping.index');
    }

    public function order()
    {
        $collections =  Product::orderBy("display_order")->get();
        return view("LaravelShopping::order", compact("collections"));
    }

    public function order2()
    {
        foreach (request()->id as $key => $item) {
            $collection = Product::find($item);
            $num = $key + 1;
            $collection->display_order = $num;
            $collection->save();
        }
        Alert::success("Edit Product Display Order Success");
        return redirect()->route('LaravelShopping.index');
    }

    public function destroy($id)
    {

        $collection = Product::findOrFail($id);
        $collection->link()->delete();
        $collection->delete();

        Alert::success("Delete Product Success");
        return redirect()->route('LaravelShopping.index');
    }

    public function show($id)
    {
        $setting = Setting::first();
        $copyright = Page::where("slug", "copyright")->first();
        $disclaimer = Page::where("slug", "disclaimer")->first();
        $terms = Page::where("slug", "terms")->first();
        $product = Product::findOrFail($id);

        if ($product->quantity <= 0) {
            return back();
        }

        return view("LaravelShopping::product.show", compact("setting", "copyright", "disclaimer", "terms", "product"));
    }
}
