<?php
namespace weshop\modules\landing\views\widgets\BrandSlider;
use weshop\views\weshop\widgets\BaseWidget;

class BrandSliderWidget extends BaseWidget{
    public $block = [];

    public function run(){

        $images = [];
        if(isset($this->block['Brand'])){
            $images = $this->block['Brand'];
        }


        return $this->render("BrandSliderView", [
            'images'=>$images
        ]);
    }
}
?>