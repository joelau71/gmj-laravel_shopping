<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory;
use GMJ\LaravelShopping\Models\Category;
use GMJ\LaravelShopping\Models\Product;
use Image;
use DB;

class LaravelShoppingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("plugin_menus")->insert([
            "slug" => "laravel_shopping",
            "url" => "LaravelShopping.index",
            "title" => "Shopping"
        ]);

        $faker = Factory::create();
        $display_order = Category::max("display_order");

        $display_order++;
        foreach (config('translatable.locales') as $locale) {
            $title[$locale] = $faker->name;
        }

        Category::create([
            "title" => $title,
            "display_order" => $display_order
        ]);


        $display_order++;
        foreach (config('translatable.locales') as $locale) {
            $title[$locale] = $faker->name;
        }
        Category::create([
            "title" => $title,
            "display_order" => $display_order
        ]);

        $display_order++;
        foreach (config('translatable.locales') as $locale) {
            $title[$locale] = $faker->name;
        }

        Category::create([
            "title" => $title,
            "display_order" => $display_order
        ]);

        for ($i = 1; $i < 4; $i++) {
            $title = [];
            $text = [];

            foreach (config('translatable.locales') as $locale) {
                $title[$locale] = $faker->name;
                $text[$locale] = $faker->text(400);
                $excerpt[$locale] =  $faker->text(100);
            }

            $collection = Product::create([
                "title" => $title,
                "excerpt" => $excerpt,
                "text" => $text,
                "original_price" => $faker->numberBetween(600, 6000),
                "price" => $faker->numberBetween(100, 3000),
                "quantity" => $faker->numberBetween(100, 400),
                "display_order" => $i
            ]);

            $img = Image::make(storage_path("demo/food/{$i}.jpg"))->fit(config("gmj.laravel_shopping_config.banner.image_width"), config("gmj.laravel_shopping_config.banner.image_height"));
            $img->save(storage_path("demo/temp.jpg"));
            $collection->addMedia(storage_path('demo/temp.jpg'))
                ->toMediaCollection("laravel_shopping_banner");

            $img = Image::make(storage_path("demo/food/{$i}.jpg"))->fit(config("gmj.laravel_shopping_config.thumbnail.image_width"), config("gmj.laravel_shopping_config.thumbnail.image_height"));
            $img->save(storage_path("demo/temp.jpg"));
            $collection->addMedia(storage_path('demo/temp.jpg'))
                ->toMediaCollection("laravel_shopping_thumbnail");

            $collection->categories()->attach($i);
        }
    }
}
