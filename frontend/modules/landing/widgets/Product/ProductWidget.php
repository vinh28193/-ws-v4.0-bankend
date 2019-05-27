<?php
namespace landing\widgets\Product;
use landing\LandingWidget;

class ProductWidget extends LandingWidget{
    public $images = [];

    public function run(){
        return $this->render("ProductView", [
            'images'=>$this->images
        ]);
    }
}
?>