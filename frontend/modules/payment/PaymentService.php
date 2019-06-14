<?php


namespace frontend\modules\payment;
;


use common\components\cart\CartHelper;
use common\helpers\WeshopHelper;
use common\models\Category;
use common\models\db\TargetAdditionalFee;
use common\models\PaymentMethodProvider;
use common\models\Seller;
use common\models\User;
use frontend\modules\payment\models\Order;
use frontend\modules\payment\models\Product;
use Yii;
use common\models\PaymentProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class PaymentService
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
    const PAYMENT_GROUP_DOKU = 13;
    const PAYMENT_GROUP_MY_BANK_TRANSFER = 55;

    // NEW
    const PAYMENT_GROUP_QRCODE = 78;
    const PAYMENT_GROUP_IB = 79;
    const PAYMENT_GROUP_ATM = 80;
    const PAYMENT_GROUP_BANK_TRANSFER = 81;
    const PAYMENT_GROUP_BANK_OFFICE = 82;
    const PAYMENT_GROUP_VN_BANK_TRANSFER = 83;

    public static function getClientConfig($merchant, $env = null)
    {
        $params = ArrayHelper::getValue(Yii::$app->params, "paymentClientParams.{$merchant}", []);
        $env = $env === null ? $params['enable'] : $env;
        return isset($params['params'][$env]) ? $params['params'][$env] : (isset($params['params']) ? $params['params'] : []);
    }

    public static function loadPaymentByStoreFromDb($store, $provider_id = null)
    {
        $query = PaymentProvider::find();
        $where = [
            'AND',
            ['store_id' => $store],
            ['status' => 1]
        ];
        if ($provider_id !== null) {
            $where[] = ['id' => $provider_id];
        }
        $query->with('paymentMethodProviders', 'paymentMethodProviders.paymentMethod', 'paymentMethodProviders.paymentMethod.paymentMethodBanks', 'paymentMethodProviders.paymentMethod.paymentMethodBanks.paymentBank');
        $query->where($where);
        return $query->asArray()->all();
    }

    /**
     * @param $provider
     * @param $method
     * @return PaymentMethodProvider|null
     */
    public static function getMethodProvider($provider, $method)
    {
        return PaymentMethodProvider::find()
            ->with(['paymentProvider', 'paymentMethod'])
            ->where([
                'AND',
                ['payment_provider_id' => $provider],
                ['payment_method_id' => $method],
            ])
            ->one();
    }

    public static function getGroupName($group)
    {
        switch ((int)$group) {
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
            case self::PAYMENT_GROUP_QRCODE:
                return 'QR Code';
            case self::PAYMENT_GROUP_MY_BANK_TRANSFER:
                return 'Bank Transfer';
            default:
                return 'Unknown';
        }
    }

    /**
     * @param Payment $payment
     */
    public static function createOrderFormCart(Payment $payment)
    {
        $errors = [];
        $keys = $payment->carts;
        $start = microtime(true);
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        $orders = [];
        $totalOrderAmount = 0;

        $items = CartHelper::getCartManager()->getItems($payment->type, $keys, $payment->uuid);
        foreach ($items as $item) {
            $params = $item['value'];
            $cartId = $item['_id'];
            if (($sellerParams = ArrayHelper::remove($params, 'seller')) === null || !is_array($sellerParams)) {
                $errors[] = 'Not found seller for order';
                continue;
            }
            $seller = new Seller($sellerParams);
            if (($productParams = ArrayHelper::remove($params, 'products')) === null || !is_array($productParams)) {
                $errors[] = 'Not found products for order';
                continue;
            }
            $supporter = ArrayHelper::remove($params, 'saleSupport');
            if ($supporter !== null) {
                $supporter = new User($supporter);
            }
            $productFailed = false;
            $products = [];
            foreach ($productParams as $key => $productParam) {
                if (($categoryParams = ArrayHelper::remove($productParam, 'category')) === null || !is_array($categoryParams)) {
                    $productFailed = true;
                    $errors[] = 'Not found category for product';
                    continue;
                }
                $category = new Category([
                    'alias' => ArrayHelper::getValue($categoryParams, 'alias'),
                    'siteId' => ArrayHelper::getValue($categoryParams, 'site'),
                    'originName' => ArrayHelper::getValue($categoryParams, 'origin_name'),
                ]);
                if (($productFeeParams = ArrayHelper::remove($productParam, 'fees')) === null || !is_array($productFeeParams)) {
                    $productFailed = true;
                    $errors[] = 'Not found fee for product';
                    continue;
                }
                $fees = [];
                foreach ($productFeeParams as $name => $value) {
                    $targetFee = new TargetAdditionalFee($value);
                    $targetFee->type = 'product';
                    $fees[] = $targetFee;
                }
                unset($productParam['available_quantity']);
                unset($productParam['quantity_sold']);
                $product = new Product($productParam);
                $product->populateRelation('productFees', $fees);
                $product->populateRelation('category', $category);
                $products[$key] = $product;
            }
            if ($productFailed) {
                continue;
            }
            unset($params['customer']);
            unset($params['support_name']);
            $order = new Order($params);
            $order->cartId = $cartId;
            $order->populateRelation('products', $products);
            $order->populateRelation('seller', $seller);
            $order->populateRelation('saleSupport', $supporter);
            $order->loadPaymentAdditionalFees();
            $totalOrderAmount += (int)$order->total_amount_local;
            $orders[] = $order;
        }
        $time = sprintf('%.3f', microtime(true) - $start);
        Yii::info("create orders total time: $time s", __METHOD__);
        Yii::info($errors, __METHOD__);
        return [
            'totalAmount' => $totalOrderAmount,
            'orders' => $orders,
        ];
    }

    public
    static function createCheckPromotionParam(Payment $payment)
    {
        $feeOrders = [];
        foreach ($payment->getOrders() as $key => $order) {
            $fees = [];
            foreach ($order->getAdditionalFees()->keys() as $name) {
                $fees[$name] = $order->getAdditionalFees()->getTotalAdditionalFees($name)[1];
            }
            $feeOrders[$order->ordercode] = [
                'totalAmount' => $order->total_amount_local,
                'additionalFees' => $fees
            ];
        }
        return [
            'couponCode' => $payment->coupon_code,
            'paymentService' => implode('_', [$payment->payment_method, $payment->payment_bank_code]),
            'totalAmount' => $payment->getTotalAmount(),
            'orders' => $feeOrders,
        ];
    }

    public
    static function toNumber($value)
    {
        return (integer)$value;
    }

    public
    static function generateTransactionCode($prefix = 'PM')
    {
        return WeshopHelper::generateTag(time(), 'PM', 16);
    }

    public
    static function createReturnUrl($provider)
    {
        return Url::toRoute(["/payment/payment/return", 'merchant' => $provider], true);
    }

    public
    static function createCancelUrl()
    {
        return Url::toRoute("/checkout/cart", true);
    }

    public
    static function getInstallmentBankIcon($code)
    {
        $icons = [
            'VPBANK' => 'img/bank/vpbank.png', //NH TMCP Việt Nam Thịnh Vượng (VPBANK)
            'TECHCOMBANK' => 'img/bank/techcombank.png', //NH TMCP Kỹ Thương Việt Nam (TECHCOMBANK)
            'ACB' => 'img/bank/acb.png', //NH TMCP Á Châu (ACB)
            'ANZ' => 'img/bank/ANZ.png', //NH TNHH MTV ANZ Việt Nam (ANZ)
            'HSBC' => 'img/bank/hsbc.png', //NH TNHH MTV HSBC (Việt Nam) (HSBC)
            'SHINHANBANK' => 'img/bank/shinhanbank.png', // NH TNHH MTV Shinhan Việt Nam (SHINHANBANK)
            'EXIMBANK' => 'img/bank/eximbank.png',  //NH TMCP Xuất Nhập Khẩu (EXIMBANK)
            'MARITIMEBANK' => 'img/bank/maritime.png', //NH TMCP Hàng Hải (MARITIMEBANK)
            'VIB' => 'img/bank/vp.png', //NH Quốc tế (VIB)
            'SACOMBANK' => 'img/bank/sacombank.png', //NH TMCP Sài Gòn Thương Tín (SACOMBANK)
            'CTB' => 'img/bank/citibank.png', //NH CitiBank Việt Nam (CTB)
            'SEABANK' => 'img/bank/seabank.png', //NH TMCP Đông Nam Á (SEABANK)
            'SC' => 'img/bank/standerd-charterd.png', //NH TNHH MTV Standard Chartered (Việt Nam) (SC)
            'TPB' => 'img/bank/tpb.png', //NH TMCP Tiên Phong (TPB)
            'SCB' => 'img/bank/scb.png', //NH TMCP Sài Gòn (SCB)
            'FE' => 'img/bank/fe.png', //FE CREDIT (FE)
            'NAB' => 'img/bank/nam-a.png', //NH TMCP Nam Á (NAB)
            'OCB' => 'img/bank/ocb.png', //NH Phương Đông (OCB)
            'KLB' => 'img/bank/kien-long.png', //NH TMCP Kiên Long (KLB)
            'SHB' => 'img/bank/shb.png', //NH TMCP Sài Gòn Hà Nội (SHB)
            'BIDV' => 'img/bank/bidv.png', //NH TMCP Đầu Tư và Phát Triển Việt Nam (BIDV)
            'VCB' => 'img/bank/vietcombank.png', //NH TMCP Ngoại Thương Việt Nam (VCB)
            'MB' => 'img/bank/mb.png' //NH TMCP Quân Đội (MB)
        ];
        $icon = isset($icons[$code]) ? Url::to($icons[$code], true) : ArrayHelper::getValue(Yii::$app->params, 'unknownBankCode', '#');
        return $icon;
    }

    public
    static function getInstallmentMethodIcon($code)
    {
        $icons = [
            'VISA' => 'img/bank/visa.png',
            'MASTERCARD' => 'img/bank/master.png',
            'JCB' => 'img/bank/jcb.png',
        ];
        $icon = isset($icons[$code]) ? Url::to($icons[$code], true) : ArrayHelper::getValue(Yii::$app->params, 'unknownMethodCode', '#');
        return $icon;
    }

}