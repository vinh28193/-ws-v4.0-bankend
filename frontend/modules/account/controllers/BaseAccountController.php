<?php


namespace frontend\modules\account\controllers;


use yii\base\Controller;

class BaseAccountController extends Controller
{
    public function beforeAction($action)
    {
        $before = parent::beforeAction($action);
        if(\Yii::$app->user->isGuest){
            return \Yii::$app->response->redirect('/login.html?rel=myWeShop.html');
        }
        return $before;
    }
}