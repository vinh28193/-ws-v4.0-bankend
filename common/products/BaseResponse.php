<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 09:35
 */

namespace common\products;


abstract class BaseResponse extends \yii\base\BaseObject
{

    abstract public function parser($data);
}