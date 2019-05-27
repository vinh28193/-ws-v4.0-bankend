<?php
namespace weshop\modules\landing\views\widgets\LandingSliderFluid;

use weshop\views\weshop\widgets\BaseWidget;
use common\models\cms\WsImageGroup;

class LandingSliderFluidWidget extends BaseWidget{
    public $block = [];

    public function run(){
        $categories = [];
        if(!empty($this->block['categories'])){
            $categories = $this->block['categories'];
        }

        $images = $this->block['Brand'];

        return $this->render("LandingSliderFluidView", [
            'categories'=>$categories,
            'images'=>$images,
        ]);

    }
}
?>