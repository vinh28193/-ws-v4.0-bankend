<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 14/05/2018
 * Time: 09:26
 */

namespace wallet\modules\v1\models\form;

use common\components\ThirdPartyLogs;
use common\models\PaymentProvider;
use common\modelsMongo\UtilLog;
use common\modelsMongo\WalletLog as MongoWalletLog;
use common\payment\providers\NganLuongProvider as NganLuong;
use wallet\modules\v1\models\enu\ResponseCode;
use wallet\modules\v1\models\WalletClient;
use wallet\modules\v1\models\WalletLog;
use wallet\modules\v1\models\WalletMerchant;
use wallet\modules\v1\models\WalletTransaction;
use common\models\db\WalletTransaction as ChildWalletTransaction;
use Yii;
use yii\base\Model;
use yii\helpers\Json;

class CallBackPaymentForm extends Model
{


    public $wallet_transaction_code;
    public $payment_transaction;
    public $status = WalletTransaction::STATUS_QUEUE;

    /**
     * @var WalletTransaction
     */
    protected $model_wallet_transaction;

    public function rules()
    {
        return [
            ['payment_transaction', 'required', 'message' => ResponseCode::WALLET_PAYMENT_TRANS_NOT_FOUND],
            ['wallet_transaction_code', 'required', 'message' => ResponseCode::WALLET_TRANS_NOT_FOUND],
        ];
    }

    public function init()
    {
        parent::init();
        $this->model_wallet_transaction = WalletTransaction::find()
            ->where(['wallet_transaction_code' => $this->wallet_transaction_code])
            ->one();

        $this->model_wallet_transaction->_wallet_client = WalletClient::findOne($this->model_wallet_transaction->wallet_client_id);
    }

    public function actionReturn()
    {

        if (!$this->validate()) {
            return ['code' => 'INVALID', 'message' => $this->getErrors()];
        }
//        $provider = PaymentProvider::findOne($this->model_wallet_transaction->payment_method);
//        if(!$provider){
//            return ['code' => 'INVALID', 'message' => "Không tìm thấy payment provider"];
//        }
        if(!$this->model_wallet_transaction->payment_transaction){
            return ['code' => 'INVALID', 'message' => "Không tìm thấy payment transaction"];
        }
        $dataCheck['order_code'] = $this->model_wallet_transaction->wallet_transaction_code;
        $dataCheck['token'] = $this->model_wallet_transaction->payment_transaction;
        $bill = new NganLuong();
        $bill->page = NganLuong::PAGE_CHECK_AND_UPDATE;
        $rs = $bill->handle($dataCheck);
//        switch ($this->model_wallet_transaction->wallet_merchant_id) {
//
//            case WalletMerchant::WALEET_MERCHAN_ID_ESC_PRO;
//                $bill = new NganLuong();
//                $bill->token = $this->payment_transaction;
//                $bill->wallet_merchant_id = WalletMerchant::WALEET_MERCHAN_ID_ESC_PRO;
//                $rs = $bill->checkPayment();
//                break;
//
//            case WalletMerchant::WALEET_MERCHAN_ID_ESC_DEV;
//                $bill = new NganLuong();
//                $bill->submitUrl = '';
//                $rs = $bill->checkPayment();
//                break;
//        }
        $rs = json_decode($rs, true);
        $notifyType = false;
//        $log = new UtilLog();
//        $log->action = 'return topup Before 1';
//        $log->request = $this->model_wallet_transaction;
//        $log->respone = ($rs['success']);
//        $log->time = date('d-m-Y H:i:s');
//        $log->save();

        if ($rs['success'] == true) {
            //todo update payment transaction
            if (round($rs['data']['response_content']['total_amount']) == round($this->model_wallet_transaction->credit_amount) && $this->model_wallet_transaction->status != WalletTransaction::STATUS_COMPLETE) {
                $this->model_wallet_transaction->payment_transaction = $this->payment_transaction;
                $this->model_wallet_transaction->request_content = json_encode($rs['data']['request_content']);
                $this->model_wallet_transaction->response_content =json_encode($rs['data']['response_content']);
                $this->model_wallet_transaction->updateTransaction(WalletTransaction::STATUS_COMPLETE, true);
//                $log = new UtilLog();
//                $log->action = 'return topup NL Success2';
//                $log->request = $this->model_wallet_transaction;
//                $log->respone = json_encode($rs);
//                $log->time = date('d-m-Y H:i:s');
//                $log->save();

                $wallet = new  ChangeBalanceForm();
                $wallet->amount = $rs['data']['response_content']['total_amount'];
                $wallet->walletTransactionId = $this->model_wallet_transaction->id;
                $wallet->wallet_client = $this->model_wallet_transaction->_wallet_client;
                $wallet = $wallet->topUp();

                $log = new UtilLog();
                $log->action = 'return topup wallet Success3';
                $log->request = $this->model_wallet_transaction;
                $log->respone = json_encode($wallet);
                $log->time = date('d-m-Y H:i:s');
                $log->save();

                $notifyType = \common\mail\Template::TYPE_TRANSACTION_TYPE_TOP_UP_SUCCESS;
            }
        }

        if($rs['success'] != true && $this->status == WalletTransaction::STATUS_FAIL){
            $this->model_wallet_transaction->updateTransaction(WalletTransaction::STATUS_FAIL, true);
            $notifyType = \common\mail\Template::TYPE_TRANSACTION_TYPE_TOP_UP_FAILED;

//            $log = new UtilLog();
//            $log->action = 'return topup false';
//            $log->request = $this->model_wallet_transaction;
//            $log->respone = json_encode($rs);
//            $log->time = date('d-m-Y H:i:s');
//            $log->save();

        }
        if($notifyType !== false && ($client = $this->model_wallet_transaction->getCurrentWalletClient())->refresh()){
            $this->model_wallet_transaction->setCurrentWalletClient($client);
            $manager = new \common\mail\MailManager();
            $manager->setType($notifyType);
            $manager->setActiveModel($this->model_wallet_transaction);
            $manager->setStore(1);
            $manager->setReceiver($this->model_wallet_transaction);
            $manager->send();
        }

        $resp ['success'] = !empty($wallet['success']) ? $wallet['success'] : false;
        $resp ['message'] = !empty($rs['message']) ? $rs['message'] : false;
        $resp ['data'] = !empty($rs['data']) ? $rs['data'] : false;

        @date_default_timezone_set("Asia/Ho_Chi_Minh");
        ThirdPartyLogs::setLog('Wallet', 'CallBackPaymentForm', 'nganluong', $this, $resp);
        $logWallet = new MongoWalletLog();
        $logWallet->action = 'check payment';
        $logWallet->order_transaction = $this->wallet_transaction_code;
        $logWallet->payment_transaction = $this->payment_transaction;
        $logWallet->request = $this;
        $logWallet->respone = $resp;
        $logWallet->create_time = date('Y-m-d H:i:s');
        $logWallet->save(false);

        return $resp;
    }

}