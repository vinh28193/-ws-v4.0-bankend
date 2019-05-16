<?php


namespace common\payment;


use common\promotion\PromotionResponse;
use Yii;
use yii\db\Exception;
use yii\di\Instance;
use yii\base\Model;
use common\models\Order;
use common\components\StoreManager;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\promotion\PromotionForm;
use common\payment\providers\wallet\WalletService;

class Payment extends Model
{
    const PAGE_CHECKOUT = 'CHECKOUT';
    const PAGE_INSTALMENT = 'INSTALMENT';
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

    public $carts;

    /**
     * @var $order Order[]
     */
    public $orders;

    public $transaction_code;
    public $transaction_fee;
    public $return_url;
    public $cancel_url;
    public $success_url;

    public $customer_name;
    public $customer_email;
    public $customer_phone;
    public $customer_address;
    public $customer_city;
    public $customer_postcode;
    public $customer_district;
    public $customer_country;


    public $use_xu = 0;
    public $bulk_point = 0;
    public $coupon_code;
    public $discount_detail = [];
    public $discount_orders = [];
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

    /**
     * @var string|StoreManager
     */
    public $storeManager = 'storeManager';

    /**
     * @var yii\web\View
     */
    public $view;


    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->storeManager = Instance::ensure($this->storeManager, StoreManager::className());
        $this->view = Yii::$app->getView();
        $this->initDefaultMethod();
        $this->registerClientScript();
        $this->currency = 'vnđ';
    }

    public function rules()
    {
        return parent::rules();
    }

    public function initDefaultMethod()
    {
        $this->payment_method = 1;
        $this->payment_provider = 42;
        $this->payment_bank_code = 'VISA';
    }


    public function registerClientScript()
    {
        PaymentAssets::register($this->view);
        $options = Json::htmlEncode($this->getClientOptions());
        $this->view->registerJs("ws.payment.init($options);");
        $this->view->registerJs("console.log(ws.payment.payment);");
    }

    public function loadPaymentProviderFromCache()
    {
        return PaymentService::loadPaymentByStoreFromDb(1);
    }

    public function createTransactionCode()
    {

        if (($methodProvider = PaymentService::getMethodProvider($this->payment_provider, $this->payment_method)) !== null) {
            $this->payment_method_name = $methodProvider->paymentMethod->code;
            $this->payment_provider_name = $methodProvider->paymentProvider->code;
        }
        $code = PaymentService::generateTransactionCode('PM');
        if($this->payment_provider === 42){
            $wallet = new WalletService([
                'total_amount' => $this->total_amount - $this->total_discount_amount,
                'payment_provider' => $this->payment_provider,
                'payment_method' => $this->payment_method,
                'bank_code' => $this->payment_bank_code,
            ]);
        }
        $this->transaction_code = PaymentService::generateTransactionCode('PM');
        $this->transaction_fee = 0;
    }

    public function processPayment()
    {
        var_dump($this->payment_provider);
        die;
    }

    public function checkPromotion()
    {
        $form = new PromotionForm();
        $params = PaymentService::createCheckPromotionParam($this);
        $form->loadParam($params);
        /** @var  $response PromotionResponse */
        $response = $form->checkPromotion();
        if ($response->success === true && $response->discount > 0 && count($response->details) > 0) {
            $this->total_discount_amount = $response->discount;
            $this->discount_detail = $response->details;
            $this->discount_orders = $response->orders;
            $this->total_amount_display = $this->total_amount - $this->total_discount_amount;
        }
        return $response;
    }

    public function createGaData()
    {

    }

    public function initPaymentView()
    {
        $view = Yii::$app->getView();

        if ($this->page === self::PAGE_TOP_UP) {
            $this->payment_method = 25;
            $this->payment_provider = 43;
            $this->payment_bank_code = 'VCB';
        } elseif ($this->page === self::PAGE_INSTALMENT) {
            return $this->view->render('installment', [
                'payment' => $this
            ]);
        }
        $providers = $this->loadPaymentProviderFromCache();
        $group = [];
        foreach ($providers as $provider) {
            foreach ($provider['paymentMethodProviders'] as $paymentMethodProvider) {
                $k = (int)$paymentMethodProvider['paymentMethod']['group'];
                if ($k === self::PAYMENT_GROUP_ALIPAY_INSTALMENT || $k === self::PAYMENT_GROUP_MANDIRI_INSTALMENT) {
                    continue;
                }

                if ($this->page == self::PAGE_TOP_UP &&
                    (
                        $k === self::PAYMENT_GROUP_WS_WALLET ||
                        $k === self::PAYMENT_GROUP_WSVP ||
                        $k === self::PAYMENT_GROUP_MASTER_VISA
                    )
                ) {
                    continue;
                }

                $group[$k][] = $paymentMethodProvider;
            }
        }
        ksort($group);
        return $view->render('normal', [
            'payment' => $this,
            'group' => $group
        ], new PaymentContextView());
    }

    public function getClientOptions()
    {
        return [
            'page' => $this->page,
            'orders' => $this->orders,
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'customer_address' => $this->customer_address,
            'customer_city' => $this->customer_city,
            'customer_postcode' => $this->customer_postcode,
            'customer_district' => $this->customer_district,
            'customer_country' => $this->customer_country,
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
            'transaction_code' => $this->transaction_code,
            'transaction_fee' => $this->transaction_fee,
        ];
    }

}