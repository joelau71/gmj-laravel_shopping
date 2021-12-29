<?php

namespace GMJ\LaravelShopping\Http\Livewire;

use App\Models\Element;
use GMJ\LaravelShopping\Models\Category;
use Livewire\Component;

class CategoryLivewire extends Component
{
    public $mode = "index";
    public $data;
    public $category_id;
    public $filter_title;
    protected $listeners = ["saveChnageDisplayOrder" => "order2", "delete"];


    public function create()
    {
        $this->data = [];
        foreach (config("translatable.locales") as $locale) {
            $this->data["title_{$locale}"] = null;
        }
        $this->data["layout"] = null;

        $this->mode = "create";
    }

    public function edit(Category $category)
    {
        $this->category_id = $category->id;
        $this->data = $category->toArray();
        foreach (config("translatable.locales") as $locale) {
            $this->data["title_{$locale}"] = $this->data["title"][$locale];
        }
        $this->mode = "edit";
    }

    public function order()
    {
        $this->mode = "order";
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

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }


    public function store()
    {
        $title = [];
        $display_order = Category::max("display_order");
        $display_order++;

        foreach (config("translatable.locales") as $locale) {
            $title[$locale] = $this->data["title_{$locale}"];
        }

        Category::create([
            "title" => $title,
            "display_order" => $display_order,
        ]);

        $this->close();

        $this->dispatchBrowserEvent('swal:modal', [
            'title' => 'Success',
            'text' => 'Create New Product Category Success',
            "type" => "success"
        ]);
    }

    public function rules()
    {
        $array = [];
        foreach (config("translatable.locales") as $locale) {
            $array["data.title_{$locale}"] = "required";
        }

        return $array;
    }

    public function update()
    {
        $title = [];
        foreach (config("translatable.locales") as $locale) {
            $title[$locale] = $this->data["title_{$locale}"];
        }

        $category = Category::findOrFail($this->category_id);

        $category->update([
            "title" => $title,
        ]);

        $this->close();

        $this->dispatchBrowserEvent('swal:modal', [
            'title' => 'Success',
            'text' => 'Edit Product Category Success',
            "type" => "success"
        ]);
    }

    public function order2($data)
    {
        $display_order = 1;
        foreach ($data as $field) {
            $value = $field["value"];
            $item = Category::findOrFail($value);
            $item->display_order = $display_order;
            $item->save();
            $display_order++;
        }

        $this->close();

        $this->dispatchBrowserEvent('swal:modal', [
            'title' => 'Success',
            'text' => 'Change Product Category Success',
            "type" => "success"
        ]);
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();

        $this->dispatchBrowserEvent('swal:modal', [
            'title' => 'Success',
            'text' => 'Delete Product Category Success',
            "type" => "success"
        ]);
    }

    public function close()
    {
        $this->category_id = null;
        $this->resetErrorBag();
        $this->mode = "index";
    }

    public function render()
    {
        $collections = Category::when($this->filter_title, function ($collection) {
            return $collection->where("title", "LIKE", "%{$this->filter_title}%");
        })
            ->orderBy("display_order")
            ->get();
        return view('LaravelShopping::livewire.category', compact("collections"));
    }
}
