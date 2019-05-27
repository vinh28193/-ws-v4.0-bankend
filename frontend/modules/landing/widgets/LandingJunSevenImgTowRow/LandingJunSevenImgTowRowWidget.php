<?php

namespace landing\widgets\LandingJunSevenImgTowRow;

use landing\LandingWidget;

class LandingJunSevenImgTowRowWidget extends LandingWidget
{
    public $block = [];

    public function run()
    {

        $images = [];
        if (isset($this->block['images'])) {
            $images = $this->block['images'];
        }
        return $this->render("LandingJunSevenImgTowRowView", [
            'images' => $images
        ]);
    }
}

?>