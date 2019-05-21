<?php


namespace frontend\modules\account\views\widgets;


use common\models\PaymentBank;
use yii\base\Widget;

class TabWithdrawWidget extends Widget
{
    public $method;
    public function run()
    {
        switch ($this->method){
            case 'nl':
                return $this->render($this->method);
                break;
            case 'bank':
                $banks = PaymentBank::find()->where(['store_id' => 1, 'status' => 1])->orderBy('name')->all();
                return $this->render($this->method,['banks' => $banks ]);
                break;
        }
    }
}