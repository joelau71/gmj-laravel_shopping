<?php

namespace GMJ\LaravelShopping\Models;

use App\Models\Link;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;

    protected $guarded = [];
    protected $table = "laravel_shopping_products";
    public $translatable = ['title', 'excerpt', 'text'];

    public function registerMediaCollections(Media $media = null): void
    {
        $this->addMediaCollection("laravel_shopping_banner")->singleFile();
        $this->addMediaCollection("laravel_shopping_thumbnail")->singleFile();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, "laravel_shopping_category_products", "product_id", "category_id")->orderBy("display_order")->withTimestamps();
    }
}
