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
        if (isset($this->block['Grid'])) {
            $images = $this->block['Grid'];
        }
        return $this->render("LandingImgView", [
            'images' => $images
        ]);
    }
}

?>