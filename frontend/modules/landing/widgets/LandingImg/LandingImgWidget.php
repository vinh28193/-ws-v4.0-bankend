<?php

namespace landing\widgets\LandingImg;

use landing\LandingWidget;

class LandingImgWidget extends LandingWidget
{
    public $block = [];

    //public $images = [];

    public function run()
    {
        $images = [];
        if (isset($this->block['grid'])) {
            $images = $this->block['grid'];
        }
        if(!$images && isset($this->block['images'])){
            $images = $this->block['images'];
        }
        return $this->render("LandingImgView", [
            'images' => $images
        ]);
    }
}

?>