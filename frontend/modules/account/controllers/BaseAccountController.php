<?php


namespace frontend\modules\account\controllers;


use common\components\StoreManager;
use Yii;
use yii\web\Controller;

class BaseAccountController extends Controller
{
    /** @var StoreManager $storeManager */
    public $storeManager;
    public function beforeAction($action)
    {
        $before = parent::beforeAction($action);
        if(\Yii::$app->user->isGuest){
            return \Yii::$app->response->redirect('/login.html?rel=my-weshop.html');
        }
        $this->storeManager = Yii::$app->storeManager;
        return $before;
    }
}