<?php
namespace weshop\modules\landing\views\widgets\LandingJunSixImgOneRow;
use weshop\views\weshop\widgets\BaseWidget;
class LandingJunSixImgOneRowWidget extends BaseWidget{
    public $block = [];

    public function run(){

        $images = [];
        if(isset($this->block['Brand'])){
            $images = $this->block['Brand'];
        }
//        echo '<pre>';
//        print_r($this->block['Brand']);
//        echo '</pre>';


        return $this->render("LandingJunSixImgOneRowView", [
            'images'=>$images
        ]);
    }
}
?>