<?php
namespace weshop\modules\landing\views\widgets\LandingBrandSlider;
use weshop\views\weshop\widgets\BaseWidget;

class LandingBrandSliderWidget extends BaseWidget{
    public $block = [];

    public function run(){

        $images = [];
        if(isset($this->block['Brand'])){
            $images = $this->block['Brand'];
        }


        return $this->render("LandingBrandSliderView", [
            'images'=>$images
        ]);
    }
}
?>