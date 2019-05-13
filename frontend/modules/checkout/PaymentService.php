<?php


namespace frontend\modules\checkout;

use Yii;
use common\models\PaymentProvider;

class PaymentService
{

    public static function loadPaymentByStoreFromDb($store){
        $query = PaymentProvider::find();
        $query->with('paymentMethodProviders.paymentMethod.paymentMethodBanks.paymentBank');
        $query->where([
            'AND',
            ['store_id' => $store],
            ['status' => 1]
        ]);
        return $query->asArray()->all();
    }
}