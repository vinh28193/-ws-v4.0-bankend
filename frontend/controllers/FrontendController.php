<?php


namespace frontend\controllers;


class FrontendController extends \yii\web\Controller
{

    public function actionNotFound()
    {
        return $this->render('404', func_get_args());
    }
}