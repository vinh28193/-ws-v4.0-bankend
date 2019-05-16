<?php
/**
 * Created by PhpStorm.
 * User: quyetbq
 * Date: 14/5/2018
 * Time: 8:48 AM
 */

namespace common\models\payment;

//use common\components\Alepay\Alepay;
use common\components\Cache;
use common\components\RedisLanguage;
use common\components\RestClient;
use common\components\UrlComponent;
use common\models\analytics\Product;
use common\models\analytics\Action;
use common\models\model\PaymentMethod;
use common\models\model\PaymentProvider;
use common\models\model\Store;
use common\models\payment\malaysia\BankTransfer;
use common\models\payment\vietnam\Alepay;
use common\models\payment\wallet\Wallet;
use common\models\promotion\PromotionComponent;
use common\models\service\CategoryService;
use common\models\db\OrderItem;
use common\models\db\PaymentMethodProvider;
//use common\models\enu\StoreConfig;
use common\models\model\Order;

//use common\models\payment\service\AlepayService;
//use common\models\payment\service\DokuService;
//use common\models\payment\service\WepayService;
use common\models\promotion\PromotionForm;
use common\models\payment\indonesia\Mcpay;
use common\models\payment\indonesia\MolId;
use common\models\payment\indonesia\Nicepay;
use common\models\payment\malaysia\MolMy;
use common\models\payment\philippines\DragonPay;
use common\models\payment\philippines\Paynamics;
use common\models\payment\thailand\C2P2;
use common\models\payment\vietnam\NganLuong;
use PHPUnit\Util\Log\JSON;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\weshop\Payment as EnvPayment;
use yii\helpers\Url;

/**
 * @property Config $config
 * @property OrderItem[] $items
 */
class Payment extends Model
{
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
    const PAGE_CHECKOUT = 1;
    const PAGE_BILL = 2;
    const PAGE_ADDFEE = 3;
    const PAGE_ADDFEE_NEW = 4;
    const PAGE_TOPUP = 5;
    public $customer_name;
    public $customer_email;
    public $customer_phone;
    public $customer_address;
    public $customer_city;
    public $customer_postcode;
    public $customer_district;
    public $customer_country;
    public $order_bin;
    public $addfee_bin;
    public $wallet_bin;
    public $order_note;
    public $coupon_code;
    public $total_amount;
    public $total_amount_display;
    public $total_amount_us;
    public $transaction_id; //$transaction_id ETH
//    public $total_amount_crypto; //tổng số tiền điện tử 0.0010043  ETH
//    public $type_crypto; //loại tiền điện tử BTC:ETH:TEKY

    public $CurrencyId; //loại tiền điện tử BTC:ETH:TEKY
    public $localCurrencyCode; //loại tiền điện tử BTC:ETH:TEKY

    public $total_before_gst;
    public $total_quantity;
    public $total_gst;
    public $config;
    public $items;
    public $totalDiscountAmount = 0;
    public $totalLocalShipAmount = 0;
    public $currency;
    public $order_id;
    public $submitUrl;
    public $returnUrl;
    public $cancelUrl;
    public $event_code; //VCB_CHECK_BIN /VPB_CHECK_BIN
    public $bankCode = '';
    public $paymentMethodName;
    public $paymentProviderName;
    public $paymentMethod;
    public $paymentToken;
    public $paymentProvider;
    public $installment_bank;
    public $installment_method;
    public $installment_month;
    public $instalment_type;
    public $district_id;
    public $envPayment;
    public $function;
    public $page;
    public $gaData;
    public $ShipmentOptionsStatus = 2;//FORWARDING 1:WAIT FOR ALL

    public $bulk_point = 0;//Điểm thưởng sử dụng
    public $otp_verify_method = 1;// 1:Email,0:SMS
    public $total_shipping_weight;
    public $customer_district_id;
    public $useXu = 0;

    public function __construct($page = self::PAGE_CHECKOUT)

