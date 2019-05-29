<?php
namespace landing\widgets\ListImage;
use landing\LandingWidget;

class ListImageWidget extends LandingWidget{
    public $block = [];

    public function run(){
        $images = [];
        if($this->block['grid']){
            $images =  $this->block['grid'];
        }
        return $this->render("ListImageView", [
            //'block'=>$this->block,
            'images'=>$images
        ]);
    }
}
?>