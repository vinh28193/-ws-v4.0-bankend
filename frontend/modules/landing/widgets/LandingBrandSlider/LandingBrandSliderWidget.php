<?php

namespace landing\widgets\LandingBrandSlider;

use landing\LandingWidget;

class LandingBrandSliderWidget extends LandingWidget
{
    public $block = [];

    public function run()
    {

        $images = [];
        if (isset($this->block['images'])) {
            $images = $this->block['images'];
        }


        return $this->render("LandingBrandSliderView", [
            'images' => $images
        ]);
    }
}

?>