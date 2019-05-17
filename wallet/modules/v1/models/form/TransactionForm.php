<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 4/28/2018
 * Time: 9:53 AM
 */

namespace wallet\modules\v1\models\form;


use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use wallet\modules\v1\models\enu\ResponseCode;
use wallet\modules\v1\models\WalletTransaction;

/**
 * Class TransactionForm
 * @package wallet\modules\v1\models\form
 *
 * @property-read bool|\wallet\modules\v1\models\WalletClient|null|\yii\web\IdentityInterface $walletClient
 */
class TransactionForm extends Model
{
    /**
     * wallet merchant id
     * @var integer
     */
    public $merchant_id = 1;

    /**
     * @var string;
     */
    public $type = WalletTransaction::TYPE_PAY_ORDER;
    /**
     * Current payment code. If order => binCode, if addfee => binAddition,if other => etc...
     * @var string
     * @example BS4F444442B,BS4F444442Bfee9999
     */
    public $transaction_code;

    /**
     * total local amout to request payment
     * @var string
     */
    public $total_amount;

    /**
     * total promotion amount request payment
     * @var string
     */
    public $promotion_amount;

    /**
     * @var string
     */
    public $payment_method;
    /**
     * @var string
     */
    public $payment_provider;

    /**
     * @var string
     */
    public $bank_code;

    /**
     * @var integer
     */
    public $otp_receive_type = WalletTransaction::VERIFY_RECEIVE_TYPE_PHONE;
    /**
     * @var bool | \wallet\modules\v1\models\WalletClient
     */
    private $_wallet_client = false;

    public function init()
    {
        parent::init();
        if (ArrayHelper::isIn($this->getWalletClient()->getId(), [1, 3])) {
            $this->merchant_id = 4;
        }
    }

    /**
     * @return bool|\wallet\modules\v1\models\WalletClient|null|\yii\web\IdentityInterface
     */
    protected function getWalletClient()
    {
        if (!$this->_wallet_client) {
            $this->_wallet_client = Yii::$app->user->identity;
        }
        return $this->_wallet_client;
    }

    public function formName()
    {
        return '';
    }

    public function getFirstErrors()
    {
        $firstError = parent::getFirstErrors();
        return reset($firstError);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['transaction_code', 'total_amount'], 'required'],
            [['merchant_id', 'total_amount'], 'integer'],
            [['transaction_code', 'payment_method', 'payment_provider', 'bank_code'], 'string', 'max' => 255],
            [['transaction_code', 'total_amount'], 'filter', 'filter' => 'trim'],
            ['transaction_code', 'filter', 'filter' => '\yii\helpers\Html::encode'],
            ['type', 'validateType'],
            ['otp_receive_type', 'validateOptReceiveType'],
        ]);
    }

    /**
     * Validates the amount.
     * This method serves as the inline validation for total amount.
     *
     * @param $attribute string the attribute currently being validated
     * @param $params array the additional name-value pairs given in the rule
     * @param $validator \yii\validators\InlineValidator
     */
    public function validateAmount($attribute, $params, $validator)
    {
        if (!$this->hasErrors()) {
            $walletClient = $this->walletClient;
            if (!$walletClient->crossCheckBalance($this->total_amount)) {
                $this->addError($attribute, Yii::t('wallet', 'Amount greater than {balance}', [
                    'balance' => $walletClient->getBalance(),
                ]));
            }
        }
    }

    /**
     * @param $attribute string the attribute currently being validated
     * @param $params array the additional name-value pairs given in the rule
     * @param $validator \yii\validators\InlineValidator
     */
    public function validateType($attribute, $params, $validator)
    {
        if (!$params) {
            $params = [WalletTransaction::TYPE_PAY_ORDER];
        }
        if (!$this->hasErrors()) {
            if (!ArrayHelper::isIn($this->$attribute, $params)) {
                $this->addError($attribute, Yii::t('wallet', 'type must be PAY_ORDER'));
            }
        }
    }

    /**
     * @param $attribute string the attribute currently being validated
     * @param $params array the additional name-value pairs given in the rule
     * @param $validator \yii\validators\InlineValidator
     */
    public function validateOptReceiveType($attribute, $params, $validator)
    {
        if (!$params) {
            $params = [
                WalletTransaction::VERIFY_RECEIVE_TYPE_PHONE,
                WalletTransaction::VERIFY_RECEIVE_TYPE_EMAIL,
                WalletTransaction::VERIFY_RECEIVE_TYPE_NONE
            ];
        }
        if (!$this->hasErrors()) {
            if (!ArrayHelper::isIn($this->$attribute, $params)) {
                $this->addError($attribute, Yii::t('wallet', '{attribute} must be either one of 0:type phone or 1:type email', [
                    'attribute' => $attribute
                ]));
            }
        }
    }

    /**
     * @return array
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function makeTransaction()
    {
        if (!$this->validate()) {
            return ['code' => ResponseCode::INVALID, 'message' => $this->getFirstErrors(), 'data' => []];
        }
        if (ArrayHelper::isIn($this->getWalletClient()->getId(), [1, 3])) {
            $this->merchant_id = 4;
        }
        $transaction = new WalletTransaction();
        $transaction->scenario = WalletTransaction::SCENARIO_PAY_ORDER;
        $transaction->wallet_merchant_id = $this->merchant_id;
        $transaction->order_number = $this->transaction_code;
        $transaction->type = $this->type;
        $transaction->payment_method = $this->payment_method;
        $transaction->payment_provider_name = $this->payment_provider;
        $transaction->payment_bank_code = $this->bank_code;
        $transaction->verify_receive_type = WalletTransaction::VERIFY_RECEIVE_TYPE_NONE;
        if ($this->otp_receive_type) {
            $transaction->verify_receive_type = $this->otp_receive_type;
        }
        $transaction->totalAmount = $this->total_amount;
        $result = $transaction->createWalletTransaction();
        if ($result['code'] == ResponseCode::SUCCESS) {
            list($queue, $code) = array_values($result['data']);
            $result['data'] = $code;
            if ($queue === true) {
                $changeBalanceForm = new ChangeBalanceForm;
                $changeBalanceForm->amount = $transaction->getTotalAmount();
                $changeBalanceForm->walletTransactionId = $transaction->getPrimaryKey();
                if (($rs = $changeBalanceForm->freeze())['success'] === false) {
                    $result['message'] = $rs['message'];
                    $result['data'] = $rs['data'];
                }
            }
        }
        return $result;

    }
}