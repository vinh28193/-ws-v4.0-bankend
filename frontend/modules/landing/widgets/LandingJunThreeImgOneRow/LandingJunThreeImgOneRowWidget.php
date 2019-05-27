<?php

namespace weshop\modules\landing\views\widgets\LandingJunThreeImgOneRow;

use weshop\views\weshop\widgets\BaseWidget;

/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 07/06/2018
 * Time: 09:42
 */
class LandingJunThreeImgOneRowWidget extends BaseWidget
{
    public $block = [];

    public function run()
    {

        $images = [];
        if (isset($this->block['Brand'])) {
            $images = $this->block['Brand'];
        }
        return $this->render("LandingJunThreeImgOneRowView", [
            'images' => $images
        ]);
    }
}