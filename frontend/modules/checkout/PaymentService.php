<?php


namespace frontend\modules\checkout;

use Yii;
use common\models\PaymentProvider;

class PaymentService
{

    public static function loadPaymentByStoreFromDb($store)
    {
        $query = PaymentProvider::find();
        $query->with('paymentMethodProviders.paymentMethod.paymentMethodBanks.paymentBank');
        $query->where([
            'AND',
            ['store_id' => $store],
            ['status' => 1]
        ]);
        return $query->asArray()->all();
    }

    public static function getGroupName($group)
    {
        switch ((int)$group) {
            case Payment::PAYMENT_GROUP_MASTER_VISA:
                return 'Credit Card';
            case Payment::PAYMENT_GROUP_BANK:
                return 'Bank Transfer';
            case Payment::PAYMENT_GROUP_NL_WALLET:
                return 'NganLuong E-Wallet';
            case Payment::PAYMENT_GROUP_WSVP:
                return 'Over WeShop\'s counter';
            case Payment::PAYMENT_GROUP_WS_WALLET:
                return 'WeShop E-Wallet';
            case Payment::PAYMENT_GROUP_COD:
                return 'COD';
            case Payment::PAYMENT_GROUP_DRAGON:
                return 'Dragon Pay';
            case Payment::PAYMENT_GROUP_PAYNAMIC:
                return 'Paynamic';
            case Payment::PAYMENT_GROUP_MOLMY:
                return 'MOL';
            case Payment::PAYMENT_GROUP_C2P2:
                return 'C2P2 Account';
            case Payment::PAYMENT_GROUP_ALIPAY_INSTALMENT:
                return 'Thanh toán trả góp';
            case Payment::PAYMENT_GROUP_MANDIRI_INSTALMENT:
                return 'Cicilan Bank';
            case Payment::PAYMENT_GROUP_MCPAY:
                return 'Kartu Kredit';
            case Payment::PAYMENT_GROUP_WEPAY:
                return 'Wepay';
            case Payment::PAYMENT_GROUP_DOKU:
                return 'Doku';
            case Payment::PAYMENT_GROUP_NL_QRCODE:
                return 'QR Code';
            case Payment::PAYMENT_GROUP_MY_BANK_TRANSFER:
                return 'Bank Transfer';
            default:
                return 'Unknown';
        }
    }
}