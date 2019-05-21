<?php


namespace frontend\modules\account\controllers;


use yii\web\Controller;

class BaseAccountController extends Controller
{
    public function beforeAction($action)
    {
        $before = parent::beforeAction($action);
        if(\Yii::$app->user->isGuest){
            return \Yii::$app->response->redirect('/login.html?rel=my-weshop.html');
        }
        return $before;
    }
}