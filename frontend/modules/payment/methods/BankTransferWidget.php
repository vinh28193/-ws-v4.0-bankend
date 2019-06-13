<?php


namespace frontend\modules\payment\methods;

use yii\helpers\Json;

class BankTransferWidget extends MethodWidget
{

    public function init()
    {
        parent::init();
        $this->registerClientScript();
    }

    public function run()
    {
        parent::run();
        return $this->render('bank_transfer');
    }

    protected function registerClientScript()
    {
        $methods = Json::htmlEncode($this->methods);
        $this->getView()->registerJs("ws.payment.registerMethods($methods);");
        $isNew = ($this->payment->payment_method !== 1 && $this->payment->payment_bank_code === null);
        if ($isNew) {
            $this->getView()->registerJs("ws.payment.methodChange(true);");
        } else {
            $this->getView()->registerJs("ws.payment.methodChange(false);");
        }
    }
}