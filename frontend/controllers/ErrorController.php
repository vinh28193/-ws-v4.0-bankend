<?php


namespace frontend\controllers;


class ErrorController extends CmsController
{

    public function actionIndex()
    {
        return $this->render('404');
    }
}