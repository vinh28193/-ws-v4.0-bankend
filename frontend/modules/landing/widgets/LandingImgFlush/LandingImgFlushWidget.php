<?php

namespace landing\widgets\LandingImgFlush;

use landing\LandingWidget;

class LandingImgFlushWidget extends LandingWidget
{

    public $block = [];

    public function run()
    {
        $images = [];
        if (isset($this->block['grid'])) {
            $images = $this->block['grid'];
        }
        if(!$images && isset($this->block['images'])){
            $images = $this->block['images'];
        }


        return $this->render("LandingImgFlushView", [
            'images' => $images
        ]);
    }
}

?>