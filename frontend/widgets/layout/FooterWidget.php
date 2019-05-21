<?php
namespace frontend\widgets\layout;

class FooterWidget extends \yii\base\Widget
{
    public function run()
    {
        return $this->render('footer');
    }
}