    {
        //INIT DEFAULT METHOD
        switch (Yii::$app->store->storeId) {
            case Store::WS_VN:
                $this->paymentMethod = 1;
                $this->paymentProvider = 22;
                $this->bankCode = "VISA";
                break;
            case Store::WS_PH:
                $this->paymentMethod = 45;
                $this->paymentProvider = 20;
                break;
            case Store::WS_ID:
                $this->paymentMethod = 64;
                $this->paymentProvider = 35;
                //$this->bankCode = "VISA";
                break;
            case Store::WS_MY:
                $this->paymentMethod = 33;
                $this->paymentProvider = 8;
                $this->bankCode = "MOLMY";
                break;
            case Store::WS_TH:
                $this->paymentProvider = 30;
                $this->paymentMethod = 55;
                $this->bankCode = "C2P2";

        }
        $this->page = $page;

    }

    public function setAttributes($values, $safeOnly = false)
    {
        $this->total_quantity = 0;
        $ws = Yii::$app->store;
//        foreach ($values['items'] as $l => $item) {
//            $temp = new OrderItem();
//            $temp->id = isset($item['id']) ? $item['id'] : null;
//            $temp->setAttributes($item);
//            $this->total_quantity += $temp->quantity;
//            $values['items'][$l] = $temp;
//        }
//        $this->total_amount_display = Yii::$app->store->showMoney($this->total_amount);
        parent::setAttributes($values, $safeOnly); // TODO: Change the autogenerated stub
        $this->website = $ws;

    }

    public function initPaymentView()
    {
        $group = [];
        $providers = Yii::$app->cache->get('provider_store' . Yii::$app->store->getStoreId());
        if ($providers == null) {
            $providers = PaymentProvider::find()->where(['storeId' => Yii::$app->store->getStoreId(), 'active' => 1])
                ->with('paymentMethodProviders', 'paymentMethodProviders.paymentMethod', 'paymentMethodProviders.paymentMethod.paymentMethodBanks', 'paymentMethodProviders.paymentMethod.paymentMethodBanks.bank')->asArray()->all();
            Yii::$app->cache->set('provider_store' . Yii::$app->store->getStoreId(), $providers, 60 * 60 * 24);
        }
        if ($this->page == self::PAGE_TOPUP) {
            $this->paymentMethod = 25;
            $this->paymentProvider = 22;
            $this->bankCode = "VCB";
        }
        $this->useXu = $this->getUserXu();
        foreach ($providers as $provider) {
            foreach ($provider['paymentMethodProviders'] as $paymentMethodProvider) {
                if ($paymentMethodProvider['paymentMethod']['group'] == self::PAYMENT_GROUP_ALIPAY_INSTALMENT && ($this->total_amount < 4500000 || $this->total_quantity > 1))
                    continue;
                if ($paymentMethodProvider['paymentMethod']['group'] == self::PAYMENT_GROUP_COD && $this->page != self::PAGE_ADDFEE)
                    continue;
                if ($paymentMethodProvider['paymentMethod']['group'] == self::PAYMENT_GROUP_MANDIRI_INSTALMENT && $this->total_amount < 500000)
                    continue;
                $group[$paymentMethodProvider['paymentMethod']['group']][] = $paymentMethodProvider;
            }
        }
        ksort($group);

        return $group;


    }

