<?php

namespace landing\widgets\LandingImgList;

use landing\LandingWidget;

class LandingImgListWidget extends LandingWidget
{

    public $block = [];

    public function run()
    {
        $images = [];
        if (isset($this->block['grid'])) {
            $images = $this->block['grid'];
        }
        if(!$images && isset($this->block['images'])){
            $images = $this->block['images'];
        }

        return $this->render("LandingImgListView", [
            'images' => $images
        ]);
    }
}

?>