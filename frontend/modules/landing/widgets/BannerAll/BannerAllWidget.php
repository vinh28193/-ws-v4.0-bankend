<?php

namespace landing\widgets\BannerAll;

use landing\LandingWidget;

class BannerAllWidget extends LandingWidget
{
    public $images = [];

    public function run()
    {
        return $this->render("BannerAllView", [
            'images' => $this->images
        ]);
    }
}

?>