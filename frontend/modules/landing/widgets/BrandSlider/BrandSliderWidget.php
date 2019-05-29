<?php

namespace landing\widgets\BrandSlider;

use landing\LandingWidget;

class BrandSliderWidget extends LandingWidget
{
    public $block = [];

    public function run()
    {

        $images = [];
        if (isset($this->block['images'])) {
            $images = $this->block['images'];
        }


        return $this->render("BrandSliderView", [
            'images' => $images
        ]);
    }
}

?>