<?php
namespace weshop\modules\landing\views\widgets\ListImage;
use weshop\views\weshop\widgets\BaseWidget;

class ListImageWidget extends BaseWidget{
    public $block = [];

    public function run(){
        $images = [];
        if($this->block['Grid']){
            $images =  $this->block['Grid'];
        }
        return $this->render("ListImageView", [
            //'block'=>$this->block,
            'images'=>$images
        ]);
    }
}
?>