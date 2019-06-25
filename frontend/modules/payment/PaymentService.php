<?php


namespace frontend\modules\payment;


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

    const PAYMENT_METHOD_GROUP_COD = 1;
    const PAYMENT_METHOD_GROUP_INSTALMENT = 2;
    const PAYMENT_METHOD_GROUP_MASTER_VISA = 3;
    const PAYMENT_METHOD_NL_WALLET = 4;
    const PAYMENT_METHOD_GROUP_ATM = 5;
    const PAYMENT_METHOD_GROUP_QRCODE = 6;
    const PAYMENT_METHOD_BANK_TRANSFER = 7;
    const PAYMENT_METHOD_BANK_MCPAY = 8;

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