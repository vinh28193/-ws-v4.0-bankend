<?php
namespace landing\widgets\LandingSlider;
use landing\LandingWidget;
use common\models\cms\WsImageGroup;

class LandingSliderWidget extends LandingWidget{
    public $block = [];

    public function run(){
        $categories = [];
        if(!empty($this->block['categories'])){
            $categories = $this->block['categories'];
        }

        $images = $this->block['images'];

        return $this->render("LandingSliderView", [
            'block'=>$this->block,
            'categories'=>$categories,
            'images'=>$images,
        ]);

    }
}
?>