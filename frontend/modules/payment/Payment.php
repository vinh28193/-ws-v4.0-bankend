<?php


namespace frontend\modules\payment;


use common\components\cart\CartHelper;
use common\components\cart\CartSelection;
use common\helpers\WeshopHelper;
use common\models\Address;
use common\models\db\TargetAdditionalFee;
use frontend\modules\payment\providers\alepay\AlepayProvider;
use frontend\modules\payment\providers\banktransfer\VNBankTransfer;
use frontend\modules\payment\providers\mcpay\McPayProvider;
use frontend\modules\payment\providers\nganluong\ver3_1\NganLuongProvider as NLProviderV31;
use frontend\modules\payment\providers\nganluong\ver3_2\NganLuongProvider as NLProviderV32;
use frontend\modules\payment\providers\nicepay\NicePayProvider;
use frontend\modules\payment\providers\office\WSVNOffice;
use common\promotion\PromotionResponse;
use Yii;
use yii\db\Exception;
use yii\di\Instance;
use yii\base\Model;
use frontend\modules\payment\models\Order;
use common\components\StoreManager;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\promotion\PromotionForm;
use yii\helpers\Url;

class Payment extends Model
{

    const ENV_SANDBOX = 'sandbox';
    const ENV_PRODUCT = 'product';

    const PAGE_CHECKOUT = 'CHECKOUT';
    const PAGE_TOP_UP = 'TOP_UP';

    public $env = self::ENV_PRODUCT;
    public $uuid = null;
    public $page = self::PAGE_CHECKOUT;

    /**
     * @var string buynow, shopping, installment
     */
    public $type;

    public $payment_type = 'online_payment';

    /**
     * @var string
     */
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
    public $total_order_amount;
    public $isPaid = false;

    public $payment_bank_code;
    public $payment_method;
    public $payment_method_name;
    public $payment_provider;
    public $payment_provider_name;
    public $payment_token;

    public $bank_account; // Số thẻ - Tài khoản ngân hàng
    public $bank_name; //  Họ tên chủ thẻ - tài khoản ngân hàng
    public $issue_month; //Tháng phát hành thẻ
    public $issue_year; // Năm phát hành thẻ
    public $expired_month; // Tháng hết hạn của thẻ
    public $expired_year; //  Năm hết hạn của thẻ

    public $installment_bank;
    public $installment_method;
    public $installment_month;
    public $instalment_type;

    public $acceptance_insurance = false;
    public $insurance_fee = 0;

    public $ga;
    public $otp_code;
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

    public $_user;

    public function getUser()
    {
        if ($this->_user === null && ($user = Yii::$app->user->identity) !== null) {
            $this->_user = $user;
        }
        return $this->_user;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->storeManager = Instance::ensure($this->storeManager, StoreManager::className());
        $this->view = Yii::$app->getView();
        if ($this->page !== self::PAGE_TOP_UP) {
            $this->getOrders();
        }
        $this->currency = $this->storeManager->store->currency;
    }

    public function rules()
    {
        return parent::rules();
    }

    public function initDefaultMethod()
    {
        $this->payment_method = 77;
        $this->payment_provider = 46;
        $this->payment_bank_code = 'VISA';
        if ($this->page === self::PAGE_CHECKOUT || $this->type === CartSelection::TYPE_INSTALLMENT) {
            $this->payment_method = 57;
            $this->payment_provider = 44;
        }
        $this->registerClientScript();
    }

    /**
     * @var array|Order[]
     */
    private $_orders = [];

    /**
     * @return array|Order[]
     */
    public function getOrders()
    {
        return $this->_orders;
    }

    /**
     * @param $orders
     */
    public function setOrders($orders)
    {
        $this->_orders = [];

        foreach ($orders as $param) {
            if (!isset($param['checkoutType'])) {
                $param['checkoutType'] = $this->type;
            }
            if (!isset($param['uuid'])) {
                $param['uuid'] = $this->uuid;
            }
            if (isset($param['totalFinalAmount'])) {
                unset($param['totalFinalAmount']);
            }
            $order = new Order($param);
            if ($order->createOrderFromCart() === false) {
                continue;
            }
            $this->total_order_amount += $order->getTotalFinalAmount();
            $this->_orders[] = $order;
        }

    }

