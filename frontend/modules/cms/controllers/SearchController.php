<?php


namespace frontend\modules\cms\controllers;


use frontend\controllers\CmsController;

class SearchController extends CmsController
{

    public function actionIndex(){
        return $this->render('index');
    }
}