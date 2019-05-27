<?php

namespace landing\widgets\LandingJunFiveImgTwoRowOneBanner;

use landing\LandingWidget;

class LandingJunFiveImgTwoRowOneBannerWidget extends LandingWidget
{
    public $block = [];

    public function run()
    {

        $images = [];
        if (isset($this->block['images'])) {
            $images = $this->block['images'];
        }
//        echo '<pre>';
//        print_r($this->block['images']);
//        echo '</pre>';


        return $this->render("LandingJunFiveImgTwoRowOneBannerView", [
            'images' => $images
        ]);
    }
}

?>