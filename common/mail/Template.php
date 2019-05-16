<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-07-26
 * Time: 13:10
 */

namespace common\mail;

use common\components\StoreManager;
use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\helpers\CustomerHelper;
use common\models\Customer;
use common\models\Order;
use common\models\model\OrderItem;
use common\models\model\OrderPayment;
use common\models\model\OrderPaymentRequest;
use wallet\modules\v1\models\WalletTransaction;
use wallet\modules\v1\models\WalletClient;

/**
 * Class Template
 * ```php
 * $template = new Template()
 * $template->type = Template::TYPE_NOTIFICATION;
 * $template->responseCode = '404';
 * $template->responseMessage = 'Not Found';
 * $template->text_content = 'error code: [RESPONSE_CODE] , message : [RESPONSE_MESSAGE]';
 *
 * echo $template->getTextContentReplace(); // error code: 404 , message : Not Found
 *
 * //Toto ActiveRecord
 * $template = Template::findOne(['type' => Template::TYPE_PAY_ORDER]);
 * $template->instanceModel = Order::findOne(1);
 * ```
 * @package common\models\model
 *
 * @property array $needles
 * @property array $haystacks
 */
class Template extends \common\models\db\NotifyTemplate
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const RECEIVE_ALL = 2;
    const RECEIVE_PHONE = 0;
    const RECEIVE_EMAIL = 1;

    /**
     * TYPE const
     * @see Template::getTypeLabels()
     */
    const TYPE_CONTACT_ADMIN = 'CONTACT_ADMIN';
    const TYPE_CONTACT_SALE = 'CONTACT_SALE';

    const TYPE_CUSTOMER_REGISTER_JWT = 'CUSTOMER_REGISTER_JWT';
    const TYPE_CUSTOMER_REGISTER_OTP = 'CUSTOMER_REGISTER_OPT';
    const TYPE_CUSTOMER_RESET_PASSWORD = 'CUSTOMER_RESET_PASSWORD';
    const TYPE_CUSTOMER_REGISTER_SUCCESS = 'CUSTOMER_REGISTER_SUCCESS';
    const TYPE_CUSTOMER_INVITE_REGISTER = 'CUSTOMER_INVITE_REGISTER';

    const TYPE_NOTIFICATION = 'NOTIFICATION';
    const TYPE_PAY_ORDER = 'PAY_ORDER';
    const TYPE_THANKS_FOR_PURCHASE = 'THANKS_FOR_PURCHASE';
    const TYPE_ORDER_REQUEST_APPROVED = 'ORDER_REQUEST_APPROVED';
    const TYPE_ORDER_TRACKING_STEP_1 = 'ORDER_TRACKING_SELLER_SHIPPED';
    const TYPE_ORDER_TRACKING_STEP_2 = 'ORDER_TRACKING_US_WAREHOUSE_STOCK_IN';
    const TYPE_ORDER_TRACKING_STEP_3 = 'ORDER_TRACKING_US_WAREHOUSE_STOCK_OUT';
    const TYPE_ORDER_TRACKING_STEP_4 = 'ORDER_TRACKING_LOCAL_WAREHOUSE_STOCK_IN';
    const TYPE_ORDER_TRACKING_STEP_5 = 'ORDER_TRACKING_LOCAL_WAREHOUSE_STOCK_OUT';
    const TYPE_ADDITION_FEE_REQUESTED = 'ADDITION_FEE_REQUESTED';
    const TYPE_ADDITION_FEE_COMPLETED = 'ADDITION_FEE_COMPLETED';
    const TYPE_OPS_PURCHASE_COMPLETE = 'OPS_PURCHASE_COMPLETE';
    //for WalletClient | WalletMerchant
    const TYPE_WALLET_NOTIFY = 'WALLET_ALERT';
    //for WalletTransaction
    const TYPE_TRANSACTION_NOTIFY = 'TRANSACTION_NOTIFY';
    const TYPE_TRANSACTION_VERIFY_CODE = 'TRANSACTION_VERIFY_CODE';
    const TYPE_TRANSACTION_TYPE_PAY_ORDER = 'TRANSACTION_TYPE_PAY_ORDER';
    const TYPE_TRANSACTION_TYPE_PAY_ORDER_SUCCESS = 'TRANSACTION_TYPE_PAY_ORDER_SUCCESS';
    const TYPE_TRANSACTION_TYPE_PAY_ORDER_FAILED = 'TRANSACTION_TYPE_PAY_ORDER_FAILED';
    const TYPE_TRANSACTION_TYPE_TOP_UP = 'TRANSACTION_TYPE_TOP_UP';
    const TYPE_TRANSACTION_TYPE_TOP_UP_SUCCESS = 'TRANSACTION_TYPE_TOP_UP_SUCCESS';
    const TYPE_TRANSACTION_TYPE_TOP_UP_FAILED = 'TRANSACTION_TYPE_TOP_UP_FAILED';
    const TYPE_TRANSACTION_TYPE_REFUND = 'TRANSACTION_TYPE_REFUND';
    const TYPE_TRANSACTION_TYPE_REFUND_SUCCESS = 'TRANSACTION_TYPE_REFUND_SUCCESS';
    const TYPE_TRANSACTION_TYPE_REFUND_FAILED = 'TRANSACTION_TYPE_REFUND_FAILED';
    const TYPE_TRANSACTION_TYPE_WITHDRAW = 'TRANSACTION_TYPE_WITHDRAW';
    const TYPE_TRANSACTION_TYPE_WITHDRAW_SUCCESS = 'TRANSACTION_TYPE_WITHDRAW_SUCCESS';
    const TYPE_TRANSACTION_TYPE_WITHDRAW_FAILED = 'TRANSACTION_TYPE_WITHDRAW_FAILED';

    /**
     * Needle const
     * @see Template::getNeedlesLabels()
     */
    const NEEDLE_COUNTRY = '[COUNTRY]';
    const NEEDLE_VERSION = '[VERSION]';

    const NEEDLE_WEBSITE_LOGO = 'WEBSITE_LOGO';
    const NEEDLE_WEBSITE_DOMAIN = '[WEBSITE_DOMAIN]';
    const NEEDLE_WEBSITE_NAME = '[WEBSITE_NAME]';

    const NEEDLE_WEBSITE_BASE_LINK = '[WEBSITE_BASE_LINK]';
    const NEEDLE_HOT_LINE = '[HOT_LINE]';
    const NEEDLE_EMAIL_SUPPORTER = '[EMAIL_SUPPORTER]';

    const NEEDLE_ADDITION_TEXT = '[ADDITION_TEXT]';
    const NEEDLE_PART_CONTENT = '[PART_CONTENT]';

    const NEEDLE_CUSTOMER_NAME = '[CUSTOMER_NAME]';
    const NEEDLE_CUSTOMER_PHONE = '[CUSTOMER_PHONE]';
    const NEEDLE_CUSTOMER_MAIL = '[CUSTOMER_MAIL]';
    const NEEDLE_CUSTOMER_ADDRESS = '[CUSTOMER_ADDRESS]';
    const NEEDLE_CUSTOMER_PROFILE_URL = '[CUSTOMER_PROFILE_URL]';
    const NEEDLE_CUSTOMER_REGISTER_URL = '[CUSTOMER_REGISTER_URL]';
    const NEEDLE_CUSTOMER_ACTIVE_ACCOUNT_URL = '[ACTIVE_ACCOUNT_URL]';
    const NEEDLE_CUSTOMER_RESET_PASSWORD_URL = '[RESET_PASSWORD_URL]';

    const NEEDLE_JSON_WEB_TOKEN = '[JWT_TOKEN]';

    const NEEDLE_WALLET_CLIENT_MY_WALLET_URL = '[WALLET_CLIENT_MY_WALLET_URL]';
    const NEEDLE_TRANSACTION_CODE = '[TRANSACTION_CODE]';
    const NEEDLE_VERIFY_CODE = '[VERIFY_CODE]';
    const NEEDLE_EXPIRED_AT = '[EXPIRED_AT]';
    const NEEDLE_ORDER_NUMBER = '[ORDER_NUMBER]';
    const NEEDLE_TOTAL_PAID_AMOUNT = '[TOTAL_PAID_AMOUNT]';
    const NEEDLE_TOTAL_AMOUNT = '[TOTAL_AMOUNT]';
    const NEEDLE_CURRENCY_CODE = '[CURRENCY_CODE]';
    const NEEDLE_CURRENCY_SYMBOL = '[CURRENCY_SYMBOL]';
    const NEEDLE_TOTAL_BALANCE = '[TOTAL_BALANCE]';
    const NEEDLE_PAYMENT_METHOD = '[PAYMENT_METHOD]';
    const NEEDLE_PAYMENT_PROVIDER = '[PAYMENT_PROVIDER]';
    const NEEDLE_BANK_CODE = '[BANK_CODE]';

    const NEEDLE_BIN_CODE = '[BIN_CODE]';
    const NEEDLE_ORDER_ITEMS = '[ORDER_ITEMS]';
    const NEEDLE_BILLING_URL = '[BILLING_URL]';
    const NEEDLE_ORDER_TRACKING_URL =  '[ORDER_TRACKING_URL]';
    const NEEDLE_DELIVERY_TIME =  '[DELIVERY_TIME]';

    const NEEDLE_PAYMENT_BUTTON = '[PAYMENT_BUTTON]';
    const NEEDLE_RESPONSE_CODE = '[RESPONSE_CODE]';
    const NEEDLE_RESPONSE_MESSAGE = '[RESPONSE_MESSAGE]';

    const NEEDLE_STATUS = '[STATUS]';
    const NEEDLE_CREATE_AT = '[CREATE_AT]';
    const NEEDLE_NOTE = '[NOTE]';

    /**
     * Default Needless
     * (ở TYPE cũng có)
     * @var array
     */
    public $defaultNeedles = [
        self::NEEDLE_WEBSITE_DOMAIN,
        self::NEEDLE_WEBSITE_NAME,
        self::NEEDLE_WEBSITE_BASE_LINK,
        self::NEEDLE_HOT_LINE,
        self::NEEDLE_EMAIL_SUPPORTER
    ];

    /**
     * @var Store
     */
    public $website;

    /**
     * @var \yii\i18n\Formatter;
     */
    public $formatter;

    public function init()
    {
        parent::init();
//        if ($this->store) {
//            $this->website = Yii::$app->store->tryStore($this->store);
//        }
    }

    /**
     * Active Record
     * @var yii\db\ActiveRecord
     */
    private $_model;

    /**
     * setter
     * @param  yii\db\ActiveRecord $model
     * @return $this
     */
    public function setActiveModel($model)
    {
        $this->_model = $model;
        return $this;
    }

    /**
     * getter
     * @return Yii\db\ActiveRecord
     */
    public function getActiveModel()
    {
        return $this->_model;
    }

    /**
     * A text addition in needle [([ADDITION_TEXT])]
     * @var string
     * @see Template::NEEDLE_ADDITION_TEXT
     */
    private $_additionText;

    /**
     * setter
     * @param string $text
     * @return $this
     */
    public function setAdditionText($text = 'Hello World')
    {
        $this->_additionText = $text;
        $this->defaultNeedles = array_merge($this->defaultNeedles, [
            self::NEEDLE_ADDITION_TEXT
        ]);
        return $this;
    }

    /**
     * getter
     * @return string
     */
    public function getAdditionText()
    {
        return $this->_additionText;
    }

    /**
     * A part content is included to the content via needle [([PART_CONTENT])]
     * (Một phần nội dung được thêm vào nội dung thông qua needle [([PART_CONTENT])])
     * @var string| MailRendererContextInterface | \Closure
     * @see Template::NEEDLE_PART_CONTENT
     * @see MailRendererContextInterface
     * @see \common\mail\BaseMailRender
     */
    private $_partContent;

    /**
     * setter
     * @param string|MailRendererContextInterface|callable $part
     * @return $this
     */
    public function setPartContent($part)
    {
        if ($part instanceof \Closure) {
            $this->_partContent = call_user_func($part, $this);
        } else if ($part instanceof MailRendererContextInterface) {
            $part->extractTemplate($this);
            $this->_partContent = $part->render();
        } else {
            $this->_partContent = Yii::$app->controller->render($part, ['template' => $this]);
        }
        $this->defaultNeedles = array_merge($this->defaultNeedles, [
            self::NEEDLE_PART_CONTENT
        ]);
        return $this;
    }

    /**
     * getter
     * @return string
     */
    protected function getPartContent()
    {
        return $this->_partContent;
    }

    /**
     * @var array
     */
    private $_responseInfo = [];

    /**
     * @param $code
     * @param $message
     * @return $this
     */
    public function setResponseInfo($code, $message)
    {
        $this->_responseInfo[$code] = $message;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponseInfo()
    {
        return $this->_responseInfo;
    }

    /**
     * @param null $code
     */
    public function normalResponseInfo($code = null)
    {

    }

    /**
     * replace all needle in haystack found in $text
     * @param $text
     * @return mixed
     */

    private $_needles;

    /**
     * getter
     * Get all needle available on TYPE
     * @return array
     */
    public function getNeedles()
    {
        if (!$this->_needles) {
            $this->addNeedles($this->getNeedlesOptions());
        }
        return $this->_needles;
    }

    /**
     * Add a single needle
     * @param $needles
     * @return $this
     */
    public function addNeedles($needles)
    {
        $this->_needles = ArrayHelper::merge($this->defaultNeedles, $needles);
        return $this;
    }

    /**
     * @var array
     */
    private $_haystacks;

    /**
     * getter
     * Get all Haystack of Type
     * haystack is replace all needle
     * @return array
     * @see Template::getNeedles()
     * @see Template::getReplace()
     */
    public function getHaystacks()
    {
        if (!$this->_haystacks) {
            $this->addHaystacks([]);
        }
        return $this->_haystacks;
    }

    /**
     * @param array $haystacks
     * @return $this
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function addHaystacks($haystacks = [])
    {
        $replacement = [];
        $needles = $this->getNeedles();
        foreach ($needles as $needle) {
            $replacement[$needle] = $this->getReplace($needle);
        }
        $replacement = ArrayHelper::merge($replacement, $haystacks);
        $this->_haystacks = $replacement;

        return $this;
    }

    /**
     * Replace a text
     * eg
     * php ```
     *      $text = 'This is [NEEDLE_1] , replace by [NEEDLE_2]';
     * ````
     * haystack
     * php ```
     *      [
     *            '[NEEDLE_1] => 'String 1',
     *            '[NEEDLE_1] => 'String 2',
     *       ]
     *  return 'This is String 1 , replace by String 2'
     * @param $text
     * @return mixed
     */
    public function replace($text)
    {
        return str_replace(array_keys($this->getHaystacks()), array_values($this->getHaystacks()), $text);
    }

    /**
     * @return array
     */
    public function getTypeLabels()
    {
        return [
            self::TYPE_CONTACT_ADMIN => 'contact to admin',
            self::TYPE_CONTACT_SALE => 'contact to sale',
            self::TYPE_CUSTOMER_REGISTER_JWT => 'Customer register new account',
            self::TYPE_CUSTOMER_REGISTER_OTP => 'Customer register new account',
            self::TYPE_CUSTOMER_RESET_PASSWORD => 'customer request to reset password',
            self::TYPE_CUSTOMER_REGISTER_SUCCESS => 'customer register complete',
            self::TYPE_CUSTOMER_INVITE_REGISTER => 'invite customer register account',
            self::TYPE_PAY_ORDER => 'Pay order',
            self::TYPE_THANKS_FOR_PURCHASE => 'Thanks for purchase',
            self::TYPE_ORDER_REQUEST_APPROVED => 'Order request approved ',
            self::TYPE_ORDER_TRACKING_STEP_1 => 'Order tracking step 1',
            self::TYPE_ORDER_TRACKING_STEP_2 => 'Order tracking step 2',
            self::TYPE_ORDER_TRACKING_STEP_3 => 'Order tracking step 3',
            self::TYPE_ORDER_TRACKING_STEP_4 => 'Order tracking step 4',
            self::TYPE_ORDER_TRACKING_STEP_5 => 'Order tracking step 5',
            self::TYPE_ADDITION_FEE_REQUESTED => 'Addition fee requested',
            self::TYPE_ADDITION_FEE_COMPLETED => 'Addition fee completed',
            self::TYPE_NOTIFICATION => 'Notification',
            self::TYPE_WALLET_NOTIFY => 'Wallet notify',
            self::TYPE_TRANSACTION_NOTIFY => 'Transaction notify',
            self::TYPE_TRANSACTION_VERIFY_CODE => 'Transaction verify code',
            self::TYPE_TRANSACTION_TYPE_PAY_ORDER => 'Transaction pay order',
            self::TYPE_TRANSACTION_TYPE_PAY_ORDER_SUCCESS => 'Transaction pay order success',
            self::TYPE_TRANSACTION_TYPE_PAY_ORDER_FAILED => 'Transaction pay order failed',
            self::TYPE_TRANSACTION_TYPE_TOP_UP => 'Transaction top up',
            self::TYPE_TRANSACTION_TYPE_TOP_UP_SUCCESS => 'Transaction top up success',
            self::TYPE_TRANSACTION_TYPE_TOP_UP_FAILED => 'Transaction top up failed',
            self::TYPE_TRANSACTION_TYPE_REFUND => 'Transaction refund',
            self::TYPE_TRANSACTION_TYPE_REFUND_SUCCESS => 'Transaction refund success',
            self::TYPE_TRANSACTION_TYPE_REFUND_FAILED => 'Transaction refund failed',
            self::TYPE_TRANSACTION_TYPE_WITHDRAW => 'Transaction withdraw',
            self::TYPE_TRANSACTION_TYPE_WITHDRAW_SUCCESS => 'Transaction withdraw success',
            self::TYPE_TRANSACTION_TYPE_WITHDRAW_FAILED => 'Transaction withdraw failed',
        ];
    }

    /**
     * @return array
     */
    public function getNeedlesLabels()
    {
        return [
            self::NEEDLE_COUNTRY => 'country (Viet Nam| ..)',
            self::NEEDLE_VERSION => 'version default is WESHOP (exeption US-GIFT)',
            self::NEEDLE_WEBSITE_DOMAIN => 'Wesite domain (weshop.com.vn|weshop.co.id| ... ect.)',
            self::NEEDLE_WEBSITE_NAME => 'Website name (Weshop Viet Name | Weshop Indo | ... etc.)',
            self::NEEDLE_WEBSITE_BASE_LINK => 'Website base url (https:://weshop.com.vn|https:://weshop.co.id| ... ect.)',
            self::NEEDLE_HOT_LINE => 'Telephone hot line',
            self::NEEDLE_EMAIL_SUPPORTER => 'Email support',
            self::NEEDLE_CUSTOMER_NAME => 'Customer name',
            self::NEEDLE_CUSTOMER_PHONE => 'Customer phone',
            self::NEEDLE_CUSTOMER_ADDRESS => 'Customer address (18 Tam Trinh, Hoang Mai, Ha Noi, ....)',
            self::NEEDLE_CUSTOMER_PROFILE_URL => 'Customer profile url',
            self::NEEDLE_CUSTOMER_REGISTER_URL => 'Customer register url',
            self::NEEDLE_CUSTOMER_ACTIVE_ACCOUNT_URL => 'Customer active account url',
            self::NEEDLE_CUSTOMER_RESET_PASSWORD_URL => 'Customer reset password url',
            self::NEEDLE_CUSTOMER_MAIL => 'Customer mail',
            self::NEEDLE_ADDITION_TEXT => 'Addition text',
            self::NEEDLE_PART_CONTENT => 'Part content',
            self::NEEDLE_JSON_WEB_TOKEN => 'json web token',
            self::NEEDLE_WALLET_CLIENT_MY_WALLET_URL => 'My wallet url',
            self::NEEDLE_TRANSACTION_CODE => 'Transaction code',
            self::NEEDLE_VERIFY_CODE => 'Verify code (otp code)',
            self::NEEDLE_EXPIRED_AT => 'Time expired (format:time)',
            self::NEEDLE_ORDER_NUMBER => 'Order number (binCode|AdditionBinCode|Wallet OrderNumber)',
            self::NEEDLE_TOTAL_PAID_AMOUNT => 'Total paid amount',
            self::NEEDLE_TOTAL_AMOUNT => 'Total amount',
            self::NEEDLE_CURRENCY_CODE => 'Currency code (VND|IDR|USD|... etc.)',
            self::NEEDLE_CURRENCY_SYMBOL => 'Currency Symbol ($,đ,..etc,)',
            self::NEEDLE_TOTAL_BALANCE => 'Wallet total balance',
            self::NEEDLE_PAYMENT_METHOD => 'Payment Method (VISA,MASTER,.. etc.)',
            self::NEEDLE_PAYMENT_PROVIDER => 'Payment Provider',
            self::NEEDLE_BANK_CODE => 'Bank code (TCB,VCB,...etc.)',
            self::NEEDLE_BIN_CODE => 'Bin code (binCode,AdditionBinCode)',
            self::NEEDLE_ORDER_ITEMS => 'OrderItem list eg "[12345,67890]"',
            self::NEEDLE_BILLING_URL => 'Billing url (/[order|addfee]-{binCode/binAddfee}/bill.html)',
            self::NEEDLE_DELIVERY_TIME => 'delivery time (3-5 | 5-7)',
            self::NEEDLE_PAYMENT_BUTTON => 'PAYMENT button',
            self::NEEDLE_RESPONSE_CODE => 'Response code ("0000","10000",... etc.)',
            self::NEEDLE_RESPONSE_MESSAGE => 'Response Message',
            self::NEEDLE_STATUS => 'Status',
            self::NEEDLE_CREATE_AT => 'Create at (format:Y-m-d H:s:i)',
            self::NEEDLE_NOTE => 'Note',
        ];
    }

    /**
     * @return array
     */
    public function getNeedlesOptions()
    {
        switch ($this->type) {
            case self::TYPE_CONTACT_ADMIN:
            case self::TYPE_CONTACT_SALE:
                return [];
                break;
            case self::TYPE_CUSTOMER_REGISTER_JWT:
                return [
                    self::NEEDLE_JSON_WEB_TOKEN,
                    self::NEEDLE_CUSTOMER_ACTIVE_ACCOUNT_URL,
                    self::NEEDLE_CUSTOMER_PROFILE_URL,
                    self::NEEDLE_EXPIRED_AT,
                    self::NEEDLE_CREATE_AT
                ];
                break;
            case self::TYPE_CUSTOMER_REGISTER_OTP:
                return [
                    self::NEEDLE_CUSTOMER_NAME,
                    self::NEEDLE_CUSTOMER_MAIL,
                    self::NEEDLE_VERIFY_CODE,
                    self::NEEDLE_EXPIRED_AT,
                ];
                break;
            case self::TYPE_CUSTOMER_RESET_PASSWORD:
                return [
                    self::NEEDLE_VERIFY_CODE,
                    self::NEEDLE_EXPIRED_AT,
                ];
                break;
            case self::TYPE_CUSTOMER_REGISTER_SUCCESS:
                return [
                    self::NEEDLE_CUSTOMER_PROFILE_URL,
                    self::NEEDLE_CREATE_AT
                ];
            case self::TYPE_PAY_ORDER:
                return [
                    self::NEEDLE_CUSTOMER_NAME,
                    self::NEEDLE_CUSTOMER_MAIL,
                    self::NEEDLE_CUSTOMER_PHONE,
                    self::NEEDLE_CUSTOMER_ADDRESS,
                    self::NEEDLE_NOTE,
                    self::NEEDLE_STATUS,
                    self::NEEDLE_CREATE_AT,
                    self::NEEDLE_ORDER_NUMBER,
                    self::NEEDLE_ORDER_ITEMS,
                    self::NEEDLE_TOTAL_AMOUNT,
                    self::NEEDLE_TOTAL_PAID_AMOUNT,
                    self::NEEDLE_CURRENCY_CODE,
                    self::NEEDLE_BILLING_URL,
                    self::NEEDLE_ORDER_TRACKING_URL,
                    self::NEEDLE_PAYMENT_METHOD,
                    self::NEEDLE_PAYMENT_PROVIDER
                ];
                break;
            case self::TYPE_THANKS_FOR_PURCHASE:
                return [
                    self::NEEDLE_CUSTOMER_NAME,
                    self::NEEDLE_CUSTOMER_MAIL,
                    self::NEEDLE_CUSTOMER_PHONE,
                    self::NEEDLE_CUSTOMER_ADDRESS,
                    self::NEEDLE_NOTE,
                    self::NEEDLE_STATUS,
                    self::NEEDLE_CREATE_AT,
                    self::NEEDLE_TRANSACTION_CODE,
                    self::NEEDLE_ORDER_NUMBER,
                    self::NEEDLE_ORDER_ITEMS,
                    self::NEEDLE_TOTAL_AMOUNT,
                    self::NEEDLE_TOTAL_PAID_AMOUNT,
                    self::NEEDLE_CURRENCY_CODE,
                    self::NEEDLE_BILLING_URL,
                    self::NEEDLE_PAYMENT_METHOD,
                    self::NEEDLE_PAYMENT_PROVIDER
                ];
                break;
            case self::TYPE_ORDER_TRACKING_STEP_1:
            case self::TYPE_ORDER_TRACKING_STEP_2:
            case self::TYPE_ORDER_TRACKING_STEP_3:
            case self::TYPE_ORDER_TRACKING_STEP_4:
            case self::TYPE_ORDER_TRACKING_STEP_5:
            case self::TYPE_ADDITION_FEE_REQUESTED:
            case self::TYPE_ADDITION_FEE_COMPLETED:
                return [];
                break;
            case self::TYPE_NOTIFICATION:
                return [
                    self::NEEDLE_RESPONSE_CODE,
                    self::NEEDLE_RESPONSE_MESSAGE
                ];
                break;
            case self::TYPE_TRANSACTION_VERIFY_CODE:
                return [
                    self::NEEDLE_CUSTOMER_NAME,
                    self::NEEDLE_VERIFY_CODE,
                    self::NEEDLE_EXPIRED_AT
                ];
                break;
            case self::TYPE_TRANSACTION_TYPE_PAY_ORDER:
                return [
                    self::NEEDLE_CUSTOMER_NAME,
                    self::NEEDLE_TRANSACTION_CODE,
                    self::NEEDLE_ORDER_NUMBER,
                    self::NEEDLE_TOTAL_AMOUNT,
                    self::NEEDLE_CURRENCY_CODE
                ];
                break;
            case self::TYPE_TRANSACTION_TYPE_PAY_ORDER_SUCCESS:
                return [
                    self::NEEDLE_CUSTOMER_NAME,
                    self::NEEDLE_ORDER_NUMBER,
                    self::NEEDLE_TOTAL_AMOUNT,
                    self::NEEDLE_TOTAL_BALANCE,
                    self::NEEDLE_CURRENCY_CODE,
                    self::NEEDLE_TRANSACTION_CODE,
                    self::NEEDLE_CREATE_AT,
                    self::NEEDLE_STATUS,
                ];
                break;
            case self::TYPE_TRANSACTION_TYPE_WITHDRAW:
                return [
                    self::NEEDLE_CUSTOMER_NAME,
                    self::NEEDLE_TRANSACTION_CODE,
                    self::NEEDLE_TOTAL_AMOUNT,
                    self::NEEDLE_PAYMENT_METHOD,
                    self::NEEDLE_PAYMENT_PROVIDER,
                    self::NEEDLE_TOTAL_AMOUNT,
                    self::NEEDLE_TOTAL_BALANCE,
                    self::NEEDLE_CURRENCY_CODE,
                    self::NEEDLE_STATUS,
                    self::NEEDLE_CREATE_AT
                ];
                break;
            case self::TYPE_TRANSACTION_TYPE_TOP_UP:
                return [
                    self::NEEDLE_CUSTOMER_NAME,
                    self::NEEDLE_TRANSACTION_CODE,
                    self::NEEDLE_TOTAL_AMOUNT,
                    self::NEEDLE_TOTAL_BALANCE,
                    self::NEEDLE_CURRENCY_CODE,
                    self::NEEDLE_PAYMENT_METHOD,
                    self::NEEDLE_PAYMENT_PROVIDER,
                    self::NEEDLE_STATUS,
                    self::NEEDLE_CREATE_AT
                ];
                break;
            case self::TYPE_TRANSACTION_TYPE_REFUND:
                return [];
                break;
            case self::TYPE_TRANSACTION_TYPE_WITHDRAW_SUCCESS:
            case self::TYPE_TRANSACTION_TYPE_WITHDRAW_FAILED:
                return [
                    self::NEEDLE_CUSTOMER_NAME,
                    self::NEEDLE_CREATE_AT,
                    self::NEEDLE_STATUS,
                    self::NEEDLE_TRANSACTION_CODE,
                    self::NEEDLE_TOTAL_AMOUNT,
                    self::NEEDLE_TOTAL_BALANCE,
                    self::NEEDLE_CURRENCY_CODE,
                    self::NEEDLE_ORDER_NUMBER,
                ];
                break;
            case self::TYPE_TRANSACTION_TYPE_TOP_UP_SUCCESS:
            case self::TYPE_TRANSACTION_TYPE_TOP_UP_FAILED:
                return [
                    self::NEEDLE_CUSTOMER_NAME,
                    self::NEEDLE_CREATE_AT,
                    self::NEEDLE_STATUS,
                    self::NEEDLE_TRANSACTION_CODE,
                    self::NEEDLE_PAYMENT_METHOD,
                    self::NEEDLE_PAYMENT_PROVIDER,
                    self::NEEDLE_TOTAL_AMOUNT,
                    self::NEEDLE_TOTAL_BALANCE,
                    self::NEEDLE_CURRENCY_CODE,
                ];
                break;
            case self::TYPE_TRANSACTION_TYPE_REFUND_SUCCESS:
                return [
                    self::NEEDLE_CUSTOMER_NAME,
                    self::NEEDLE_CREATE_AT,
                    self::NEEDLE_STATUS,
                    self::NEEDLE_TRANSACTION_CODE,
                    self::NEEDLE_TOTAL_AMOUNT,
                    self::NEEDLE_TOTAL_BALANCE,
                    self::NEEDLE_CURRENCY_CODE,
                ];
                break;
            default:
                return [];
                break;
        }
    }

    /**
     * @param $needle
     * @return array|mixed|string
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function getReplace($needle)
    {
        $storeManage = new StoreManager();
        $storeManage->setStore($this->store);
        $model = $this->getActiveModel();
        switch ($needle) {
            case self::NEEDLE_COUNTRY;
                if ($storeManage->isVN()){
                    return 'Việt Nam';
                }elseif ($storeManage->isID()){
                    return 'Indonesia';
                }
                break;
            case self::NEEDLE_VERSION:
                return 'Weshop';
                break;
            case self::NEEDLE_WEBSITE_LOGO:
                $baseUrl = $storeManage->getBaseUrl();
                    return Html::a(
                        $baseUrl,
                        Html::img($baseUrl.'mail/image/weshop_logo.png',[
                            'alt' => $this->getReplace(self::NEEDLE_WEBSITE_NAME),
                            'width' => 116,
                            'height' => 44
                        ]),
                        ['target' => '_blank']
                    );
                break;
            case self::NEEDLE_WEBSITE_DOMAIN:
                return $storeManage->getDomainByStoreId($this->store);
                break;
            case self::NEEDLE_WEBSITE_NAME:
                return $storeManage->getName();
                break;
            case self::NEEDLE_WEBSITE_BASE_LINK:
                return Html::a($this->getReplace(self::NEEDLE_WEBSITE_NAME), $storeManage->getBaseUrl());
                break;
            case self::NEEDLE_HOT_LINE:
                // Or Todo get via Store;
                if ($storeManage->isVN()) {
                    return '1900-6755 (Hà Nội), 1900 636 027 (Hồ Chí Minh)';
                } elseif ($storeManage->isID()) {
                    return '0855-7467-9968';
                }
                break;
            case self::NEEDLE_EMAIL_SUPPORTER:
                // Or Todo get via Store;
                if ($storeManage->isVN()) {
                    return 'support@weshop.com.vn';
                } elseif ($storeManage->isID()) {
                    return 'support-id@weshop.asia';
                }
                break;
            case self::NEEDLE_CURRENCY_CODE:
            case self::NEEDLE_CURRENCY_SYMBOL:
                return $storeManage->getCurrencyName();
                break;
            case self::NEEDLE_CUSTOMER_PHONE:
                $user = Yii::$app->user->identity;
                if ($user !== null) {
                    if ($user instanceof WalletClient) {
                        return $user->customer_phone;
                    } elseif ($user instanceof Customer) {
                        return $user->phone;
                    }
                } elseif ($model !== null) {
                    if ($model instanceof Order) {
                        return $model->buyerPhone;
                    } elseif ($model instanceof Customer) {
                        return $model->phone;
                    }
                }

                break;
            case self::NEEDLE_CUSTOMER_NAME:

                if (($user = Yii::$app->user->identity) !== null && ($this->isSubClassCustomer($user) && !$this->isSystemAccount($user))) {
                    if ($user instanceof WalletClient) {
                        return $user->customer_name;
                    } elseif ($this->isSubClassCustomer($user)) {
                        return $user->username;
                    }
                    
                } elseif ($model !== null) {
                    if ($model instanceof WalletTransaction) {
                        return $model->getCurrentWalletClient()->customer_name;
                    } elseif ($model instanceof WalletClient) {
                        return $model->customer_name;
                    } elseif ($model instanceof Customer || $this->isSubClassCustomer($model)) {
                        return $model->username;
                    } elseif ($model instanceof Order) {
                        if($this->type === self::TYPE_THANKS_FOR_PURCHASE || $this->type === self::TYPE_PAY_ORDER){
                            return $model->buyerName;
                        }else{
                            if($model->customer){
                                return $model->customer->username;
                            }else{
                                return $this->getReplace(self::NEEDLE_CUSTOMER_MAIL);
                            }
                        }
                    }
                }

                break;
            case self::NEEDLE_CUSTOMER_MAIL:
                $user = Yii::$app->user->identity;
                if ($user !== null && ($user instanceof WalletClient || ($this->isSubClassCustomer($user) && !$this->isSystemAccount($user)))) {
                    return $user->email;
                } elseif ($model !== null) {
                    if ($model instanceof Order) {
                        return $model->buyerEmail;
                    } elseif ($this->isSubClassCustomer($model)) {
                        return $model->email;
                    }
                }
                break;
            case self::NEEDLE_CUSTOMER_ADDRESS:
                if (($user = Yii::$app->user->identity) !== null && ($this->isSubClassCustomer($user) && !$this->isSystemAccount($user))) {
                    if ($user instanceof WalletClient || ($this->isSubClassCustomer($user) && !$this->isSystemAccount($user))) {
                        return $user->district;
                    }
                } elseif ($model !== null) {
                    if ($model instanceof WalletTransaction) {
                        return $model->getCurrentWalletClient()->district;
                    } elseif ($model instanceof WalletClient) {
                        return $model->email;
                    } elseif ($model instanceof Customer) {
                        return $model->district;
                    } elseif ($model instanceof Order) {
                        $address = [];
                        if($this->type === self::TYPE_PAY_ORDER){
                            if($model->receiverDistrictName){
                                $address[] = $model->receiverDistrictName;
                            }
                            if($model->receiverCityName){
                                $address[] = $model->receiverCityName;
                            }
                            if(count($address) === 0 && $model->receiverAddress){
                                $address[] = $model->receiverAddress;
                            }
                        }elseif ($this->type === self::TYPE_THANKS_FOR_PURCHASE){
                            if($model->receiverAddress){
                                $address[] = $model->receiverAddress;
                            }
                            if($model->receiverDistrictName){
                                $address[] = $model->receiverDistrictName;
                            }
                            if($model->receiverCityName){
                                $address[] = $model->receiverCityName;
                            }
                        }
                        if(count($address) === 0){
                            $address[] = $this->getReplace(self::NEEDLE_CUSTOMER_MAIL);
                        }
                        return implode(' ',$address);
                    }
                }
                break;

            case self::NEEDLE_CUSTOMER_PROFILE_URL:
            case self::NEEDLE_CUSTOMER_REGISTER_URL:
               $user = Yii::$app->getUser()->getIdentity();
               if($user !== null){
                   return Url::toRoute('/account/user/active',['id' => $user->getId()]);
               }
                break;
            case self::NEEDLE_CUSTOMER_ACTIVE_ACCOUNT_URL:
            case self::NEEDLE_CUSTOMER_RESET_PASSWORD_URL:
                return Url::toRoute('/account/user/active',['token' => $this->getReplace(self::NEEDLE_JSON_WEB_TOKEN)]);
                break;
            case self::NEEDLE_ADDITION_TEXT:
                return $this->getAdditionText();
                break;
            case self::NEEDLE_PART_CONTENT:
                return $this->getPartContent();
                break;
            case self::NEEDLE_TRANSACTION_CODE;
                if ($model instanceof WalletTransaction) {
                    return $model->getWalletTransactionCode();
                }elseif ($model instanceof Order){
                    return $model->paymentToken;
                }
                break;
            case self::NEEDLE_VERIFY_CODE;
                if ($model instanceof WalletTransaction) {
                    return $model->getOtpCode();
                }elseif ($this->isSubClassCustomer($model) && method_exists($model,'getVerifyCode')){
                    if($this->type === self::TYPE_CUSTOMER_REGISTER_OTP || $this->type === self::TYPE_CUSTOMER_RESET_PASSWORD){
                        return $model->getVerifyCode();
                    }
                }
                break;
            case self::NEEDLE_EXPIRED_AT;
                if ($this->isSubClassCustomer($model)) {
                    if(($this->type === self::TYPE_CUSTOMER_RESET_PASSWORD || $this->type === self::TYPE_CUSTOMER_REGISTER_OTP) &&  method_exists($model,'getExpire')){
                        return $model->getExpire();
                    }else if($this->type === self::TYPE_CUSTOMER_REGISTER_JWT){
                        return Yii::t('frontend','about 2 days');
                    }
                } elseif ($model instanceof WalletTransaction) {
                    return Yii::$app->formatter->asTime($model->verify_expired_at);
                }
                break;
            case self::NEEDLE_BIN_CODE;
            case self::NEEDLE_ORDER_NUMBER;
                if ($model instanceof WalletTransaction) {
                    return $model->order_number;
                } elseif ($model instanceof Order) {
                    return $model->binCode;
                } elseif ($model instanceof OrderPayment) {
                    return $model->bin_code_addition;
                }
                break;
            case self::NEEDLE_TOTAL_AMOUNT;
                if ($model instanceof WalletTransaction) {
                    return (int)$model->totalAmount;
                } elseif ($model instanceof Order) {
                    return (int)$model->OrderTotalInLocalCurrencyFinal;
                } elseif ($model instanceof OrderPayment) {
                    return (int)$model->total_local_amount;
                }
                break;
            case self::NEEDLE_TOTAL_PAID_AMOUNT:
                if ($model instanceof WalletTransaction) {
                    return (int)$model->totalAmount;
                } elseif ($model instanceof Order) {
                    return (int)$model->TotalPaidAmount;
                } elseif ($model instanceof OrderPayment) {
                    return (int)$model->total_paid_amount;
                }
                break;
            case self::NEEDLE_TOTAL_BALANCE;
                if ($model instanceof WalletTransaction) {
                    return $model->getCurrentWalletClient()->getBalance();
                } elseif ($model instanceof WalletClient) {
                    return $model->getBalance();
                }
                break;
            case self::NEEDLE_PAYMENT_METHOD;
                if ($model instanceof WalletTransaction) {
                    return $model->payment_method;
                } elseif ($model instanceof Order) {
                    return $model->paymentMethodProvider->paymentMethod->Name;
                }
                break;
            case self::NEEDLE_PAYMENT_PROVIDER;
                if ($model instanceof WalletTransaction) {
                    return $model->payment_provider_name;
                } elseif ($model instanceof Order) {
                    return $model->paymentMethodProvider->paymentProvider->Name;
                }
                break;
            case self::NEEDLE_BANK_CODE;
                if ($model instanceof WalletTransaction) {
                    return $model->payment_bank_code;
                } elseif ($model instanceof Order) {
                    return $model->BankName;
                }
                break;
            case self::NEEDLE_ORDER_ITEMS:
                $orderItemId = [];
                if ($model instanceof Order) {
                    foreach ($model->orderItems as $item) {
                        $orderItemId[] = $item->id;
                    }
                } elseif ($model instanceof OrderItem) {
                    if (is_array($this->getActiveModel())) {
                        foreach ($model as $item) {
                            $orderItemId[] = $item->id;
                        }
                    } else {
                        $orderItemId[] = $model->id;
                    }
                } elseif ($model instanceof OrderPayment) {
                    foreach ($model->orderPaymentRequests as $request) {
                        $orderItemId[] = $request->order_item_id;
                    }
                } elseif ($model instanceof OrderPaymentRequest) {
                    if (is_array($this->getActiveModel())) {
                        foreach ($model as $request) {
                            $orderItemId[] = $request->order_item_id;
                        }
                    } else {
                        $orderItemId[] = $model->order_item_id;
                    }
                }
                if (count($orderItemId) > 0) {
                    return implode(',', $orderItemId);
                }
                break;
            case self::NEEDLE_BILLING_URL:
                if ($model instanceof Order) {
                    return $this->getReplace(self::NEEDLE_WEBSITE_DOMAIN).'/order-' . $model->binCode . '/bill.html';
                } elseif ($model instanceof OrderPayment) {
                    return $this->getReplace(self::NEEDLE_WEBSITE_DOMAIN).'/addfee-' . $model->bin_code_addition . '/bill.html';
                }
                break;
            case self::NEEDLE_ORDER_TRACKING_URL:
                if ($model instanceof Order) {
                    return $this->getReplace(self::NEEDLE_WEBSITE_DOMAIN).'/tracking/' . $model->binCode . '.html';
                } elseif ($model instanceof OrderPayment) {
                    return $this->getReplace(self::NEEDLE_WEBSITE_DOMAIN).'/tracking/' . $model->bin_code_addition . '.html';
                }
                break;
            case self::NEEDLE_DELIVERY_TIME:
                if($model instanceof OrderItem){
                    return '3-5';
                }
            case self::NEEDLE_PAYMENT_BUTTON:
                if($model instanceof Order || ($model instanceof OrderPayment && $model->payment_method !== 'COD')){
                    return Html::button(Yii::t('frontend','Payment Now'), $this->getReplace(self::NEEDLE_BILLING_URL),[
                        'style' => 'background: #2796b6;color: #fff;text-decoration: none;padding: 10px 30px;border-radius: 20px;text-transform: uppercase;'
                    ]);
                }
                break;
            case self::NEEDLE_RESPONSE_CODE:
            case self::NEEDLE_RESPONSE_MESSAGE:
                $info = $this->getResponseInfo();
                if ($needle == self::NEEDLE_RESPONSE_CODE) {
                    return array_keys($info);
                    break;
                } elseif ($needle == self::NEEDLE_RESPONSE_CODE) {
                    return array_values($info);
                    break;
                }
                break;
            case self::NEEDLE_CREATE_AT:
                if ($model instanceof Customer) {
                    if ($this->type === self::TYPE_CUSTOMER_REGISTER_JWT) {
                        return $model->createTime;
                    } elseif ($this->type === self::TYPE_CUSTOMER_REGISTER_SUCCESS) {
                        return $model->updateTime;
                    } elseif ($this->type === self::TYPE_CUSTOMER_RESET_PASSWORD) {
                        return Yii::$app->formatter->asDatetime('now');
                    } elseif ($this->type === self::TYPE_CUSTOMER_INVITE_REGISTER) {
                        return Yii::$app->formatter->asDatetime('now');
                    }
                } elseif ($model instanceof WalletTransaction) {
                    if (ArrayHelper::isIn($this->type, [
                        self::TYPE_TRANSACTION_TYPE_TOP_UP,
                        self::TYPE_TRANSACTION_TYPE_WITHDRAW,
                        self::TYPE_TRANSACTION_TYPE_PAY_ORDER,
                        self::TYPE_TRANSACTION_TYPE_REFUND
                    ])) {
                        return $model->create_at;
                    } elseif (ArrayHelper::isIn($this->type, [
                        self::TYPE_TRANSACTION_TYPE_TOP_UP_SUCCESS,
                        self::TYPE_TRANSACTION_TYPE_WITHDRAW_SUCCESS,
                        self::TYPE_TRANSACTION_TYPE_REFUND_SUCCESS,
                        self::TYPE_TRANSACTION_TYPE_PAY_ORDER_SUCCESS
                    ])) {
                        return $model->complete_at;
                    } elseif (ArrayHelper::isIn($this->type, [
                        self::TYPE_TRANSACTION_TYPE_TOP_UP_FAILED,
                        self::TYPE_TRANSACTION_TYPE_WITHDRAW_FAILED,
                        self::TYPE_TRANSACTION_TYPE_PAY_ORDER_FAILED,
                        self::TYPE_TRANSACTION_TYPE_REFUND_FAILED
                    ])) {
                        return $model->cancel_at;
                    }

                } elseif ($model instanceof Order) {
                    if ($this->type === self::TYPE_PAY_ORDER) {
                        return $model->createTime;
                    } elseif ($this->type === self::TYPE_THANKS_FOR_PURCHASE) {
                        return $model->LastPaidTime;
                    }

                } elseif ($model instanceof OrderItem) {
                    if ($this->type === self::TYPE_ORDER_TRACKING_STEP_1) {
                        return count($model->orderItemTrackings) > 0 ? $model->orderItemTrackings[0]->createdTime : '';
                    } elseif ($this->type === self::TYPE_ORDER_TRACKING_STEP_2) {
                        return $model->exportWarehouseStockInTime;
                    }
                } else {
                    return Yii::$app->formatter->asDatetime('now');
                }
                break;
            case self::NEEDLE_STATUS:
                if ($model instanceof WalletTransaction) {
                    return WalletTransaction::getStatusLabels($model->status);
                }elseif ($model instanceof Order){
                    if($this->type === self::TYPE_PAY_ORDER || $this->type === self::TYPE_THANKS_FOR_PURCHASE){
                        return $model->paymentStatus === 2
                            ? Yii::t('frontend','Paid')
                            : Yii::t('frontend','Unpaid');
                    }
                }
                break;
            case self::NEEDLE_NOTE:
                if($model instanceof Order){
                    return $model->note;
                }elseif( $model instanceof OrderPayment){
                    return $model->update_reason;
                }
                break;
            case self::NEEDLE_JSON_WEB_TOKEN:
                if ($this->isSubClassCustomer($model) ){
                    if ($this->type === self::TYPE_CUSTOMER_REGISTER_JWT && method_exists($model,'getJwtEncode')){
                        return $model->getJwtEncode();
                    }
                } elseif($model === null && ($user = Yii::$app->getUser()->getIdentity()) !== null && !$this->isSystemAccount($user)) {
                    if ($this->type === self::TYPE_CUSTOMER_REGISTER_JWT && method_exists($user, 'getJwtEncode')) {
                        return $user->getJwtEncode();
                    }
                }
                break;
            default:
                return '';
                break;
        }
    }

    public function getSubjectReplace()
    {
        return $this->replace($this->subject);
    }

    public function getTextContentReplace()
    {
        return $this->replace($this->text_content);
    }

    public function getHtmlLayout($title, $content)
    {
        // Todo View::findLayout
        return <<< HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>$title</title>
</head>

<body style="margin:0;padding:0;background-color:#2796b6">
$content
</body>
</html>
HTML;
    }

    public function getHtmlContentReplace()
    {
        return $this->replace($this->html_content);
    }

    /**
     * @param $identity \yii\web\IdentityInterface
     * @return bool
     */
    protected function isSystemAccount($identity){
        return CustomerHelper::isSystemAccount($identity);
    }

    /**
     * @param $object object
     * @return bool
     */
    private function isSubClassCustomer($object){
        return get_parent_class($object) === 'common\\models\\db\\Customer';
    }
}