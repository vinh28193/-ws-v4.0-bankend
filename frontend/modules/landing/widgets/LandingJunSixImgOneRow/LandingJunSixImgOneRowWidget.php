<?php

namespace landing\widgets\LandingJunSixImgOneRow;

use landing\LandingWidget;

class LandingJunSixImgOneRowWidget extends LandingWidget
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


        return $this->render("LandingJunSixImgOneRowView", [
            'images' => $images
        ]);
    }
}

?>