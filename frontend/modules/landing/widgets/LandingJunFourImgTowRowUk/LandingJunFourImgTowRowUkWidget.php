<?php

namespace landing\widgets\LandingJunFourImgTowRowUk;

use landing\LandingWidget;

class LandingJunFourImgTowRowUkWidget extends LandingWidget
{
    public $block = [];

    public function run()
    {

        $images = [];
        if (isset($this->block['images'])) {
            $images = $this->block['images'];
        }
        return $this->render("LandingJunFourImgTowRowUkView", [
            'images' => $images
        ]);
    }
}

?>