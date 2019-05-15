<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 5/2/2018
 * Time: 1:16 PM
 */

namespace wallet\modules\v1\models\form;


use common\models\model\NotifyTemplate;
use common\models\model\PaymentMethod;
use common\models\model\PaymentProvider;
use wallet\modules\v1\models\enu\ResponseCode;
use wallet\modules\v1\models\WalletClient;
use Yii;
use yii\base\Model;
use wallet\modules\v1\models\WalletTransaction;

class RefundRequestForm extends Model
{

    /**
     * wallet merchant id
     * @var integer
     */
    public $merchant_id;

    /**
     * @var string;
     */
    public $type = WalletTransaction::TYPE_REFUND;

    /**
     * @var string;
     */
    public $order_number;


    /**
     * @var string;
     */
    public $amount_total;

    /**
     * @var string;
     */
    public $scenario = WalletTransaction::SCENARIO_REFUND;

    /**
     * @var string;
     */
    public $note;

    /**
     * @var string;
     */
    public $wallet_client_id;
    /**
     * @var string;
     */
    public $payment_method_id;
    /**
     * @var string;
     */
    public $payment_provider_id;


    /**
     * @var Object model WalletClient;
     */
    protected $_wallet_client;

    /**
     * @var object Payment_method;
     */
    protected $payment_method;

    /**
     * @var object Payment_provider;
     */
    protected $payment_provider;

    /**
     * @var string;
     */
    protected $payment_bank_code;

    /**
     * @var string;
     */
    protected $walletTransaction_code;

    /**
     * @var WalletTransaction;
     */
    protected $walletTransaction;

    /**
     *Tao yeu cau refund
     */

    public function init()
    {
        parent::init();
        $this->_wallet_client = WalletClient::findOne($this->wallet_client_id);
        $this->payment_method = PaymentMethod::getById($this->payment_method_id);
        $this->payment_provider = PaymentProvider::getById($this->payment_provider_id);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['amount_total', 'integer', 'min' => 1,],
            ['amount_total', 'required'],
            ['merchant_id', 'required'],
            ['note', 'required'],
            ['wallet_client_id', 'required'],
        ];
    }

    /**
     * @return array|mixed|WalletTransaction
     */
    public function refundRequest()
    {

        if (!$this->validate()) {
            return ['code' => 'INVALID', 'message' => $this->getErrors()];
        }

        $transaction = new WalletTransaction();
        $transaction->wallet_merchant_id = $this->merchant_id;
        $transaction->currentWalletClient = $this->_wallet_client;
        $transaction->type = $this->type;
        $transaction->note = $this->note;
        $transaction->payment_method = !empty($this->payment_method->alias)?$this->payment_method->alias:'WALLET_WESHOP';
        $transaction->payment_provider_name = !empty($this->payment_provider->alias)?$this->payment_provider->alias:'Wallet weshop';
        $transaction->payment_bank_code = 'Wallet';
        $transaction->totalAmount = $this->amount_total;
        $transaction->order_number = $this->order_number;
        //$transaction->detachBehavior('blameable');
        $transaction = $transaction->createWalletTransaction();

        if($transaction['code'] == ResponseCode::SUCCESS){
            $this->walletTransaction_code = $transaction['data'];
            $this->walletTransaction = WalletTransaction::find()
                ->where(['wallet_transaction_code' => $this->walletTransaction_code])
                ->one();
            $rs = $this->refund();
            $this->walletTransaction->refresh();
            if($rs['success'] === true && ($client = $this->walletTransaction->getCurrentWalletClient())->refresh()){
                $this->walletTransaction->setCurrentWalletClient($client);
                $manager = new \common\mail\MailManager();
                $manager->setType(\common\mail\Template::TYPE_TRANSACTION_TYPE_REFUND_SUCCESS);
                $manager->setReceiver($this->walletTransaction);
                $manager->setActiveModel($this->walletTransaction);
                $manager->send();
            }else{
                $this->walletTransaction->updateTransaction(WalletTransaction::STATUS_FAIL);
                return $rs;
            }
            return $transaction;
        }
        return $transaction;
    }

    /**
     * Thuc hien yeu cau refund
     */
    public function refund()
    {
        $isOk = new ChangeBalanceForm();
        $isOk->wallet_client =$this->_wallet_client;
        $isOk->amount=$this->amount_total;
        $isOk->walletTransactionId=$this->walletTransaction->id;
        return $isOk->refunded();
    }
}