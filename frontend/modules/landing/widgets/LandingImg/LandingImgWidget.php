<?php
namespace weshop\modules\landing\views\widgets\LandingImg;
use weshop\views\weshop\widgets\BaseWidget;

class LandingImgWidget extends BaseWidget{
    public $block = [];
    //public $images = [];

    public function run(){
        $images = [];
        if(isset($this->block['Grid'])){
            $images = $this->block['Grid'];
        }
        return $this->render("LandingImgView", [
            'images'=>$images
        ]);
    }
}
?>