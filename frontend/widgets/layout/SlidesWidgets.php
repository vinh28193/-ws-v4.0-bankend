<?php


namespace frontend\widgets\layout;


use yii\base\Widget;

class SlidesWidgets extends Widget
{
    public function run()
    {
        return $this->render('slides');
    }
}