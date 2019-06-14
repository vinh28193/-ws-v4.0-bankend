<?php


namespace console\controllers;

use common\components\db\Connection;
use Yii;
use yii\console\Controller;

class CategoryController extends Controller
{

    public function actionClone(){

        /** @var  $db Connection*/
        $db = Yii::$app->get('db');
        /** @var  $db Connection*/
        $db_cms = Yii::$app->get('db_cms');
    }

    public function categoryGroupMap(){
        return [
        ];
    }
}