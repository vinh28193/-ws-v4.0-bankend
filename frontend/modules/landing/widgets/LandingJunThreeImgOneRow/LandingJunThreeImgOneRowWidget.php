<?php

namespace landing\widgets\LandingJunThreeImgOneRow;

use landing\LandingWidget;

/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 07/06/2018
 * Time: 09:42
 */
class LandingJunThreeImgOneRowWidget extends LandingWidget
{
    public $block = [];

    public function run()
    {

        $images = [];
        if (isset($this->block['images'])) {
            $images = $this->block['images'];
        }
        return $this->render("LandingJunThreeImgOneRowView", [
            'images' => $images
        ]);
    }
}