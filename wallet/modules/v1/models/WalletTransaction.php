<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 5/8/2018
 * Time: 9:10 AM
 */

namespace wallet\modules\v1\models;


use common\mail\ReceiverInterface;
use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use wallet\modules\v1\models\enu\ResponseCode;
use wallet\modules\v1\models\form\ChangeBalanceForm;

/**
 * Class WalletTransaction
 * @package wallet\modules\v1\models
 *
 * @property boolean $isCredit
 * @property boolean $isValid
 * @property string $totalAmount
 * @property object $redis
 * @property bool|\wallet\modules\v1\models\WalletClient|null|\yii\web\IdentityInterface $currentWalletClient
 *
 * @property WalletTransactionLog[] $walletTransactionLog
 */
class WalletTransaction extends \common\models\db\WalletTransaction implements ReceiverInterface
{
    const TRANSACTION_CODE_PREFIX = 'WL';
    const OTP_COUNT_LIMIT = 5;
    const ATTEMPT_REFRESH_LIMIT = 2;

    const SCENARIO_PAY_ORDER = 'payOrder';
    const SCENARIO_TOPUP = 'topUp';
    const SCENARIO_REFUND = 'refund';

    const TYPE_TOP_UP = 'TOP_UP';
    const TYPE_FREEZE = 'FREEZE';
    const TYPE_UN_FREEZE = 'UN_FREEZE';
    const TYPE_PAY_ORDER = 'PAY_ORDER';
    const TYPE_REFUND = 'REFUND';
    const TYPE_WITH_DRAW = 'WITH_DRAW';

    const STATUS_QUEUE = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_COMPLETE = 2;
    const STATUS_CANCEL = 3;
    const STATUS_FAIL = 4;

    const VERIFY_RECEIVE_TYPE_PHONE = 0;
    const VERIFY_RECEIVE_TYPE_EMAIL = 1;

    /**
     * on dev mode set it to make static otp code
     * @var string
     */
    public $fixedOtpCode;

    /**
     * @var array
     */
    public $redisConfig = ['name' => 'WALLET_TRANSACTION_UPDATE_QUEUE'];

