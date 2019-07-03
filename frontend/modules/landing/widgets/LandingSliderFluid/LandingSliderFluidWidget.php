<?php
namespace landing\widgets\LandingSliderFluid;

use common\models\cms\WsImage;
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
        $data = [];
        if($images){
            foreach ($images as $image){
                $newImg = new WsImage();
                $newImg->setAttributes($image,false);
                $data[] = $newImg;
            }
        }
        return $this->render("LandingSliderFluidView", [
            'categories'=>$categories,
            'images'=>$data,
        ]);

    }
}
?>