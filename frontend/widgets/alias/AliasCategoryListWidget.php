<?php


namespace frontend\widgets\alias;

use yii\bootstrap4\Widget;

class AliasCategoryListWidget extends Widget
{
    public $wsCategoryGroups;
    public $wsAliasItem;

    public function run()
    {
        return $this->render("alias_category_list", [
            'wsCategoryGroups' => $this->wsCategoryGroups,
            'wsAliasItem'=>$this->wsAliasItem
        ]);
    }
}