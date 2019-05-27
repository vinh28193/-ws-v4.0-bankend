<?php
namespace weshop\modules\landing\views\widgets\LandingJunFiveImgTwoRowOneBannerUk;
use weshop\views\weshop\widgets\BaseWidget;
class LandingJunFiveImgTwoRowOneBannerUkWidget extends BaseWidget{
    public $block = [];

    public function run(){

        $images = [];
        if(isset($this->block['Brand'])){
            $images = $this->block['Brand'];
        }
//        echo '<pre>';
//        print_r($this->block['Brand']);
//        echo '</pre>';
//        die();


        return $this->render("LandingJunFiveImgTwoRowOneBannerUkView", [
            'images'=>$images
        ]);
    }
}
?>