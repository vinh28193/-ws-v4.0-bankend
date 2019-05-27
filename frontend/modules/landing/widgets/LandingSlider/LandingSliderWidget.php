<?php
namespace weshop\modules\landing\views\widgets\LandingSlider;
use weshop\views\weshop\widgets\BaseWidget;
use common\models\cms\WsImageGroup;

class LandingSliderWidget extends BaseWidget{
    public $block = [];

    public function run(){
        $categories = [];
        if(!empty($this->block['categories'])){
            $categories = $this->block['categories'];
        }

        $images = $this->block['Brand'];

        return $this->render("LandingSliderView", [
            'block'=>$this->block,
            'categories'=>$categories,
            'images'=>$images,
        ]);

    }
}
?>