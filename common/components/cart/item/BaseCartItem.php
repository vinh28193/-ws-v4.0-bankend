<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-27
 * Time: 14:16
 */

namespace common\components\cart\item;

abstract class BaseCartItem extends \yii\base\Model
{
    /**
     * @return $this;
     */
    abstract public function process();
}