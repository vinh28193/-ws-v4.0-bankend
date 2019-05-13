<?php


namespace frontend\widgets\breadcrumb;


use yii\base\Widget;

class BreadcrumbWidget extends Widget
{
    public $portal;
    public function run()
    {

        return $this->render('breadcrumb',['portal' => $this->portal]);
    }
}