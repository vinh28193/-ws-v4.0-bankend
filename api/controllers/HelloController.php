<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-04
 * Time: 15:40
 */

namespace api\controllers;

use app\models\User;
use common\models\Order;
use Yii;

class HelloController extends BaseApiController
{

    public function rules()
    {
        return parent::rules();
    }

    public function actionIndex(){
        $order= new Order();
        return $this->response(true,'you are connected',Order::findOne(1));
    }
}