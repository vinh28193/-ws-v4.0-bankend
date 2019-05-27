<?php

namespace weshop\modules\landing\views\widgets\LandingJunFiveImgTwoRowOneBig;

use weshop\views\weshop\widgets\BaseWidget;

class LandingJunFiveImgTwoRowOneBigWidget extends BaseWidget
{
    public $block = [];

    public function run()
    {

        $images = [];
        if (isset($this->block['Brand'])) {
            $images = $this->block['Brand'];
        }
        return $this->render("LandingJunFiveImgTwoRowOneBigView", [
            'images' => $images
        ]);
    }
}

?>