<?php
namespace weshop\modules\landing\views\widgets\LandingImgFlush;
use weshop\views\weshop\widgets\BaseWidget;

class LandingImgFlushWidget extends BaseWidget{

    public $block = [];

    public function run(){
        $images = [];
        if(isset($this->block['Grid'])){
            $images = $this->block['Grid'];
        }


        return $this->render("LandingImgFlushView", [
            'images'=>$images
        ]);
    }
}
?>