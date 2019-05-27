<?php

namespace landing\widgets\LandingJunFourImgOneRow;

use landing\LandingWidget;

class LandingJunFourImgOneRowWidget extends LandingWidget
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


        return $this->render("LandingJunFourImgOneRowView", [
            'images' => $images
        ]);
    }
}

?>