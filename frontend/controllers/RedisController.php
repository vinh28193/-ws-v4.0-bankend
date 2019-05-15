<?php
namespace frontend\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class RedisController extends FrontendController
{
    public function actionIndex()
    {
        Yii::$app->session->setFlash('contactFormSubmitted');
        $a = Yii::$app->session->getFlash('contactFormSubmitted');
        echo $a;
    }

    public function actionCaches()
    {
        $cache = Yii::$app->cache;
        $key = 'new';
        $data = $cache->get($key);
        if ($data === false) {
            $key = 'new';
            $data = 'A newly cache added';
            $cache->set($key, $data);
        }
        echo $data;
    }

}
