<?php


namespace frontend\widgets\alias;

use yii\bootstrap4\Widget;
use yii\helpers\ArrayHelper;

class AliasProductSliderWidget extends Widget
{
    public $wsProductGroups;
    public $index;
    public $wsAliasItem;
    public function run()
    {
        return $this->render("alias_product_slider", [
            'wsProductGroups' => $this->wsProductGroups,
            'index' => $this->index,
            'item' => $this->wsAliasItem,
        ]);
    }

}