    /**
     * @var bool|\wallet\modules\v1\models\WalletClient|null|\yii\web\IdentityInterface
     */
    public $_wallet_client;
    /**
     * @var string|\yii\i18n\Formatter;
     */
    private $_formatter = 'formatter';
    /**
     * @var \yii\db\Connection;
     */
    private $_db;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (!$this->_formatter instanceof \yii\i18n\Formatter) {
            $this->_formatter = Yii::$app->get($this->_formatter);
        }
        if (!$this->_db) {
            $this->_db = $this->getDb();
        }
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_at',
                'updatedAtAttribute' => false,
                'value' => function ($event) {
                    return $this->_formatter->asDatetime('now');
                }
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'wallet_client_id',
                'updatedByAttribute' => false,
                'defaultValue' => null,
                'value' => function ($event) {
                    return $this->currentWalletClient->primaryKey;
                }
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_DEFAULT => [],
            self::SCENARIO_PAY_ORDER => [
                'wallet_merchant_id',
                'order_number',
                'type',
            ],
            self::SCENARIO_TOPUP => [
                'wallet_merchant_id',
                'type',
                'payment_method',
                'payment_provider_name'
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['wallet_merchant_id', 'order_number', 'type'], 'required', 'on' => self::SCENARIO_PAY_ORDER],
            [['wallet_merchant_id', 'type', 'payment_method', 'payment_provider_name'], 'required', 'on' => self::SCENARIO_TOPUP],
            [['wallet_merchant_id', 'verify_receive_type'], 'integer'],
            [['verified_at', 'create_at', 'update_at', 'complete_at', 'cancel_at', 'fail_at'], 'safe'],
            [['order_number', 'wallet_transaction_code', 'type', 'note', 'description', 'payment_method', 'payment_provider_name', 'payment_bank_code', 'payment_transaction'], 'string', 'max' => 255],
            [['request_content', 'response_content'], 'string', 'max' => 1000],
            [['verify_code'], 'string', 'max' => 10],
            [['isCredit', 'isValid', 'fixedOtpCode', 'currentWalletClient'], 'safe'],

        ];
    }

    public function getCurrentWalletClient()
    {
        if (!$this->_wallet_client) {
            $identity = Yii::$app->user->isGuest;
            if(!$this->isNewRecord && ($identity === null || ($identity && $identity->getId() !== $this->wallet_client_id))){
                $this->_wallet_client = WalletClient::findIdentity($this->wallet_client_id);
            }else{
                $this->_wallet_client = $identity;
            }

        }
        return $this->_wallet_client;
    }

    public function setCurrentWalletClient($client)
    {
        $this->_wallet_client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstErrors()
    {
        $firstError = parent::getFirstErrors();
        return reset($firstError);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWalletTransactionLog()
    {
        return $this->hasMany(WalletTransactionLog::className(), ['wallet_transaction_id' => 'id']);
    }

    /**
     * @return mixed
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function createWalletTransaction()
    {
        if (!$this->validate()) {
            return ['code' => ResponseCode::INVALID, 'message' => $this->getFirstErrors(), 'data' => null];
        }
        $token = 'Create Transaction:' . $this->type . ', amount:' . $this->getTotalAmount() . ', client identity:' . $this->currentWalletClient->username;
        if ($this->order_number !== null) {
            $token = $token . ', order code:' . $this->order_number;
        }
        $transaction = $this->_db->beginTransaction();
        try {
            Yii::info($token, __METHOD__);
            $this->generateWalletTransactionCode();
            $this->generateDescription();
            $this->status = self::STATUS_QUEUE;

            if ($this->type === self::TYPE_PAY_ORDER || $this->type === self::TYPE_WITH_DRAW) {
                $valid = $this->type === self::TYPE_PAY_ORDER
                    ? $this->currentWalletClient->crossCheckBalance($this->totalAmount)
                    : $this->totalAmount <= $this->currentWalletClient->withdrawable_balance;
                if (!$valid) {
                    $this->status = self::STATUS_FAIL;
                    $this->fail_at = $this->_formatter->asDatetime('now');
                    $this->note = 'Wallet transfer amount failed';
                    if ($this->type === self::TYPE_WITH_DRAW) {
                        $this->note = 'Withdraw amount requested greater than withdrawable balance';
                    }
                } else {
                    $this->getOtpCode();
                    $this->sendOtpCode();
                }
            }

            if ($this->save()) {
                if ($this->status === self::STATUS_QUEUE && ArrayHelper::isIn($this->type, [self::TYPE_WITH_DRAW, self::TYPE_PAY_ORDER])) {
                    $pushTime = $this->_formatter->asDatetime('now');
                    $this->redis->push([$this->getWalletTransactionCode(), $pushTime, 1]);
                }
                $log = new WalletTransactionLog();
                $log->wallet_transaction_id = $this->primaryKey;
                $log->create_at = $this->_formatter->asDatetime('now');
                $log->user_name = $this->currentWalletClient->username;
                $log->user_action = $this->type;
                $log->content = $this->getLogContent();
                $log->save();
            }

            $transaction->commit();
        } catch (\yii\db\Exception $e) {
            Yii::error($e, __METHOD__);
            $transaction->rollBack();
            return ['code' => ResponseCode::ERROR, 'message' => $e->getMessage(), 'data' => null];
        }
        $token .= ', status:' . self::getStatusLabels($this->status);
        return ['code' => ResponseCode::SUCCESS, 'message' => $token, 'data' => ['queue' => $this->status === self::STATUS_QUEUE ? true : false, 'code' => $this->getWalletTransactionCode()]];
    }


    /**
     * getter
     * @return string
     */
    public function getWalletTransactionCode()
    {
        return $this->wallet_transaction_code;
    }

    /**
     * @return string
     */
    public function generateWalletTransactionCode()
    {
        return $this->wallet_transaction_code = self::TRANSACTION_CODE_PREFIX . rand(50, 99) . time();
    }


    /**
     * Gets the otp code.
     * @param bool $regenerate whether the otp code should be regenerated.
     * @return string the verification code.
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function getOtpCode($regenerate = false, $resetCount = true)
    {
        if ($this->fixedOtpCode !== null) {
            $this->verify_code = $this->fixedOtpCode;
            $this->verify_expired_at = $this->_formatter->asTimestamp('now + 365 days');
            $this->verify_count = 1;
            return $this->verify_code;
        }
        $code = $this->verify_code;
        if ($code === null || $regenerate) {
            if($code === null){
                $resetCount = true;
            }
            $code = $this->generateOtpCode();
            $this->verify_code = $code;
            $token = 'Generate opt code:' . $this->verify_code;
            $timeExpired = (int)$this->verify_receive_type === WalletTransaction::VERIFY_RECEIVE_TYPE_EMAIL ? 'now + 300 seconds' : 'now + 120 seconds';
            $timestamp = $this->_formatter->asTimestamp($timeExpired);
            $this->verify_expired_at = $timestamp;
            $token .= ', expire at:' . $this->_formatter->asDatetime($timestamp) . ' (' . $timeExpired . ':' . $this->_formatter->asRelativeTime($timestamp) . ')';

            if ($resetCount) {
                $this->verify_count = 1;
            }

            if ($regenerate) {
                $attributes = ['verify_code', 'verify_expired_at'];
                if ($resetCount) {
                    $attributes[] = 'verify_count';
                }
                $this->update(false, $attributes);
                $token .= ', regenerate:yes';
            }
            Yii::info($token, __METHOD__);
        }

        return $code;
    }

    public function generateOtpCode()
    {
        return (string)rand(10000, 99999);
    }

    public function generateDescription()
    {
        if ($this->type === self::TYPE_TOP_UP) {
            $this->description = Yii::t('wallet','Top up amount: {totalAmount} , payment method: {paymentMethod}, payment provider: {paymentProvider}, bank code: {bankCode}', [
                'totalAmount' => $this->totalAmount,
                'paymentMethod' => $this->payment_method,
                'paymentProvider' => $this->payment_provider_name,
                'bankCode' => $this->payment_bank_code
            ]);
        } elseif ($this->type === self::TYPE_PAY_ORDER) {
            $this->description = Yii::t('wallet','Payment order: {orderNumber}', [
                'orderNumber' => $this->order_number
            ]);
        } elseif ($this->type === self::TYPE_REFUND) {
            $this->description = Yii::t('wallet','Refund order: {orderNumber}', [
                'orderNumber' => $this->order_number
            ]);
        } elseif ($this->type === self::TYPE_WITH_DRAW) {
            $this->description = Yii::t('wallet','Request Withdraw amount: {totalAmount} , request content: {request_content}, payment method: {paymentMethod},  bank code: {bankCode}', [
                'totalAmount' => $this->totalAmount,
                'request_content' => $this->request_content,
                'paymentMethod' => $this->payment_method,
                'bankCode' => $this->payment_bank_code

            ]);
        }
        return $this->description;
    }

    /**
     * Validates the otp code to see if it matches the generated otp code.
     * @param string $code user otp code
     * @param bool $caseSensitive whether the comparison should be case-sensitive
     * @return mixed whether the code is valid
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function validateOtpCode($code, $caseSensitive = false)
    {
        $otp = $this->getOtpCode(false);
        $token = 'Validatting OTP:'.$otp;
        $valid = $caseSensitive ? ($otp === $code) : strcasecmp($otp, $code) === 0;
        $res = [
            'valid' => $valid,
            'message' => Yii::t('wallet','Ok,your otp code valid'),
            'data' => []
        ];
        if ($this->fixedOtpCode !== null) {
            $token .= ', dev mode fixed opt:'. $this->fixedOtpCode;
            $res['message'] = $valid ? $res['message'] : Yii::t('wallet','Your otp is: {fixCode}', ['fixCode' => $this->fixedOtpCode]);
            return ArrayHelper::merge($res, [
                'data' => [
                    'verified' => true,
                    'count' => 1,
                    'expired' => $this->_formatter->asDatetime('now + 365 days')
                ]
            ]);
        }
        if ($this->status === self::STATUS_CANCEL) {
            return ArrayHelper::merge($res, [
                'valid' => false,
                'message' => Yii::t('wallet','Your transaction not valid'),
            ]);
        }
        $verifyCount = self::OTP_COUNT_LIMIT - ($this->verify_count !== null ? $this->verify_count : 0);
        if ((int)$verifyCount === 0) {
            $message = Yii::t('wallet','Your otp verify failed too much, your transaction has been broken');
            $this->markTransactionAsCancel();
            //Todo save logs why update transaction cancel
            return ArrayHelper::merge($res, [
                'valid' => false,
                'message' =>  $message
            ]);
        }
        if (time() > $this->verify_expired_at) {
            $message = Yii::t('wallet','You OTP expired at: {expired}', ['expired' => $this->_formatter->asDatetime($this->verify_expired_at)]);
            $this->markTransactionAsCancel();
            //Todo save logs why update transaction cancel
            return ArrayHelper::merge($res, [
                'valid' => false,
                'message' => $message,
            ]);
        }
        if (!$valid && $verifyCount > 0) {
            $this->verify_count = $this->verify_count + 1;
            $this->update(false, ['verify_count']);
            $message = 'Invalid otp, available {count} remaining';
            $res = ArrayHelper::merge($res,[
                'message' => Yii::t('wallet', $message, ['count' => $verifyCount]),
            ]);
        }
        if ($res['valid']) {
            $this->verified_at = $this->_formatter->asDatetime('now');
            $this->update(false, ['verified_at']);
            $this->updateTransaction(self::STATUS_PROCESSING);
        }
        return ArrayHelper::merge($res, [
            'data' => [
                'verified' => $this->verified_at === null ? false : true,
                'count' => $verifyCount,
                'expired' => $this->_formatter->asDatetime($this->verify_expired_at),
                'expiredTimestamp' => $this->verify_expired_at
            ]
    ]);;

    }

    /**
     * mark a transaction form queue to cancel
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function markTransactionAsCancel()
    {
        if (!$this->isValid) {
            return [
                'success' => false,
                'message' => 'Your Transaction unvalid',
                'data' => [],
                'code' => ResponseCode::INVALID
            ];
        }
        $form = new ChangeBalanceForm();
        $form->amount = $this->totalAmount;
        $form->walletTransactionId = $this->primaryKey;
        $form->wallet_client = $this->currentWalletClient;

        $isOk = $form->unFreezeAdd($this);

        if ($isOk['success'] === true) {
            $queueItems = $this->getRedis()->getAllQueueItem();
            $this->getRedis()->flush();
            foreach ((array)$queueItems as $item) {
                if (isset($item[0]) && $item[0] !== $this->getWalletTransactionCode()) {
                    $this->getRedis()->push($item);
                }
            }
            $this->updateTransaction(self::STATUS_CANCEL);
        }
        return $isOk;
    }

    /**
     * @param bool $renew
     * @param null $receiveType
     * @return array
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function sendOtpCode($receiveType = null, $renew = false, $keepCount = false)
    {
        $debugContent = true;
        $content = '';
        if ($receiveType === null && $this->verify_receive_type !== null) {
            $receiveType = $this->verify_receive_type;
        } else if ($receiveType !== null && $receiveType !== $this->verify_receive_type) {
            $this->verify_receive_type = $receiveType;
            $this->update(false, ['verify_receive_type']);
        } else {
            $receiveType = WalletTransaction::VERIFY_RECEIVE_TYPE_EMAIL;
        }
        $this->refresh();
        $otp = $this->getOtpCode($renew, $keepCount);
        $this->setScenario(self::SCENARIO_DEFAULT);
        $isSave = $this->save();
        $receiveType = (int)$receiveType;
        $sendTo = self::getOtpSendTo($receiveType);
        $isSend =  $this->sendMessageAndEmail($receiveType, $sendTo);

        $message = Yii::t('wallet','send otp success');
        if (($success = ($isSave && $isSend)) === false) {
            $message = Yii::t('wallet','send otp failed');
        }
        return [$success, $message, ['receive_type' => $receiveType, 'send_to' => $sendTo], ResponseCode::SUCCESS];
    }

    public function sendMessageAndEmail($receiveType = null, $sendTo = null)
    {
        $failed = false;
        if ($receiveType === null) {
            $receiveType = $this->verify_receive_type;
        }
        if ($sendTo === null) {
            $sendTo = self::getOtpSendTo($receiveType);
        }
        $targetId = $receiveType === self::VERIFY_RECEIVE_TYPE_EMAIL ? 'mail' : 'phone';
        $sendTo = [$targetId => $sendTo];
        if ($this->type === self::TYPE_PAY_ORDER || $this->type === self::TYPE_WITH_DRAW) {
            $sendType = \common\mail\Template::TYPE_TRANSACTION_VERIFY_CODE;
        } else if ($this->type === self::TYPE_TOP_UP) {
            $sendType = \common\mail\Template::TYPE_TRANSACTION_TYPE_TOP_UP;
        } else if ($this->type === self::TYPE_REFUND) {
            $sendType = \common\mail\Template::TYPE_TRANSACTION_TYPE_REFUND;
            $sendTo = self::className();
        }
        $manager = new \common\mail\MailManager();
        $manager->setType($sendType);
        $manager->setReceiver($sendTo);
        $manager->setActiveModel($this);
        $manager->send();

        return true;
    }

    /**
     * true if transaction type is credit
     * @var boolean
     */
    private $_isCredit;

    /**
     * @param $value
     * @return mixed
     */
    public function setIsCredit($value)
    {
        return $this->_isCredit = $value;
    }

    /**
     * getter
     * @return bool
     */
    public function getIsCredit()
    {
        $this->_isCredit = ArrayHelper::isIn($this->type, [self::TYPE_TOP_UP, self::TYPE_REFUND]);
        return $this->_isCredit;
    }

    /**
     * @var boolean
     */
    private $_isValid;

    /**
     * kiểm tra 1 transaction có đang bị đóng bắng hay không.
     * //Todo check via ChangeBalanceForm
     * @return bool
     */
    public function getIsValid()
    {
        if ($this->_isValid === null) {
            $this->_isValid = !($this->status === self::STATUS_CANCEL || $this->status === self::STATUS_FAIL);
        }
        return $this->_isValid;
    }

    /**
     * @param $valid bool
     */
    public function setIsValid($valid)
    {
        $this->_isValid = $valid;
    }

    /**
     * setter
     * @param $amount
     */
    public function setTotalAmount($amount)
    {
        $this->amount = $amount;
        if ($this->getIsCredit()) {
            $this->credit_amount = $amount;
        } else {
            $this->debit_amount = $amount;
        }
    }

    /**
     * getter
     * @return float
     */
    public function getTotalAmount()
    {
        if ($this->getIsCredit()) {
            return $this->credit_amount;
        }
        return $this->debit_amount;
    }


    public function getLogContent()
    {
        $token = 'Create wallet transaction success, type:' . $this->type;
        if ($this->isNewRecord === false) {
            $token .= ', transaction code:' . $this->wallet_transaction_code;
        }
        if ($this->isCredit) {
            $token .= ', payment method:' . $this->payment_method;
            $token .= ', payment provider:' . $this->payment_provider_name;
            $token .= ', bank code:' . $this->payment_bank_code;
            $token .= ', credit amount:' . $this->totalAmount;
        } else {
            $token .= ', order code:' . $this->order_number;
            $token .= ', debit amount:' . $this->totalAmount;
        }
        $token .= ', status:' . self::getStatusLabels($this->status);
        return $token;
    }

    /**
     * @param null $status
     * @param bool $multipleAttribute
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function updateTransaction($status = null, $multipleAttribute = false)
    {
        $attributeNames = [];
        if ($status === null) {
            $multipleAttribute = true;
        } else if ($status === $this->status && !$multipleAttribute) {
            return true;
        } else {
            $this->status = $status;
            $attributeNames[] = 'status';
        }

        $this->update_at = $this->_formatter->asDatetime('now');
        $attributeNames[] = 'update_at';

        if ($this->status == self::STATUS_PROCESSING) {

        } elseif ($this->status === self::STATUS_COMPLETE) {
            $this->complete_at = $this->_formatter->asDatetime('now');
            $attributeNames[] = 'complete_at';
        } elseif ($this->status === self::STATUS_CANCEL) {
            $this->cancel_at = $this->_formatter->asDatetime('now');
            $attributeNames[] = 'cancel_at';
        } elseif ($this->status === self::STATUS_FAIL) {
            $this->fail_at = $this->_formatter->asDatetime('now');
            $attributeNames[] = 'fail_at';
        }
        if ($this->scenario !== self::SCENARIO_DEFAULT) {
            $this->scenario = self::SCENARIO_DEFAULT;
        }
        return $this->save(true, $multipleAttribute ? null : $attributeNames);
    }

    /**
     * Kiem tra 1 giao dich thanh cong hay khong.
     * CAll api NL check transaction ngan luong
     * @return bool
     */
    public function checkTransaction()
    {
        return $this->status === self::STATUS_COMPLETE;
    }

    /**
     * @var object $redis
     */
    private $_redis;

    /**
     * getter
     * @return object
     */
    public function getRedis()
    {
        if (!$this->_redis) {
            $this->_redis = $this->createRedis();
        }
        return $this->_redis;
    }

    /**
     * getter
     * @param $redis
     */
    public function setRedis($redis)
    {
        $this->_redis = $redis;
    }

    /**
     * create redis object via Yii::createObject and 'redisConfig' given
     * @return object
     */
    public function createRedis()
    {
        $config = $this->redisConfig;
        if (!isset($config['class'])) {
            //$config['class'] = '';
        }
        if (!isset($config['name'])) {
            $config['name'] = self::className();
        }
        //return Yii::createObject($config);
        return new \common\components\RedisQueue($config['name']);
    }

    /**
     * @param null $receiveType
     * @return array|mixed
     */
    public function getOtpSendTo($receiveType = null)
    {
        $sendTo = [
            self::VERIFY_RECEIVE_TYPE_PHONE => $this->currentWalletClient->customer_phone,
            self::VERIFY_RECEIVE_TYPE_EMAIL => $this->currentWalletClient->email
        ];
        return $receiveType === null ? $sendTo : $sendTo[$receiveType];
    }

    /**
     * @param null $status
     * @return array|mixed
     */
    public static function getStatusLabels($status = null)
    {
        $statusLists = [
            self::STATUS_QUEUE => Yii::t('wallet','Transaction queue'),
            self::STATUS_PROCESSING => Yii::t('wallet', 'Transaction processing'),
            self::STATUS_COMPLETE => Yii::t('wallet','Transaction complete'),
            self::STATUS_CANCEL => Yii::t('wallet', 'Transaction cancel'),
            self::STATUS_FAIL => Yii::t('wallet','Transaction fail'),
        ];
        return $status === null ? $statusLists : ArrayHelper::getValue($statusLists, $status,Yii::t('wallet', 'Transaction unknown'));
    }

    /**
     * @param null $type
     * @return array|mixed
     */
    public static function getVerifyReceiveTypeLabels($type = null)
    {
        $types = [
            self::VERIFY_RECEIVE_TYPE_PHONE => Yii::t('wallet','Phone'),
            self::VERIFY_RECEIVE_TYPE_EMAIL => Yii::t('wallet','Email')
        ];
        return $type === null ? $types : ArrayHelper::getValue($types, $type, Yii::t('wallet','Unknown'));
    }

    /**
     * @param $target
     * @return mixed
     */
    public function extract($target)
    {
        $all = array_combine(['phone','mail'],self::getOtpSendTo());
        return ArrayHelper::getValue($all,$target->id);
    }
}