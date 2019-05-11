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

    public function initDefaultMethod(){
        switch ($this->storeManager->getId()){
            case 1:
                $this->payment_method = 1;
                $this->payment_provider = 22;
                $this->payment_bank_code = 'VISA';
                break;
        }
    }
    public function loadPaymentProviderFromCache(){
        return [];
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

    public function initPaymentView(){
        $view = Yii::$app->getView();
        PaymentAssets::register($view);
        $options = Json::htmlEncode($this->getClientOptions());
        $view->registerJs("ws.payment.init($options);");
        $provinders = $this->loadPaymemtProviderFromCache();
        return $view->render('payment',[
            'provinders' => $provinders,
            'payment' => $this
        ]);
    }

    public function getClientOptions(){

    }
}