<?php

namespace landing\widgets\DealBanner;

use landing\LandingWidget;

class DealBannerWidget extends LandingWidget
{
    public $images = [];

    public function run()
    {
        return $this->render("DealBannerView", [
            'images' => $this->images
        ]);
    }
}

?>