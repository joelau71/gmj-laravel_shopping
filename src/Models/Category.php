<?php

namespace GMJ\LaravelShopping\Models;

use App\Models\Element;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $guarded = [];
    protected $table = "laravel_shopping_categories";
    public $translatable = ['title'];

    public function products()
    {
        return $this->belongsToMany(Product::class, "laravel_shopping_category_products", "category_id", "product_id")->where([
            ["quantity", ">", 0],
            ["on_sale", true]
        ])->orderBy("display_order")->withTimestamps();
    }

    public function element()
    {
        return $this->belongsTo(Element::class);
    }
}
