<?php
namespace weshop\modules\landing\views\widgets\LandingJunSevenImgTowRow;
use weshop\views\weshop\widgets\BaseWidget;
class LandingJunSevenImgTowRowWidget extends BaseWidget{
    public $block = [];

    public function run(){

        $images = [];
        if(isset($this->block['Brand'])){
            $images = $this->block['Brand'];
        }
        return $this->render("LandingJunSevenImgTowRowView", [
            'images'=>$images
        ]);
    }
}
?>