    public function getUserXu()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->identity->refresh();
            $today = date('Y-m-d H:i:s');
            Yii::info($this->total_before_gst . 'Customer_xu');
            if ($today >= Yii::$app->user->identity->usable_xu_start_date && $today <= Yii::$app->user->identity->usable_xu_expired_date) {
                $xu = Yii::$app->user->identity->usable_xu;
                if ($xu * 1000 > $this->total_before_gst) {
                    return PromotionComponent::roundDown($this->total_before_gst / 1000, 0.5);
                }
                return $xu;
            }
        }
        return 0;
    }


    public function checkEnvPayment()
    {
        $store = Yii::$app->store->getNameWithSymbol();
        $storeData = Yii::$app->params['payments'][$store];
        $this->envPayment = new EnvPayment($storeData);
        return $this->envPayment;
    }

    public static function GroupName($group)
    {
        switch ($group) {
            case 1:
                return Yii::t('frontend', 'Credit Card');
            case 2:
                return Yii::t('frontend', 'Bank Transfer');
            case 3:
                return Yii::t('frontend', 'NganLuong E-Wallet');
            case 4:
                return Yii::t('frontend', "Over WeShop's counter");
            case 5 :
                return Yii::t('frontend', 'WeShop E-Wallet');
            case 6:
                return 'COD';
            case 7:
                return "Dragon Pay";
            case 8:
                return "Paynamic";
            case 9:
                return "MOL";
            case 10:
                return "C2P2 Account";
            case 11:
                return Yii::t('frontend', 'Thanh toán trả góp');
            case 12:
                return 'Cicilan Bank';
            case 35:
                return Yii::t('frontend', 'Kartu Kredit');
            case 36:
                return "Wepay";
            case 13:
                return "Doku";
            case 77:
                return Yii::t('frontend', 'QR Code');


        }
    }


    public
    function processPayment()
    {
        $this->loadConfig();
        switch ($this->paymentProvider) {
            case 8:
//                $this->config->merchainId = 'SB_weshop';
//                $this->config->merchainPassword = '5A3FN3yq';
//                print_r($this);
//                die;
                $payment = new MolMy($this);
                return $payment->createPayment();
            case 22:
                $this->returnUrl = Url::base(true) . '/api/checkout/nganluong/callbackpayment.html';
                $rs = NganLuong::makePayment($this);
                return $rs;
            case 33:
                $payment = new Alepay();
                return $payment->createPayment($this);
            case 20:
                $payment = new DragonPay($this);
                return $payment->createPayment();
            case 34:
                $payment = new Paynamics($this);
                return $payment->createPayment();
            case 9:
                $payment = new MolId($this);
                return $payment->createPayment();
            case 29:
                $this->returnUrl = Url::base(true) . '/api/checkout/nicepay/callbackpayment.html';
                $payment = new Nicepay($this);
                $rs = $payment->createPayment();
                return $rs;
            case 30:
                $payment = new C2P2($this);
                return $payment->createPayment();
            case 35:
                $payment = new Mcpay($this);
                return $payment->createPayment();
            case 36:
                $payment = new WepayService();
                return $payment->getTransactionStatusForMerchant($this);
            case 13:
                $payment = new DokuService($this);
                return $payment->createPayment();
            case 16:
                $wallet = new Wallet([
                    'payment' => $this,
                    'website' => $this->website
                ]);
                return $wallet->create();
            case 40:
                $payment = new BankTransfer();
                $payment->payment = $this;
                return $payment->createPayment();
        }
    }

    function loadConfig()
    {
        /**
         * @var PaymentMethodProvider $data
         */
        $data = Payment::getMethodProvider($this->paymentProvider, $this->paymentMethod);
        $this->config = new \stdClass();
        $this->config->merchainId = $data->paymentProvider->merchantVerifyId;
        $this->config->merchainPassword = $data->paymentProvider->secret_key;
        $this->config->submitUrl = $data->paymentProvider->submit_url;
        $this->paymentMethodName = $data->paymentMethod->alias;
        $this->paymentProviderName = $data->paymentProvider->alias;
    }

    public function setAttributesByOrder(Order $order)
    {
        $ObjPaymentMethod = PaymentMethod::getByPk($order->paymentMethod);
        $ObjPaymentProvider = PaymentProvider::getById($order->paymentMethodProviderId);
        $this->coupon_code = $order->couponCode;
        $this->bankCode = $order->BankName;
        $this->paymentProvider = $order->paymentMethodProviderId;
        $this->paymentMethod = $order->paymentMethod;

        $this->order_bin = $order->binCode;
        $this->total_quantity = $order->totalquantity;
        $this->total_before_gst = Yii::$app->store->roundMoneyAmount($order->OrderTotalInLocalCurrency);
        $this->total_gst = $order->OrderTotalInLocalCurrencyDisplay - $order->OrderTotalInLocalCurrency;
        $this->order_id = $order->id;
        $this->total_amount_us = $order->OrderTotal;
        $this->total_amount = Yii::$app->store->roundMoneyAmount($order->OrderTotalInLocalCurrencyFinal);
        $this->total_amount_display = Yii::$app->store->showMoney($this->total_amount);
        $this->returnUrl = Yii::$app->store->getUrl() . UrlComponent::step3_bill($this->order_bin);
        $this->cancelUrl = Yii::$app->store->getUrl() . UrlComponent::step3_bill($this->order_bin);
        $this->customer_phone = $order->buyerPhone;
        $this->customer_email = $order->buyerEmail;
        $this->customer_country = $order->billingCountryName;
        $this->customer_district = $order->billingDisctrictName;
        $this->customer_city = $order->billingCityName;
        $this->customer_address = $order->buyerAddress;
        $this->customer_name = $order->buyerName;
        $this->useXu = $order->xuCount;
        $this->customer_postcode = $order->buyerPostCode;
        $this->total_shipping_weight = $order->weight;
        if (Yii::$app->store->isWSID()) {
            $this->totalLocalShipAmount = 0;
            if ($order->OrderTotalInLocalCurrencyDisplay > 10000000) {
                $this->totalLocalShipAmount = Yii::$app->store->calcLocalShipFeeID($order->buyerDistrictId, $order->OrderTotalInLocalCurrencyDisplay, $order->weight);
            } else {
                $this->totalLocalShipAmount = Yii::$app->store->calcFeeShiplocalIndo($order->buyerDistrictId, $order->weight);

            }
            $this->totalLocalShipAmount = Yii::$app->store->ExchangeRate() * $order->ShipmentLocalPerUnit;
        }

        $this->items = $order->orderItems;
        $this->total_amount = Yii::$app->store->roundMoneyAmount($order->OrderTotalInLocalCurrencyFinal);
        $this->total_amount_display = Yii::$app->store->showMoney($this->total_amount);

        if ($this->paymentProvider == 36) {
            $wepay = new WepayService();
            $price = $wepay->getPrices();
            $exWepayFee = $wepay->getFee();
            //$this->total_amount_crypto = $order->OrderTotal*(1/$price);
            $this->localCurrencyCode = 'ETH';
            $this->CurrencyId = 15;
            $this->total_amount = round($order->OrderTotal * (1 / $price) * ($order->OrderTotal * (1 / $price) * (1 - $exWepayFee)), 8);
        }
        if(!empty($order->installmentRequestData)){
            $installmentRequestData = json_decode($order->installmentRequestData,true);
            $this->installment_bank = $installmentRequestData['bankCode'];
            $this->installment_method = $installmentRequestData['paymentMethod'];
            $this->installment_month = $installmentRequestData['month'];
        }
        return $this;
    }

    public function getTotalFee()
    {
        $rs = new \stdClass();
        $rs->weshopFee = 0;
        $rs->internationalShippingFee = 0;
        $rs->localShipFee = 0;
        $rs->customeFee = 0;
        $rs->gstFee = 0;

        foreach ($this->items as $item) {
            $rs->weshopFee += floatval($item->ItemFeeServiceAmount);
            $rs->internationalShippingFee += floatval($item->ItemInternationalShippingAmount);
            $rs->localShipFee += floatval($item->ItemDomesticShippingAmount);
            $rs->customeFee += floatval($item->ItemCustomFee);
            $rs->gstFee += floatval($item->OrderItemTotalDisplay - $item->OrderItemTotal);
        }
        return $rs;
    }

    /**
     * @param $provider
     * @param $method
     * @return PaymentMethodProvider
     */
    public static function getMethodProvider($provider, $method)
    {
        $data['paymentMethodId'] = $method;
        $data['paymentProviderId'] = $provider;
        $rs = Yii::$app->cache->get('payment_' . $provider . '_' . $method);
        if (!$rs) {
            $rs = PaymentMethodProvider::find()->where(['paymentMethodId' => $method, 'paymentProviderId' => $provider])->with(['paymentProvider', 'paymentMethod'])->one();;
            Yii::$app->cache->set('payment_' . $provider . '_' . $method, $rs, 60 * 60 * 24 * 7);
        }
        return $rs;
    }

    /**
     * @return array
     */
    public function createGaData()
    {
        $idRemarketing = [];
        $valueRemarketing = 0;
        $products = [];
        $dataCriteo = [];
        if ($this->items != null) {
            foreach ($this->items as $index => $item) {
                $product = new Product();
                $listOfItem = (explode('/', $item->link)[2] === 'www.amazon.com') ? Product::AMZON : Product::EBAY;
                $product->id = ($listOfItem === Product::AMZON) ? $item->sku : $item->ParentSku;
                $product->name = $item->Name;
                $product->category = ArrayHelper::getValue(CategoryService::getAlias($item->itemCategoryId), Yii::$app->store->isWSVN() ? 'name' : 'originName');
                $product->variant = $item->specifics;
                $product->price = $item->TotalAmountInLocalCurrencyDisplay;
                $product->quantity = $item->quantity;
                $product->position = $index;
                $product->list = $listOfItem;
                $products[] = $product;
                $dataCriteo[] = [
                    'id' => $item->sku,
                    'price' => $item->TotalAmountInLocalCurrencyDisplay,
                    'quantity' => $item->quantity,
                ];

                array_push($idRemarketing, strval($item->sku));
                $valueRemarketing += floatval($item->TotalAmountInLocalCurrencyDisplay);
            }
        }
        $action = new Action();
        $action->id = $this->order_bin;
        $action->affiliation = Yii::$app->store->name();
        $action->revenue = $this->total_amount; // tiền lời;
        $tax = $this->getTotalFee()->weshopFee + $this->getTotalFee()->customeFee;

        $action->tax = $tax > 0 ? $tax : 0;
        $shipping = $this->getTotalFee()->internationalShippingFee + $this->getTotalFee()->localShipFee;
        $action->shipping = $shipping > 0 ? $shipping : 0;
        $action->coupon = $this->coupon_code ? $this->coupon_code : '';
        $action->step = 3;
        return [
            'storeId' => Yii::$app->store->getStoreId(),
            'bincode' => $this->order_bin,
            'totalAmount' => $this->total_amount,
            'identityId' => Yii::$app->user->identity ? Yii::$app->user->identity->getId() : 0,
            'ecommerce' => [
                'products' => $products,
                'action' => $action,
                'transactionId' => Action::ACTION_PURCHASE
            ],
            'mkt' => [
                'content_ids' => $idRemarketing,
                'content_type' => 'product',
                'value' => $valueRemarketing,
                'currency' => Yii::$app->store->getCurrencyName()
            ],
            'criteo' => [
                'bin' => 'BIN ' . $this->order_bin,
                'items' => $dataCriteo
            ],
        ];

    }

    /**
     * @return  PromotionForm *
     */
    public function checkPromotion($checkOnly = true)
    {
        $total = $this->getTotalFee();
        $form = new PromotionForm();
        $form->storeId = Yii::$app->store->getStoreId();
        $form->quantity = count($this->items);

        $form->orderAmount = $this->total_before_gst + $this->total_gst + $this->totalLocalShipAmount;

        $form->buyerEmail = $this->customer_email;
        $userXu = $this->getUserXu();
        if ($this->useXu > 0) {
            $this->useXu = $this->useXu > $userXu ? $userXu : $this->useXu;
        }
        $form->orderBinCode = $this->order_bin;
        $form->useXu = $this->useXu;
        $form->customerId = Yii::$app->user->isGuest ? null : Yii::$app->user->identity->getId();
        $form->gstFee = $total->gstFee * $this->website->ExchangeRate();
        $form->localShipFee = $total->localShipFee * $this->website->ExchangeRate();
        $form->weshopFee = $total->weshopFee * $this->website->ExchangeRate();
        $form->internationalShipFee = $total->internationalShippingFee * $this->website->ExchangeRate();
        $form->couponCode = $this->coupon_code;
        $form->conditionCheckService = $this->paymentMethod . '_' . $this->bankCode;
        $form->checkInstalment = $this->paymentProvider == 33 || ($this->paymentProvider == 29 && $this->paymentMethod == 59);
        if ($this->page == self::PAGE_ADDFEE) {
            $form->couponCode = null;
            $form->conditionCheckService = null;
        }
        foreach ($this->items as $item) {
            $form->category[] = $item->itemCategoryId;
            $form->itemAmount[] = $this->website->roundMoneyAmount($item->UnitPriceExclTax * $this->website->ExchangeRate());
            $form->shipping_weight[] = $item->weight;
        }
//        return PromotionService::checkPromotion($form);
        if ($checkOnly) {
            $data = PromotionComponent::checkPromotion($form);

            if (is_array($data) && isset($data['data'])) {
                return $data['data'];
            } else return null;
        } else {
            $data = PromotionComponent::usePromotion($form);
            if (is_array($data) && isset($data['data'])) {
                return $data['data'];
            } else return null;
        }

    }


}