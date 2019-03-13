<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 3/13/2019
 * Time: 9:55 AM
 */



namespace common\filters;

use Yii;
class Cors extends \yii\filters\Cors
{
    public function beforeAction($action)
    {
        parent::beforeAction($action);

        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            Yii::$app->getResponse()->getHeaders()->set('Allow', 'POST GET PUT');
            Yii::$app->end();
        }

        return true;
    }
}