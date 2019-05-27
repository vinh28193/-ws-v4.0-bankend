<?php

namespace landing\widgets\LandingImgFlush;

use landing\LandingWidget;

class LandingImgFlushWidget extends LandingWidget
{

    public $block = [];

    public function run()
    {
        $images = [];
        if (isset($this->block['Grid'])) {
            $images = $this->block['Grid'];
        }


        return $this->render("LandingImgFlushView", [
            'images' => $images
        ]);
    }
}

?>