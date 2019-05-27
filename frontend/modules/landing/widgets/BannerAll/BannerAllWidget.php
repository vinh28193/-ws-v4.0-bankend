<?php
namespace landing\views\widgets\BannerAll;

class BannerAllWidget extends BaseWidget{
    public $images = [];

    public function run(){
        return $this->render("BannerAllView", [
            'images'=>$this->images
        ]);
    }
}
?>