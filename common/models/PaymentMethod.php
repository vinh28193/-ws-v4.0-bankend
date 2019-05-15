<?php


namespace common\models;


use common\components\Cache;
use Yii;

class PaymentMethod extends \common\models\db\PaymentMethod
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethodBanks()
    {
        return $this->hasMany(PaymentMethodBank::className(), ['payment_method_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethodProviders()
    {
        return $this->hasMany(PaymentMethodProvider::className(), ['payment_method_id' => 'id']);
    }
    public static function getById($id,$cache=true){
        $key = (Yii::$app->params['CACHE_PAYMENT_METHOD'])?Yii::$app->params['CACHE_PAYMENT_METHOD']:'CACHE_PAYMENT_METHOD';
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

    public static function getByPk($id,$cache=true){
        $key = (Yii::$app->params['CACHE_PAYMENT_METHOD_BY_ID_'])?Yii::$app->params['CACHE_PAYMENT_METHOD_BY_ID_']:'CACHE_PAYMENT_METHOD_BY_ID_';
        $data = Cache::get($key);
        if(!$data || $cache == false){
            $data = @self::findOne($id);
            Cache::set($key, $data, 60 * 60 *24);
        }
        return $data;
    }
}