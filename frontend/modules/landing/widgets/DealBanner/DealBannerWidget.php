<?php
namespace weshop\modules\landing\views\widgets\DealBanner;
use weshop\views\weshop\widgets\BaseWidget;

class DealBannerWidget extends BaseWidget{
    public $images = [];

    public function run(){
        return $this->render("DealBannerView", [
            'images'=>$this->images
        ]);
    }
}
?>