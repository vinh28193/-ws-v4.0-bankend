<?php

namespace landing\widgets\LandingJunFiveImgTwoRowOneBannerUk;

use landing\LandingWidget;

class LandingJunFiveImgTwoRowOneBannerUkWidget extends LandingWidget
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
//        die();


        return $this->render("LandingJunFiveImgTwoRowOneBannerUkView", [
            'images' => $images
        ]);
    }
}

?>