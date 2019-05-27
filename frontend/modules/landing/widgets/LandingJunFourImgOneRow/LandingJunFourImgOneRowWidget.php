<?php
namespace weshop\modules\landing\views\widgets\LandingJunFourImgOneRow;
use weshop\views\weshop\widgets\BaseWidget;
class LandingJunFourImgOneRowWidget extends BaseWidget{
    public $block = [];

    public function run(){

        $images = [];
        if(isset($this->block['Brand'])){
            $images = $this->block['Brand'];
        }
//        echo '<pre>';
//        print_r($this->block['Brand']);
//        echo '</pre>';


        return $this->render("LandingJunFourImgOneRowView", [
            'images'=>$images
        ]);
    }
}
?>