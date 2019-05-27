<?php
namespace weshop\modules\landing\views\widgets\Product;
use weshop\views\weshop\widgets\BaseWidget;

class ProductWidget extends BaseWidget{
    public $images = [];

    public function run(){
        return $this->render("ProductView", [
            'images'=>$this->images
        ]);
    }
}
?>