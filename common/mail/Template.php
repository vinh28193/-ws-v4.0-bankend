<?php


namespace common\mail;


use yii\base\BaseObject;

class Template extends BaseObject
{

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
    const TYPE_APPROVE_ORDER_REQUEST = 'APPROVE_ORDER_REQUEST';
    const TYPE_THANKS_FOR_PURCHASE = 'THANKS_FOR_PURCHASE';
    const TYPE_ORDER_REQUEST_APPROVED = 'ORDER_REQUEST_APPROVED';
    const TYPE_ORDER_REQUEST_DECLINE = 'ORDER_REQUEST_DECLINE';
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

    const NEEDLE_WEBSITE_LOGO = '[WEBSITE_LOGO]';
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
    const NEEDLE_STEP_TIME =  '[STEP_TIME]';

    const NEEDLE_PAYMENT_BUTTON = '[PAYMENT_BUTTON]';
    const NEEDLE_RESPONSE_CODE = '[RESPONSE_CODE]';
    const NEEDLE_RESPONSE_MESSAGE = '[RESPONSE_MESSAGE]';

    const NEEDLE_STATUS = '[STATUS]';
    const NEEDLE_CREATE_AT = '[CREATE_AT]';
    const NEEDLE_NOTE = '[NOTE]';

    const NEEDLE_APPROVE_STATUS = '[APPROVE_STATUS]';
    const NEEDLE_APPROVE_NOTE = '[APPROVE_NOTE]';


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
     * @var MailManager
     */
    private $_manager;


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
            self::TYPE_APPROVE_ORDER_REQUEST => 'Approve a order request'
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
            self::NEEDLE_APPROVE_STATUS => 'Approve status (text) Approve/Decline',
            self::NEEDLE_APPROVE_NOTE => 'Approve note',
        ];
    }
}