<?php
namespace weshop\modules\landing\views\widgets\LandingJunFiveImgTwoRowOneBanner;
use weshop\views\weshop\widgets\BaseWidget;
class LandingJunFiveImgTwoRowOneBannerWidget extends BaseWidget{
    public $block = [];

    public function run(){

        $images = [];
        if(isset($this->block['Brand'])){
            $images = $this->block['Brand'];
        }
//        echo '<pre>';
//        print_r($this->block['Brand']);
//        echo '</pre>';


        return $this->render("LandingJunFiveImgTwoRowOneBannerView", [
            'images'=>$images
        ]);
    }
}
?>