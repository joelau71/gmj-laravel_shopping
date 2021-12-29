<?php

namespace GMJ\LaravelShopping\Http\Livewire;

use App\Models\Element;
use GMJ\LaravelShopping\Models\Category;
use GMJ\LaravelShopping\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
//use Livewire\WithFileUploads;

class ProductLivewire extends Component
{
    public $categories;
    public $data;
    public $filter_title;
    public $filter_category;
    public $mode = "index";
    protected $listeners = ["saveChnageDisplayOrder", "delete"];

    //protected $listeners = ["updateField"];

    // use WithFileUploads;


    public function mount()
    {
        $this->categories = Category::orderBy("display_order")->get();
    }

    /* public function rules()
    {
        $array = [];
        foreach (config("translatable.locales") as $locale) {
            $array["data.title_{$locale}"] = "required";
            $array["data.excerpt_{$locale}"] = "required";
            $array["data.text_{$locale}"] = "required";
        }
        $array["data.price"] = ["required", "numeric"];
        $array["data.category"] = "required";
        $array["data.banner"] = ["required", "image"];
        $array["data.quantity"] = ["required", "numeric"];
        return $array;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updateField($propertyName, $value)
    {
        $this->data[$propertyName] = $value;

        info($this->data);
        $this->validateOnly($propertyName);
    }

    public function create()
    {
        $this->data = [];
        foreach (config("translatable.locales") as $locale) {
            $this->data["title_{$locale}"] = null;
            $this->data["excerpt_{$locale}"] = null;
            $this->data["text_{$locale}"] = null;
        }
        $this->data["banner"] = null;
        $this->data["categories"] = [];
        $this->data["price"] = null;
        $this->data["quantity"] = null;

        $this->mode = "create";
    }


    public function save()
    {
        $this->validate();

        if ($this->mode == "create") {
            $this->store();
            return false;
        }

        if ($this->mode == "edit") {
            $this->update();
            return false;
        }
    }

    public function store()
    {
        $this->validate();

        $display_order = Category::max("display_order");
        $display_order++;

        foreach (config("translatable.locales") as $locale) {
            $title[$locale] = $this->data["title_{$locale}"];
            $excerpt[$locale] = $this->data["excerpt_{$locale}"];
            $text[$locale] = $this->data["text_{$locale}"];
        }

        $product = Product::create([
            "element_id" => $this->element->id,
            "title" => $title,
            "excerpt" => $excerpt,
            "text" => $text,
            "price" => $this->data["price"],
            "quantity" => $this->data["quantity"],
            "display_order" => $display_order,
        ]);

        $product->category()->attach($this->data["categories"]);

        $this->close();

        $this->dispatchBrowserEvent('swal:modal', [
            'title' => 'Create',
            'text' => 'New Product Success',
            "type" => "success"
        ]);
    } */

    public function order()
    {
        $this->mode = "order";
    }

    public function close()
    {
        $this->mode = "index";
    }

    public function saveChnageDisplayOrder($data)
    {
        $display_order = 1;
        foreach ($data as $field) {
            $value = $field["value"];
            $item = Product::findOrFail($value);
            $item->display_order = $display_order;
            $item->save();
            $display_order++;
        }

        $this->close();

        $this->dispatchBrowserEvent('swal:modal', [
            'title' => 'Success',
            'text' => 'Change Product Success',
            "type" => "success"
        ]);
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();

        $this->dispatchBrowserEvent('swal:modal', [
            'title' => 'Success',
            'text' => 'Delete Product Success',
            "type" => "success"
        ]);
    }

    public function render()
    {
        $collections = Product::when($this->filter_title, function ($query) {
            return $query->where("title", "LIKE", "%{$this->filter_title}%");
        })
            ->when($this->filter_category, function ($query) {
                $products = DB::table("laravel_shopping_category_products")
                    ->where("category_id", $this->filter_category)
                    ->pluck("product_id")
                    ->toArray();
                return $query->whereIn("id", $products);
            })
            ->orderBy("display_order")
            ->get();
        return view('LaravelShopping::livewire.product', compact("collections"));
    }
}
