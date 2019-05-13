<?php


namespace frontend\modules\checkout;

use yii\base\BaseObject;

abstract class BasePaymentProvider extends BaseObject
{
    /**
     * @var $payment Payment
     */
    public $payment;

    /**
     * @param $request
     * @return void
     */
    abstract public function send($request);

    /**
     * @param $response
     * @return void
     */
    abstract public function handle($response);
}