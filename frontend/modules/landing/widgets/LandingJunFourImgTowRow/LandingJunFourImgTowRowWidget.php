<?php

namespace landing\widgets\LandingJunFourImgTowRow;

use landing\LandingWidget;

class LandingJunFourImgTowRowWidget extends LandingWidget
{
    public $block = [];

    public function run()
    {

        $images = [];
        if (isset($this->block['images'])) {
            $images = $this->block['images'];
        }
        return $this->render("LandingJunFourImgTowRowView", [
            'images' => $images
        ]);
    }
}

?>