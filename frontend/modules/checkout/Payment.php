<?php


namespace frontend\modules\checkout;

use frontend\modules\checkout\models\ShippingForm;
use Yii;
use yii\di\Instance;
use yii\base\Model;
use common\models\Order;
use common\models\Product;
use common\components\StoreManager;
use yii\base\InvalidParamException;
use yii\helpers\Json;


class Payment extends Model
{
    const PAGE_CHECKOUT = 'CHECKOUT';
    const PAGE_BILLING = 'BILLING';
    const PAGE_TOP_UP = 'TOP_UP';

    const PAYMENT_GROUP_MASTER_VISA = 1;
    const PAYMENT_GROUP_BANK = 2;
    const PAYMENT_GROUP_NL_WALLET = 3;
    const PAYMENT_GROUP_WSVP = 4;
    const PAYMENT_GROUP_WS_WALLET = 5;
    const PAYMENT_GROUP_ALIPAY_INSTALMENT = 11;
    const PAYMENT_GROUP_MANDIRI_INSTALMENT = 12;
    const PAYMENT_GROUP_COD = 6;
    const PAYMENT_GROUP_MOLMY = 9;
    const PAYMENT_GROUP_DRAGON = 7;
    const PAYMENT_GROUP_PAYNAMIC = 8;
    const PAYMENT_GROUP_C2P2 = 10;
    const PAYMENT_GROUP_MCPAY = 35;
    const PAYMENT_GROUP_WEPAY = 36;
    const PAYMENT_GROUP_NL_QRCODE = 77;
    const PAYMENT_GROUP_DOKU = 13;
    const PAYMENT_GROUP_MY_BANK_TRANSFER = 55;

    const  ALEPAY_INSTALMENT_MIN = 0;

    public $page = self::PAGE_CHECKOUT;

    public $order_bin;

    /**
     * @var $order Order[]
     */
    public $orders;

    /**
     * @var ShippingForm
     */
    public $shippingForm;

    public $use_xu = 0;
    public $bulk_point = 0;
    public $coupon_code;
    public $discount_detail;
    public $total_discount_amount = 0;

    public $currency;
    public $total_amount;
    public $total_amount_display;

    public $payment_bank_code;
    public $payment_method;
    public $payment_method_name;
    public $payment_provider;
    public $payment_provider_name;
    public $payment_token;
    public $installment_bank;
    public $installment_method;
    public $installment_month;
    public $instalment_type;

    public $ga;
    public $otp_verify_method = 0;
    public $shipment_options_status = 2;
    public $transaction_id;
    public $transaction_fee;

    /**
     * @var string|StoreManager
     */
    public $storeManager = 'storeManager';

    public function init()
    {
        parent::init();
        $this->storeManager = Instance::ensure($this->storeManager, StoreManager::className());
        $this->initDefaultMethod();

    }

    public function rules()
    {
        return parent::rules();
    }

    public function initDefaultMethod()
    {
        switch ($this->storeManager->getId()) {
            case 1:
                $this->payment_method = 1;
                $this->payment_provider = 22;
                $this->payment_bank_code = 'VISA';
                break;
        }
    }

    public function loadPaymentProviderFromCache()
    {
        return PaymentService::loadPaymentByStoreFromDb(1);
    }

    public function processPayment()
    {

    }

    public function checkPromotion($checkOnly = true)
    {

    }

    public function createGaData()
    {

    }

