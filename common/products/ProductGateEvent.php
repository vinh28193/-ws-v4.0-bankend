<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 10:04
 */

namespace common\products;

use yii\base\Event;

class ProductGateEvent extends Event
{
    public $request;
    public $response;
}