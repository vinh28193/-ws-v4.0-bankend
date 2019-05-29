<?php

namespace landing\widgets\LandingJunFiveImgTwoRowOneBig;

use landing\LandingWidget;

class LandingJunFiveImgTwoRowOneBigWidget extends LandingWidget
{
    public $block = [];

    public function run()
    {

        $images = [];
        if (isset($this->block['images'])) {
            $images = $this->block['images'];
        }
        return $this->render("LandingJunFiveImgTwoRowOneBigView", [
            'images' => $images
        ]);
    }
}

?>