<?php


namespace common\models;


use common\components\Cache;
use Yii;

class PaymentProvider extends \common\models\db\PaymentProvider
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethodProviders()
    {
        return $this->hasMany(PaymentMethodProvider::className(), ['payment_provider_id' => 'id'])->joinWith(['paymentMethod' => function ($q) {
            $q->where(['payment_method.status' => 1]);
        }], false);
    }
    public static function getById($id,$cache = true){
        $key = (Yii::$app->params['CACHE_PAYMENT_PROVINDER'])?Yii::$app->params['CACHE_PAYMENT_PROVINDER']:'CACHE_PAYMENT_PROVINDER';
        $data = Cache::get($key);
        if(!$data || $cache == false){
            $data = @self::find()->asArray()->all();
            Cache::set($key, $data, 60 * 60 *24);
        }
        if(!empty($data[$id])){
            return $data[$id];
        }
        return '';

    }
}