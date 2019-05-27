<?php
namespace landing\widgets\LandingSliderFluid;

use landing\LandingWidget;
use common\models\cms\WsImageGroup;

class LandingSliderFluidWidget extends LandingWidget{
    public $block = [];

    public function run(){
        $categories = [];
        if(!empty($this->block['categories'])){
            $categories = $this->block['categories'];
        }

        $images = $this->block['images'];

        return $this->render("LandingSliderFluidView", [
            'categories'=>$categories,
            'images'=>$images,
        ]);

    }
}
?>