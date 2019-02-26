<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 09:07
 */

namespace common\models;

use common\models\db\Store as BbStore;
use common\components\StoreInterface;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class Store extends BbStore implements StoreInterface
{

    public static function getStoreReferenceKey()
    {
        return 'store_id';
    }

    public function getStoreAdditionalFee()
    {
        return $this->hasMany(StoreAdditionalFee::className(), ['store_id' => 'store_id']);
    }

    /**
     * @param $condition
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public static function getActiveStore($condition)
    {
        
        if(($store = self::findOne($condition)) === null){
            $condition = reset($condition);
            throw new NotFoundHttpException("Not found Store $condition");
        }
        $storeAdditionalFee = StoreAdditionalFee::findAll(['store_id' => $store->id]);
        if($storeAdditionalFee !== null){
            $storeAdditionalFee = ArrayHelper::index($storeAdditionalFee,'name');
        }else if ($storeAdditionalFee === null){
            $storeAdditionalFee = [];
        }
        $store->populateRelation('storeAdditionalFee',$storeAdditionalFee);
        return $store;
    }
}