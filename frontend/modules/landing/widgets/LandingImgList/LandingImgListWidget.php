<?php
namespace weshop\modules\landing\views\widgets\LandingImgList;
use weshop\views\weshop\widgets\BaseWidget;

class LandingImgListWidget extends BaseWidget{

    public $block = [];

    public function run(){
        $images = [];
        if(isset($this->block['Grid'])){
            $images = $this->block['Grid'];
        }

        return $this->render("LandingImgListView", [
            'images'=>$images
        ]);
    }
}
?>