<?php


namespace frontend\widgets\layout;


use yii\base\Widget;

class HeaderWidget extends Widget
{
    public function run()
    {
        return $this->render('header');
    }
}