<?php


namespace frontend\modules\checkout;

use yii\base\BaseObject;

class Payment extends BaseObject
{
    const PAGE_CHECKOUT = 'CHECKOUT';
    const PAGE_BILLING = 'BILLING';
    const PAGE_TOP_UP = 'TOP_UP';

    public $page = self::PAGE_CHECKOUT;

}