<?php

namespace landing\widgets\LandingImgList;

use landing\LandingWidget;

class LandingImgListWidget extends LandingWidget
{

    public $block = [];

    public function run()
    {
        $images = [];
        if (isset($this->block['Grid'])) {
            $images = $this->block['Grid'];
        }

        return $this->render("LandingImgListView", [
            'images' => $images
        ]);
    }
}

?>