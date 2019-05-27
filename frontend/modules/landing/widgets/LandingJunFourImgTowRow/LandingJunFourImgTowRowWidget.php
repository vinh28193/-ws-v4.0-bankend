<?php
namespace weshop\modules\landing\views\widgets\LandingJunFourImgTowRow;
use weshop\views\weshop\widgets\BaseWidget;
class LandingJunFourImgTowRowWidget extends BaseWidget{
    public $block = [];

    public function run(){

        $images = [];
        if(isset($this->block['Brand'])){
            $images = $this->block['Brand'];
        }
        return $this->render("LandingJunFourImgTowRowView", [
            'images'=>$images
        ]);
    }
}
?>