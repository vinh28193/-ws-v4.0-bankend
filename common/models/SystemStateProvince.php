<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class SystemStateProvince extends \common\models\db\SystemStateProvince
{

    public static function select2Data($country = 1, $dataKey = 'id', $dataValue = 'name', $refreshCache = false)
    {

        $cacheKey = ['SystemStateProvince', $country, $dataKey, $dataValue];
        if (!($provinces = Yii::$app->cache->get($cacheKey)) || $refreshCache) {
            $query = new Query();
            $query->from(['p' => self::tableName()]);
            $query->orderBy('display_order , name');
            $query->select(["id" => "p.$dataKey", "name" => "p.$dataValue"]);
            $query->where(['AND', ['p.remove' => 0], ['p.country_id' => $country]]);
            $provinces = ArrayHelper::map($query->all(self::getDb()), $dataKey, $dataValue);
            Yii::$app->cache->set($cacheKey, $provinces, 3600);
        }
        return $provinces;
    }
    public static function select2DataForCountry($country_id, $refreshCache = false)
    {
        $cacheKey = 'SystemProvince'.$country_id;
        if (!($data = Yii::$app->cache->get($cacheKey)) || $refreshCache) {
            $data = self::find()->select('id,country_id,name')->where(['country_id' => $country_id,'remove' => 0])->asArray()->all();
            Yii::$app->cache->set($cacheKey, $data, 60*60*24*60);
        }
        return $data;
    }

}
