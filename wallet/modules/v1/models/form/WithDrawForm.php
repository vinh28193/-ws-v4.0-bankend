<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 4/23/2018
 * Time: 5:03 PM
 */

namespace wallet\modules\v1\models\form;


use common\models\model\PaymentProvider;
use wallet\modules\v1\models\WalletTransaction;
use yii\base\Model;

class WithDrawForm extends Model
{
    public $amount_total;
    public $method;
    public $note;

    public $typeSendOtp;
    public $requestContent;
    public $merchant_id;
    public $transaction_code;
    public $payment_provider;
    public $type = WalletTransaction::TYPE_WITH_DRAW;
    public $bank_code;
    /**
     *
     */
    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['amount_total','integer', 'min' => 0],
            ['amount_total', 'required', 'message' => 'Please amount is not null.'],
            ['merchant_id', 'required', 'message' => 'Please merchant_id is not null.'],
            ['payment_provider', 'required', 'message' => 'Please payment_provider is not null.'],
            ['method', 'required', 'message' => 'Please payment_method is not null.'],
            ['bank_code', 'required', 'message' => 'Please bank_code is not null.'],
            ['typeSendOtp', 'required', 'message' => 'Please type send otp is not null.'],
            ['requestContent', 'required', 'message' => 'Please request_content is not null.'],
        ];
    }

    /**
     * @return array|mixed
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function withDrawRequest()
    {
        if (!$this->validate()) {
            return ['code' => 'INVALID', 'message' => $this->getErrors()];
        }
        $transaction = new WalletTransaction();
        $transaction->setScenario(WalletTransaction::SCENARIO_TOPUP);
        $transaction->wallet_merchant_id = $this->merchant_id;
        $transaction->order_number = $this->transaction_code;
        $transaction->type = $this->type;
        $transaction->request_content = $this->requestContent;
        $transaction->verify_receive_type = $this->typeSendOtp;
        $transaction->payment_method = $this->method;
        $transaction->payment_provider_name = $this->payment_provider;
        $transaction->payment_bank_code = $this->bank_code;
        $transaction->totalAmount = $this->amount_total;
        $transaction->note = $this->note;
        $res = $transaction->createWalletTransaction();
        if ($res['code'] == 'OK') {
            return $res['message'];
        }
        return $res;
    }

    /**
     *
     */
    public function withDraw()
    {
        $isOk = new ChangeBalanceForm();
        return $isOk->withDraw();
    }
}