    public function initPaymentView()
    {
        $view = Yii::$app->getView();
        $options = Json::htmlEncode($this->getClientOptions());
        $view->registerJs("ws.payment.init($options);");
        $providers = $this->loadPaymentProviderFromCache();
        if ($this->page === self::PAGE_TOP_UP) {
            $this->payment_method = 25;
            $this->payment_provider = 22;
            $this->payment_bank_code = 'VCB';
        }
        $group = [];
        foreach ($providers as $provider) {
            foreach ($provider['paymentMethodProviders'] as $paymentMethodProvider) {
                if ($paymentMethodProvider['paymentMethod']['group'] === self::PAYMENT_GROUP_ALIPAY_INSTALMENT && ($this->total_amount < self::ALEPAY_INSTALMENT_MIN || count($this->orders) > 1 )){
                    continue;
                }

                if ($paymentMethodProvider['paymentMethod']['group'] === self::PAYMENT_GROUP_MANDIRI_INSTALMENT && $this->total_amount < 500000){
                    continue;
                }

                if (
                    $this->page == self::PAGE_TOP_UP &&
                    (
                        $paymentMethodProvider['paymentMethod']['group'] === self::PAYMENT_GROUP_WS_WALLET ||
                        $paymentMethodProvider['paymentMethod']['group'] === self::PAYMENT_GROUP_WSVP ||
                        $paymentMethodProvider['paymentMethod']['group'] === self::PAYMENT_GROUP_MASTER_VISA
                    )
                ){
                    continue;
                }

                $group[$paymentMethodProvider['paymentMethod']['group']][] = $paymentMethodProvider;
            }
        }
        return $view->render('payment', [
            'payment' => $this,
            'group' => $group
        ], new PaymentViewContext());
    }

    public function getClientOptions()
    {
        return [
            'page' => $this->page,
            'orders' => $this->orders,
            'use_xu' => $this->use_xu,
            'bulk_point' => $this->bulk_point,
            'coupon_code' => $this->coupon_code,
            'discount_detail' => $this->discount_detail,
            'total_discount_amount' => $this->total_discount_amount,
            'currency' => $this->currency,
            'total_amount' => $this->total_amount,
            'total_amount_display' => $this->total_amount_display,
            'payment_bank_code' => $this->payment_bank_code,
            'payment_method' => $this->payment_method,
            'payment_method_name' => $this->payment_method_name,
            'payment_provider' => $this->payment_provider,
            'payment_provider_name' => $this->payment_provider_name,
            'payment_token' => $this->payment_token,
            'installment_bank' => $this->installment_bank,
            'installment_method' => $this->installment_method,
            'installment_month' => $this->installment_month,
            'instalment_type' => $this->instalment_type,
            'ga' => $this->ga,
            'otp_verify_method' => $this->otp_verify_method,
            'shipment_options_status' => $this->shipment_options_status,
            'transaction_id' => $this->transaction_id,
            'transaction_fee' => $this->transaction_fee,
        ];
    }

    public static function getGroupName($group)
    {
        switch ($group) {
            case self::PAYMENT_GROUP_MASTER_VISA:
                return 'Credit Card';
            case self::PAYMENT_GROUP_BANK:
                return 'Bank Transfer';
            case self::PAYMENT_GROUP_NL_WALLET:
                return 'NganLuong E-Wallet';
            case self::PAYMENT_GROUP_WSVP:
                return 'Over WeShop\'s counter';
            case self::PAYMENT_GROUP_WS_WALLET:
                return 'WeShop E-Wallet';
            case self::PAYMENT_GROUP_COD:
                return 'COD';
            case self::PAYMENT_GROUP_DRAGON:
                return 'Dragon Pay';
            case self::PAYMENT_GROUP_PAYNAMIC:
                return 'Paynamic';
            case self::PAYMENT_GROUP_MOLMY:
                return 'MOL';
            case self::PAYMENT_GROUP_C2P2:
                return 'C2P2 Account';
            case self::PAYMENT_GROUP_ALIPAY_INSTALMENT:
                return 'Thanh toán trả góp';
            case self::PAYMENT_GROUP_MANDIRI_INSTALMENT:
                return 'Cicilan Bank';
            case self::PAYMENT_GROUP_MCPAY:
                return 'Kartu Kredit';
            case self::PAYMENT_GROUP_WEPAY:
                return 'Wepay';
            case self::PAYMENT_GROUP_DOKU:
                return 'Doku';
            case self::PAYMENT_GROUP_NL_QRCODE:
                return 'QR Code';
            case self::PAYMENT_GROUP_MY_BANK_TRANSFER:
                return 'Bank Transfer';
        }
    }
}