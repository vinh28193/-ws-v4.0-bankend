<?php


namespace frontend\widgets\alias;

use yii\bootstrap4\Widget;

class AliasImageGridWidget extends Widget
{
    public $wsImageGroups;

    public function run()
    {
        return $this->render("alias_image_grid", [
            'wsImageGroups' => $this->wsImageGroups,
        ]);
    }
}