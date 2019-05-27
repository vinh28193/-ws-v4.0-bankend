<?php
namespace weshop\modules\landing\views\widgets\LandingJunFourImgTowRowUk;
use weshop\views\weshop\widgets\BaseWidget;
class LandingJunFourImgTowRowUkWidget extends BaseWidget{
    public $block = [];

    public function run(){

        $images = [];
        if(isset($this->block['Brand'])){
            $images = $this->block['Brand'];
        }
        return $this->render("LandingJunFourImgTowRowUkView", [
            'images'=>$images
        ]);
    }
}
?>