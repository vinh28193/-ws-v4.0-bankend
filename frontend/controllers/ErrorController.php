<?php


namespace frontend\controllers;


class ErrorController extends CmsController
{

    public function actionIndex()
    {
        $this->isShow = false;
        return $this->render('404');
    }
}