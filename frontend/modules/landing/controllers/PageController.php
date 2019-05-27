<?php


namespace frontend\modules\landing\controllers;


use common\models\cms\PageService;

class PageController extends LandingController
{


    public function actionIndex()
    {
        return $this->render('index');
    }
}