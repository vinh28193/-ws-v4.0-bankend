<?php


namespace frontend\modules\checkout;

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

    public $page = self::PAGE_CHECKOUT;

    public $order_bin;

    public $customer_name;
    public $customer_email;
    public $customer_phone;
    public $customer_address;
    public $customer_city;
    public $customer_postcode;
    public $customer_district;
    public $customer_district_id;
    public $customer_country;

    /**
     * @var $order Order[]
     */
    public $orders;

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
        return PaymentService::loadPaymentByStoreFromDb($this->storeManager->id);
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
        PaymentAssets::register($view);
        $options = Json::htmlEncode($this->getClientOptions());
        $view->registerJs("ws.payment.init($options);");
        $providers = $this->loadPaymemtProviderFromCache();
        return $view->render('payment', [
            'provinders' => $providers,
            'payment' => $this
        ]);
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
}