<?php


namespace frontend\widgets\breadcrumb;


use yii\base\Widget;

class BreadcrumbWidget extends Widget
{
    public $portal = 'Home';
    public $params = [];
    public function run()
    {
        return $this->render('breadcrumb',['portal' => $this->portal,'params' => $this->params]);
    }
}