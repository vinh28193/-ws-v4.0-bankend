<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-28
 * Time: 09:30
 */

namespace api\controllers;


use common\components\TestObject;

class TesterController extends \yii\rest\Controller
{

    public function actionIndex(){
        $object = new TestObject();
        $object->type = 'isA';
        $object->isA();
        die;
        return 0;
    }
}