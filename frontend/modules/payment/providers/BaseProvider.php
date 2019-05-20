<?php

namespace frontend\modules\payment\providers;


use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentProviderInterface;
use Yii;
use yii\base\BaseObject;
use common\models\PaymentProvider;

abstract class BaseProvider extends BaseObject implements PaymentProviderInterface
{

    const ENV_SANDBOX = 'sandBox';
    const ENV_END = 'sandBox';
    public $env = self::ENV_SANDBOX;

    public $submitUrl;

    public $configs = [];

    /**
     * @var Payment;
     */
    public $payment;
    public function getConfig()
    {
        return isset($this->configs[$this->env]) ? $this->configs[$this->env] : null;
    }


    abstract public function createPayment();

    public function handle($data)
    {

    }

    /**
     * @param $id
     * @return PaymentProvider|null
     */
    protected function findPaymentProvider($id)
    {
        return PaymentProvider::findOne($id);
    }

}