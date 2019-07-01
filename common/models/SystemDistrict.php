<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class SystemDistrict extends \common\models\db\SystemDistrict
{

    public static function select2Data($province_id = 1, $dataKey = 'id', $dataValue = 'name', $refreshCache = false)
    {
        $cacheKey = ['SystemDistrict',$province_id, $dataKey, $dataValue];
        if (!($provinces = Yii::$app->cache->get($cacheKey)) || $refreshCache) {
            $query = new Query();
            $query->from(['d' => self::tableName()]);
            $query->select(["id" => "d.$dataKey", "name" => "d.$dataValue"]);
            $query->where(['AND', ['d.remove' => 0], ['d.province_id' => $province_id]]);
            $provinces = $query->all(self::getDb());
            Yii::$app->cache->set($cacheKey, $provinces, 3600);
        }
        return $provinces;
    }
    public static function selectData($province_id = 1, $dataKey = 'id', $dataValue = 'name', $refreshCache = false)
    {
        $cacheKey = ['SystemDistrict_select_',$province_id, $dataKey, $dataValue];
        if (!($provinces = Yii::$app->cache->get($cacheKey)) || $refreshCache) {
            $query = new Query();
            $query->from(['d' => self::tableName()]);
            $query->select(["id" => "d.$dataKey", "name" => "d.$dataValue"]);
            $query->where(['AND', ['d.remove' => 0], ['d.province_id' => $province_id]]);
            $provinces = ArrayHelper::map($query->all(self::getDb()), $dataKey, $dataValue);
            Yii::$app->cache->set($cacheKey, $provinces, 3600);
        }
        return $provinces;
    }
    public static function select2DataForCountry($country_id, $refreshCache = false)
    {
        $cacheKey = 'SystemDistrict'.$country_id;
        if (!($data = Yii::$app->cache->get($cacheKey)) || $refreshCache) {
            $data = self::find()->select('id,province_id,name')->where(['country_id' => $country_id,'remove' => 0])->asArray()->all();
            Yii::$app->cache->set($cacheKey, $data, 60*60*24*60);
        }
        return $data;
    }
}
