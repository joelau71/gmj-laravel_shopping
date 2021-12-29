<?php

namespace GMJ\LaravelShopping\View\Components;

use GMJ\LaravelShopping\Models\Block;
use GMJ\LaravelShopping\Models\Config;
use Illuminate\View\Component;

class Frontend extends Component
{
    public $element_id;
    public $page_element_id;
    public $collections;

    public function __construct($pageElementId, $elementId)
    {
        $this->page_element_id = $pageElementId;
        $this->element_id = $elementId;
        $this->collections = Block::where("element_id", $elementId)->orderBy("display_order")->get();
    }

    public function render()
    {
        $config = Config::where("element_id", $this->element_id)->with("media")->first();
        $layout = $config->layout;
        return view("LaravelShopping::components.{$layout}.frontend");
    }
}