    /**
     * @var array
     */
    private $_additionalFees;

    /**
     * @param null $names
     * @param bool $refresh
     * @return array|mixed
     */
    public function getAdditionalFees($refresh = false)
    {
        if (empty($this->_additionalFees) || $refresh) {
            $this->_additionalFees = [];
            foreach ($this->getOrders() as $idx => $order) {
                foreach ($order->getAdditionalFees()->keys() as $key) {
                    if (!isset($this->_additionalFees[$key])) {
                        $this->_additionalFees[$key] = 0;
                    }
                    $value = $this->_additionalFees[$key];
                    $value += $order->getAdditionalFees()->getTotalAdditionalFees($key)[1];
                    $this->_additionalFees[$key] = $value;
                }
            }
        }
        return $this->_additionalFees;
    }

    public function setAdditionalFees($arrays)
    {
        $this->_additionalFees = $arrays;
    }

    public function registerClientScript()
    {
        PaymentAssets::register($this->view);
        $options = Json::htmlEncode($this->getClientOptions());
        $this->view->registerJs("ws.payment.init($options);");
        $this->view->registerJs("console.log(ws.payment.payment);");
        if ($this->type === CartSelection::TYPE_INSTALLMENT) {
            $this->view->registerJs("ws.payment.calculateInstallment();");
        }
    }

    public function loadPaymentProviderFromCache()
    {
        return PaymentService::loadPaymentByStoreFromDb(1, $this->page === self::PAGE_TOP_UP ? $this->payment_provider : null);
    }

    public function createTransactionCode()
    {

        $this->payment_provider = (int)$this->payment_provider;
        $this->payment_method = (int)$this->payment_method;
        if (($methodProvider = PaymentService::getMethodProvider($this->payment_provider, $this->payment_method)) !== null) {
            $this->payment_method_name = $methodProvider->paymentMethod->code;
            $this->payment_provider_name = $methodProvider->paymentProvider->code;
        }
        $code = PaymentService::generateTransactionCode('PM');
        $this->transaction_code = $code;
        $this->transaction_fee = 0;
        $this->return_url = $this->page === self::PAGE_TOP_UP ? Url::to("/my-wallet/topup/{$this->payment_provider}/return.html", true) : PaymentService::createReturnUrl($this->payment_provider);
        $this->cancel_url = PaymentService::createCancelUrl();
    }

    public function processPayment()
    {
        switch ($this->payment_provider) {
            case 42:
                $nl = new NLProviderV31();
                return $nl->create($this);
            case 44:
                return (new AlepayProvider())->create($this);
            case 45:
                return (new WSVNOffice())->create($this);
            case 46:
                $nl = new NLProviderV32();
                return $nl->create($this);
            case 48:
                $nicePay = new NicePayProvider();
                return $nicePay->create($this);
            case 49:
                $mcPay = new McPayProvider();
                return $mcPay->create($this);
            case 50:
                $mcPay = new VNBankTransfer();
                return $mcPay->create($this);
        }
    }

    /**
     * @param $merchant
     * @param $request \yii\web\Request
     * @return PaymentResponse
     */
    public static function checkPayment($merchant, $request)
    {
        switch ($merchant) {

            case 42:
                $nl = new NLProviderV31();
                return $nl->handle($request->get());
            case 44:
                return (new AlepayProvider())->handle($request->get());
            case 45:
                return (new WSVNOffice())->handle($request->get());
            case 46:
                $nl = new NLProviderV32();
                return $nl->handle($request->get());
            case 48:
                $nicePay = new NicePayProvider();
                return $nicePay->handle($request->get());
            case 49:
                $mcPay = new McPayProvider();
                return $mcPay->handle($request->get());
            case 50:
                $mcPay = new VNBankTransfer();
                return $mcPay->handle($request->get());

        }
    }

    public function checkPromotion()
    {
        foreach ($this->getOrders() as $order) {
            /** @var  $order Order */
            $form = new PromotionForm();
            $form->load($order->createPromotionParam(), '');
            /** @var  $response PromotionResponse */
            $response = $form->checkPromotion();
            if ($response->success === true && $response->discount > 0 && count($response->details) > 0) {
                $order->discountAmount = $response->discount;
                $order->discountDetail = $response->details;
            }
        }
    }

