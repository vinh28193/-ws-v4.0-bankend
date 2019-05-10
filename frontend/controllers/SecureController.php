<?php


namespace frontend\controllers;


class SecureController extends FrontendController
{

    public $layout = 'secure';

    public function actionLogin(){
        return $this->render('login');
    }
}