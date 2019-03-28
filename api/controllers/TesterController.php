<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-28
 * Time: 09:30
 */

namespace api\controllers;


class TesterController extends \yii\rest\Controller
{

    public function actionIndex(){
        \common\components\log\Logging::create()->product->push('test','tesing');
        die;
        return 0;
    }
}