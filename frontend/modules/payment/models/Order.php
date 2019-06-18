<?php


namespace frontend\modules\payment\models;

use common\components\AdditionalFeeInterface;
use common\components\AdditionalFeeTrait;
use common\models\Order as BaseOrder;
use common\models\User;

class Order extends BaseOrder
{

    public $cartId;

    use AdditionalFeeTrait;
}