    public function courierCalculator($from, $to)
    {

    }

    public function createGaData()
    {

    }

    private $_totalAmount;

    public function getTotalAmount()
    {
        if ($this->_totalAmount === null) {
            $this->_totalAmount = $this->total_order_amount;
            foreach ($this->getAdditionalFees() as $key => $value) {
                $this->_totalAmount += $value;
            }
        }
        return $this->_totalAmount;
    }

    public function setTotalAmount($totalAmount)
    {
        $this->_totalAmount = $totalAmount;
    }

    private $_totalAmountDisplay;

    public function getTotalAmountDisplay()
    {
        if ($this->_totalAmountDisplay === null) {
            $this->_totalAmountDisplay = $this->getTotalAmount();
            if ($this->total_discount_amount !== null && $this->total_discount_amount > 0) {
                $this->_totalAmountDisplay -= $this->total_discount_amount;
            }
        }
        return $this->_totalAmountDisplay;

    }

    public function setTotalAmountDisplay($totalAmountDisplay)
    {
        $this->_totalAmountDisplay = $totalAmountDisplay;
    }

    public function initPaymentView()
    {
        if ($this->type === CartSelection::TYPE_INSTALLMENT) {
            return $this->view->render('installment', [
                'payment' => $this
            ], new PaymentContextView());
        }
        $providers = $this->loadPaymentProviderFromCache();
        $group = [];
        foreach ($providers as $provider) {
            foreach ($provider['paymentMethodProviders'] as $paymentMethodProvider) {
                $k = (int)$paymentMethodProvider['paymentMethod']['group'];
                if ($k === PaymentService::PAYMENT_GROUP_ALIPAY_INSTALMENT || $k === PaymentService::PAYMENT_GROUP_MANDIRI_INSTALMENT) {
                    continue;
                }

                $group[$k][] = $paymentMethodProvider;
            }
        }
        ksort($group);
        return $this->view->render('normal', [
            'payment' => $this,
            'group' => $group
        ], new PaymentContextView());
    }

    public
    function getClientOptions()
    {
        $orders = [];
        foreach ($this->getOrders() as $order) {
            $orders[$order->cartId] = [
                'cartId' => $order->cartId,
                'checkoutType' => $order->checkoutType,
                'uuid' => $order->uuid,
                'ordercode' => $order->ordercode,
                'acceptance_insurance' => 'N',
                'courier_sort_mode' => 'best_rating',
                'courierDetail' => $order->courierDetail,
                'additionalFees' => $order->getAdditionalFees()->toArray(),
                'totalFinalAmount' => $order->total_final_amount_local,
            ];
        }
        return [
            'page' => $this->page,
            'uuid' => $this->uuid,
            'type' => $this->type,
            'orders' => (array)$orders,
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
            'total_order_amount' => $this->total_order_amount,
            'totalAmount' => $this->getTotalAmount(),
            'totalAmountDisplay' => $this->getTotalAmountDisplay(),
            'payment_bank_code' => $this->payment_bank_code,
            'payment_method' => $this->payment_method,
            'payment_method_name' => $this->payment_method_name,
            'payment_provider' => $this->payment_provider,
            'payment_provider_name' => $this->payment_provider_name,
            'payment_token' => $this->payment_token,
            'bank_account' => $this->bank_account,
            'bank_name' => $this->bank_name,
            'issue_month' => $this->issue_month,
            'issue_year' => $this->issue_year,
            'expired_month' => $this->expired_month,
            'expired_year' => $this->expired_year,
            'installment_bank' => $this->installment_bank,
            'installment_method' => $this->installment_method,
            'installment_month' => $this->installment_month,
            'instalment_type' => $this->instalment_type,
            'acceptance_insurance' => $this->acceptance_insurance,
            'insurance_fee' => $this->insurance_fee,
            'ga' => $this->ga,
            'otp_code' => $this->otp_code,
            'otp_verify_method' => $this->otp_verify_method,
            'shipment_options_status' => $this->shipment_options_status,
            'transaction_code' => $this->transaction_code,
            'transaction_fee' => $this->transaction_fee,
            'additionalFees' => $this->getAdditionalFees()
        ];
    }

}
