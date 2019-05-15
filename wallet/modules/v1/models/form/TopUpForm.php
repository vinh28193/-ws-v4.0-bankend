<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 4/23/2018
 * Time: 4:51 PM
 */

namespace wallet\modules\v1\models\form;


use common\models\PaymentMethod;
use common\models\PaymentProvider;
use common\modelsMongo\WalletLogWs as MongoWalletLog;
use wallet\modules\v1\models\WalletTransaction;
use yii\base\Model;

class TopUpForm extends Model
{
    public $amount_total;
    public $method;
    public $note;

    public $merchant_id;
    public $transaction_code;
    public $payment_provider;
    public $type = WalletTransaction::TYPE_TOP_UP;
    public $bank_code;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['amount_total', 'integer', 'min' => 0],
            ['amount_total', 'required', 'message' => 'Please amount is not null.'],
            ['merchant_id', 'required', 'message' => 'Please merchant_id is not null.'],
            ['payment_provider', 'required', 'message' => 'Please payment_provider is not null.'],
            ['method', 'required', 'message' => 'Please payment_method is not null.'],
            ['bank_code', 'required', 'message' => 'Please bank_code is not null.'],
        ];
    }

    /**
     *Tao yeu cau nap tien
     */
    public function topUpRequest()
    {
        if (!$this->validate()) {
            return ['code' => 'INVALID', 'message' => $this->getErrors()];
        }
        $payment_provider = PaymentProvider::getById($this->payment_provider);
        $payment_method = PaymentMethod::getByPk($this->method);
        $transaction = new WalletTransaction();
        $transaction->setScenario(WalletTransaction::SCENARIO_TOPUP);
        $transaction->wallet_merchant_id = $this->merchant_id;
        $transaction->order_number = $this->transaction_code;
        $transaction->type = $this->type;
        $transaction->payment_method = !empty($payment_method->Name)? $payment_method->Name:$this->method;
        $transaction->payment_provider_name = !empty($payment_provider['Name'])?$payment_provider['Name']:$this->payment_provider;
        $transaction->payment_bank_code = $this->bank_code;
        $transaction->totalAmount = $this->amount_total;
        $transaction->note = $this->note;
        $transaction->verify_receive_type = WalletTransaction::VERIFY_RECEIVE_TYPE_EMAIL;
        $res = $transaction->createWalletTransaction();

        $logWallet = new MongoWalletLog();
        $logWallet->action = 'make payment';
        $logWallet->order_transaction = $res['data']['code'];
        $logWallet->request = $this;
        $logWallet->respone = $res;
        $logWallet->create_time = date('Y-m-d H:i:s');
        $logWallet->save(false);

        if($transaction->refresh()){
            $transaction->sendMessageAndEmail(WalletTransaction::VERIFY_RECEIVE_TYPE_EMAIL);
        }

        if ($res['code'] == 'OK') {
            return $res['message'];
            //$this->topUp();
            //return $transaction->getWalletTransactionCode();
        }
        return $res;
    }

    /**
     * Thuc hien nap tien cho vi
     */
    public function topUp()
    {
        $isOk = new ChangeBalanceForm();
        $isOk->amount = $this->amount_total;
        return $isOk->topUp();
    }
}