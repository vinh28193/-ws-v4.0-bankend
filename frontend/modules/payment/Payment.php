<?php


namespace frontend\modules\payment;


use common\components\cart\CartHelper;
use common\components\cart\CartSelection;
use common\helpers\WeshopHelper;
use common\models\Address;
use common\models\Category;
use common\models\Product;
use common\models\ProductFee;
use common\models\Seller;
use frontend\modules\payment\providers\vietnam\WalletClientProvider;
use frontend\modules\payment\providers\vietnam\WSVNOffice;
use frontend\modules\payment\providers\wallet\WalletHideProvider;
use frontend\modules\payment\providers\wallet\WalletProvider;
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
use yii\helpers\Url;

class Payment extends Model
{
    const PAGE_CHECKOUT = 'CHECKOUT';
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

    public $payment_type;

    /**
     * @var $order Order[]
     */
    public $carts;

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
        if ($this->page !== self::PAGE_TOP_UP){
            $this->loadOrdersFromCarts();
        }
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
        if ($this->page === self::PAGE_TOP_UP){
            $this->payment_method = 25;
            $this->payment_provider = 46;
            $this->payment_bank_code = 'VCB';
        }
        $this->registerClientScript();
    }

    private $_orders;

    public function getOrders()
    {
        if (!$this->_orders) {
            $this->loadOrdersFromCarts();
        }
        return $this->_orders;
    }

    public function loadOrdersFromCarts()
    {
        $data = CartHelper::createOrderParams($this->carts);
        $this->_orders = $data['orders'];
        $this->total_amount = $data['totalAmount'];
        $this->total_amount_display = $data['totalAmount'];
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
        return PaymentService::loadPaymentByStoreFromDb(1,$this->payment_provider);
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
        $this->return_url = $this->page === self::PAGE_TOP_UP ? Url::to("/my-wallet/topup/{$this->payment_provider}/return.html", true) : Url::to("/payment/{$this->payment_provider}/return.html", true);
        $this->cancel_url = Url::toRoute("/account/wallet/index", true);
    }

    public function processPayment()
    {

        switch ($this->payment_provider) {
            case 42:
                $wlHide = new WalletHideProvider();
                return $wlHide->create($this);
            case 43:
                $wallet = new WalletProvider();
                return $wallet->create($this);

            case 45:
                $office = new WSVNOffice();
                return $office->create($this);
            case 46:
                $office = new WalletClientProvider();
                return $office->create($this);
        }
    }

    /**
     * @param $merchant
     * @param $request \yii\web\Request
     * @return array|mixed|void
     */
    public static function checkPayment($merchant, $request)
    {
        switch ($merchant) {
            case 42:
                $wlHide = new WalletHideProvider();
                return $wlHide->handle($request->get());
            case 43:
                $wallet = new WalletProvider();
                return $wallet->handle($request->post());
            case 45:
                $office = new WSVNOffice();
                return $office->handle($request->get());
            case 46:
                $office = new WalletClientProvider();
                return $office->handle($request->get());
        }
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

    /**
     * @param null $receiverAddress Address
     * @param bool $paymentSuccess bool
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function createOrder($receiverAddress = null, $paymentSuccess = true)
    {

        $now = Yii::$app->getFormatter()->asDatetime('now');
        $promotionDebug = [];
        if ($receiverAddress === null) {
            $receiverAddress = Yii::$app->user->identity->defaultShippingAddress;
        }
        /* @var $results PromotionResponse */
        $results = $this->checkPromotion();
        $transaction = Order::getDb()->beginTransaction();
        try {
            foreach ($this->getOrders() as $key => $params) {
                $orderPromotions = []; // chứa toàn tộ những promotion được áp dụng cho order có key là $key
                if ($results->success === true && count($results->orders)) {
                    foreach ($results->orders as $promotion => $data) {
                        if (($discountForMe = ArrayHelper::getValue($data, $key)) === null) {
                            continue;
                        }
                        $orderPromotions[$promotion] = $discountForMe;
                    }
                }
                // 1 order
                $order = new Order();
                $order->type_order = Order::TYPE_SHOP;
                $order->portal = isset($params['portal']) ? $params['portal'] : explode(':', $key)[0];
                $order->customer_type = 'Retail';
                $order->exchange_rate_fee = $this->storeManager->getExchangeRate();
                $order->payment_type = $this->payment_type;
                $order->receiver_email = $receiverAddress->email;
                $order->receiver_name = $receiverAddress->last_name . ' ' . $receiverAddress->last_name;
                $order->receiver_phone = $receiverAddress->phone;
                $order->receiver_address = $receiverAddress->address;
                $order->receiver_country_id = $receiverAddress->country_id;
                $order->receiver_country_name = $receiverAddress->country_name;
                $order->receiver_province_id = $receiverAddress->province_id;
                $order->receiver_province_name = $receiverAddress->province_name;
                $order->receiver_district_id = $receiverAddress->district_id;
                $order->receiver_district_name = $receiverAddress->district_name;
                $order->receiver_post_code = $receiverAddress->post_code;
                $order->receiver_address_id = $receiverAddress->id;
                $order->total_paid_amount_local = 0;

                if (($sellerParams = ArrayHelper::getValue($params, 'seller')) === null || !isset($sellerParams['seller_name']) || $sellerParams['seller_name'] === null || $sellerParams['seller_name'] === '') {
                    $transaction->rollBack();
                    return ['success' => false, 'message' => 'can not create order from not found seller'];
                }
                // 2 .seller
                if (($seller = Seller::find()->where(['AND', ['seller_name' => $sellerParams['seller_name']], ['portal' => isset($sellerParams['portal']) ? $sellerParams['portal'] : $order->portal]])->one()) === null) {
                    $seller = new Seller();
                    $seller->seller_name = $sellerParams['seller_name'];
                    $seller->portal = isset($sellerParams['portal']) ? $sellerParams['portal'] : $order->portal;
                    $seller->seller_store_rate = isset($sellerParams['seller_store_rate']) ? $sellerParams['seller_store_rate'] : null;
                    $seller->seller_link_store = isset($sellerParams['seller_link_store']) ? $sellerParams['seller_link_store'] : null;
                    $seller->save(false);
                }
                // 3. update seller for order
                $order->seller_id = $seller->id;
                $order->seller_name = $seller->seller_name;
                $order->seller_store = $seller->seller_link_store;
                if (!$order->save(false)) {
                    $transaction->rollBack();
                    return ['success' => false, 'message' => 'can not create order'];
                }
                $orderDiscount = 0; // số tiền discout cho toàn bộ order (không cho phí nào)
                $productPromotions = []; // discount cho các phí của product
                if (!empty($orderPromotions)) {
                    foreach ($orderPromotions as $promotion => $data) {
                        $value = (int)ArrayHelper::getValue($data, 'totalDiscountAmount', 0);
                        $orderDiscount += $value;

                        if (($discountForProduct = ArrayHelper::getValue($data, 'products')) !== null) {
                            $productPromotions[$promotion] = $discountForProduct;
                            continue;
                        }
                        $promotionDebug[] = [
                            'apply' => $now,
                            'code' => $promotion,
                            'level' => 'order',
                            'level_id' => $order->id,
                            'value' => $value
                        ];

                    }
                }

                $updateOrderAttributes = [];

                $updateOrderAttributes['total_promotion_amount_local'] = $orderDiscount;

                // 4 products
                if (($products = ArrayHelper::getValue($params, 'products')) === null) {
                    $transaction->rollBack();
                    return ['success' => false, 'message' => 'an item is invalid'];
                }
                foreach ($products as $id => $item) {
                    $myDiscounts = [];
                    if (!empty($productPromotions)) {
                        foreach ($productPromotions as $promotion => $data) {
                            if (($current = ArrayHelper::getValue($data, $id)) === null) {
                                continue;
                            }
                            $myDiscounts[$promotion] = $current;
                        }
                    }
//                    Yii::info($myDiscounts, $id);
                    // 5 create product
                    $product = new Product();
                    $product->order_id = $order->id;
                    $product->portal = $item['portal'];
                    $product->sku = $item['sku'];
                    $product->parent_sku = $item['parent_sku'];
                    $product->link_img = $item['link_img'];
                    $product->link_origin = $item['link_origin'];
                    $product->product_link = $item['product_link'];
                    $product->product_name = $item['product_name'];
                    $product->quantity_customer = $item['quantity_customer'];
                    $product->total_weight_temporary = $item['total_weight_temporary'];
                    $product->price_amount_origin = $item['price_amount_origin'];
                    $product->total_fee_product_local = $item['total_fee_product_local'];
                    $product->price_amount_local = $item['price_amount_local'];
                    $product->total_price_amount_local = $item['total_price_amount_local'];
                    $product->quantity_purchase = null;
                    /** Todo */
                    $product->quantity_inspect = null;
                    /** Todo */
                    $product->variations = null;
                    /** Todo */
                    $product->variation_id = null;
                    $product->remove = 0;
                    $product->version = '4.0';
                    // 6. // step 4: create category for each item
                    if (($categoryParams = ArrayHelper::remove($item, 'category')) === null) {
                        $transaction->rollBack();
                        return ['success' => false, 'message' => 'invalid param for an item'];
                    }
                    if (($category = Category::findOne(['AND', ['alias' => $categoryParams['alias']], ['site' => isset($categoryParams['portal']) ? $categoryParams['portal'] : $product->portal]])) === null) {
                        $category = new Category();
                        $category->alias = $categoryParams['alias'];
                        $category->site = isset($categoryParams['portal']) ? $categoryParams['portal'] : $product->portal;
                        $category->origin_name = ArrayHelper::getValue($categoryParams, 'origin_name', null);
                        $category->save(false);
                    }
                    // 7. set category id for product
                    $product->category_id = $category->id;
                    // 8. set seller id for product
                    $product->seller_id = $seller->id;
                    // 9. product discount amount
                    // save total product discount here
                    if (!$product->save(false)) {
                        $transaction->rollBack();
                        return ['success' => false, 'message' => 'can not save a product'];
                    }
                    $productDiscount = 0;
                    $feeDiscounts = []; // chứa discount của toàn bộ phí
                    if (!empty($myDiscounts)) {
                        foreach ($myDiscounts as $promotion => $discount) {
                            $value = (int)ArrayHelper::getValue($discount, 'totalDiscountAmount', 0);
                            $productDiscount += $value;
                            $feeDiscounts[$promotion] = ArrayHelper::getValue($discount, 'discountFees', []);
                            $promotionDebug[] = [
                                'apply' => $now,
                                'code' => $promotion,
                                'level' => 'product',
                                'level_id' => $product->id,
                                'value' => $value
                            ];
                        }
                    }
//                    $product->updateAttributes(['total_discount_amount' => $productDiscount]);
                    // 9. product fee
                    if (($productFees = ArrayHelper::getValue($item, 'fees')) === null || count($productFees) === 0) {
                        $transaction->rollBack();
                        return ['success' => false, 'message' => 'can not get fee for an item'];
                    }
                    $orderTotalAmountLocal = 0;
                    $totalFeeAmountLocal = 0;
                    foreach ($productFees as $feeName => $feeValue) {
                        // 10. create each fee
                        $orderAttribute = '';
                        if ($feeName === 'product_price_origin') {
                            // Tổng giá gốc của các sản phẩm tại nơi xuất xứ
                            $orderAttribute = 'total_origin_fee_local';
                        }
                        if ($feeName === 'tax_fee_origin') {
                            // Tổng phí tax của các sản phẩm tại nơi xuất xứ
                            $orderAttribute = 'total_origin_tax_fee_local';
                        }
                        if ($feeName === 'origin_shipping_fee') {
                            // Tổng phí ship của các sản phẩm tại nơi xuất xứ
                            $orderAttribute = 'total_origin_shipping_fee_local';
                        }
                        if ($feeName === 'weshop_fee') {
                            // Tổng phí phí dịch vụ wéhop fee của các sản phẩm
                            $orderAttribute = 'total_weshop_fee_local';
                        }
                        if ($feeName === 'intl_shipping_fee') {
                            // Tổng phí phí vận chuyển quốc tế của các sản phẩm
                            $orderAttribute = 'total_intl_shipping_fee_local';
                        }
                        if ($feeName === 'custom_fee') {
                            // Tổng phí phụ thu của các sản phẩm
                            $orderAttribute = 'total_custom_fee_amount_local';
                        }
                        if ($feeName === 'packing_fee') {
                            // Tổng phí đóng gói của các sản phẩm
                            $orderAttribute = 'total_packing_fee_local';
                        }
                        if ($feeName === 'inspection_fee') {
                            // Tổng đóng hàng của các sản phẩm
                            $orderAttribute = 'total_inspection_fee_local';
                        }
                        if ($feeName === 'insurance_fee') {
                            // Tổng bảo hiểm của các sản phẩm
                            $orderAttribute = 'total_insurance_fee_local';
                        }
                        if ($feeName === 'vat_fee') {
                            // Tổng vat của các sản phẩm
                            $orderAttribute = 'total_vat_amount_local';
                        }
                        if ($feeName === 'delivery_fee_local') {
                            // Tổng vận chuyển tại local của các sản phẩm
                            $orderAttribute = 'total_delivery_fee_local';
                        }

                        $productFee = new ProductFee();
                        $productFee->type = $feeName;
                        $productFee->name = $feeValue['name'];
                        $productFee->order_id = $order->id;
                        $productFee->product_id = $product->id;
                        $productFee->amount = $feeValue['amount'];
                        $productFee->local_amount = $feeValue['local_amount'];
                        $productFee->discount_amount = 0;
                        $productFee->currency = $feeValue['currency'];
                        if (!$productFee->save(false)) {
                            $transaction->rollBack();
                            return ['success' => false, 'message' => 'can not deploy an fee'];
                        }
                        $orderTotalAmountLocal += $productFee->local_amount;
                        if ($productFee->type !== 'product_price_origin') {
                            $totalFeeAmountLocal += $productFee->local_amount;
                        }
                        // 10. update discount each fee
                        $discountForFeeAmount = 0;
                        if (!empty($feeDiscounts)) {
                            foreach ($feeDiscounts as $promotion => $data) {
                                if (($forCurrentFee = ArrayHelper::getValue($data, $productFee->type)) === null) {
                                    continue;
                                }
                                $discountForFeeAmount += $forCurrentFee;
                                $promotionDebug[] = [
                                    'apply' => $now,
                                    'code' => $promotion,
                                    'level' => 'fee',
                                    'level_id' => $productFee->id,
                                    'value' => $forCurrentFee
                                ];
                            }
                        }
                        if ($discountForFeeAmount > 0) {
                            $productFee->updateAttributes(['discount_amount' => $discountForFeeAmount]);
                        }
                        if ($orderAttribute !== '') {
                            if ($orderAttribute === 'total_origin_fee_local') {
                                // Tổng giá gốc của các sản phẩm tại nơi xuất xứ (giá tại nơi xuất xứ)
                                $oldAmount = isset($updateOrderAttributes['total_price_amount_origin']) ? $updateOrderAttributes['total_price_amount_origin'] : 0;
                                $oldAmount += $productFee->amount;
                                $updateOrderAttributes['total_price_amount_origin'] = $oldAmount;
                            }
                            $value = isset($updateOrderAttributes[$orderAttribute]) ? $updateOrderAttributes[$orderAttribute] : 0;
                            $value += $productFee->local_amount;
                            $updateOrderAttributes[$orderAttribute] = $value;
                        }
                    }

                    // Tổng các phí các sản phẩm (trừ giá gốc tại nơi xuất xứ)
                    $oldAmount = isset($updateOrderAttributes['total_fee_amount_local']) ? $updateOrderAttributes['total_fee_amount_local'] : 0;
                    $oldAmount += $totalFeeAmountLocal;
                    $updateOrderAttributes['total_fee_amount_local'] = $oldAmount;

                    // Tổng tiền (bao gồm tiền giá gốc của các sản phẩm và các loại phí)
                    $oldAmount = isset($updateOrderAttributes['total_amount_local']) ? $updateOrderAttributes['total_amount_local'] : 0;
                    $oldAmount += $orderTotalAmountLocal;
                    $updateOrderAttributes['total_amount_local'] = $oldAmount;

                    $updateOrderAttributes['total_final_amount_local'] = $oldAmount;

                }

                $updateOrderAttributes['ordercode'] = WeshopHelper::generateTag($order->id, 'WSVN', 16);
                $updateOrderAttributes['total_final_amount_local'] = $updateOrderAttributes['total_amount_local'] - $updateOrderAttributes['total_promotion_amount_local'];
                if ($paymentSuccess) {
                    $updateOrderAttributes['total_paid_amount_local'] = $updateOrderAttributes['total_final_amount_local'];
                }
                $order->updateAttributes($updateOrderAttributes);
            }
            $transaction->commit();
            return ['success' => true, 'message' => 'create order success'];
        } catch (Exception $exception) {
            $transaction->rollBack();
            Yii::error($exception, __METHOD__);
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    public function initPaymentView()
    {
        if ($this->page === self::PAGE_TOP_UP) {
            $this->payment_method = 25;
            $this->payment_provider = 46;
            $this->payment_bank_code = 'VCB';
        } elseif ($this->payment_type === CartSelection::TYPE_INSTALLMENT) {
            return $this->view->render('installment', [
                'payment' => $this
            ], new PaymentContextView());
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
        return $this->view->render('normal', [
            'payment' => $this,
            'group' => $group
        ], new PaymentContextView());
    }

    public function getClientOptions()
    {
        return [
            'page' => $this->page,
            'payment_type' => $this->payment_type,
            'carts' => (array)$this->carts,
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