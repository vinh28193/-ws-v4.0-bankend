<?php


namespace common\models;

use Yii;
use yii\db\Expression;
use yii\db\Query;
use common\models\db\SystemZipcode as DbSystemZipcode;


class SystemZipcode extends DbSystemZipcode
{


    public static function loadZipCode($country, $zipcode = null, $province = null, $district = null, $refresh = false)
    {
        $cacheKey = [
            __CLASS__
        ];
        $conditions = [
            'AND',
            ['zip.system_country_id' => $country]
        ];
        if ($province !== null && $province !== '') {
            $conditions[] = ['zip.boxme_state_province_id' => $province];
            $cacheKey[] = "boxme_state_province_id $province";
        }
        if ($district !== null && $district !== '') {
            $conditions[] = ['zip.boxme_district_id' => $district];
            $cacheKey[] = "boxme_district_id $district";
        }
        if ($zipcode !== null && $zipcode !== '') {
            $conditions[] = ['like', 'zip.zip_code', $zipcode];
        }
        $cache = Yii::$app->cache;
        if (!($results = $cache->get($cacheKey)) || $refresh) {
            $query = new Query();
            $query->select([
                'zip_code' => new Expression('`zip`.`zip_code`'),
                'country_code' => 'country.name',
                'province_id' => 'province.id',
                'province_name' => 'province.name',
                'district_id' => 'district.id',
                'district_name' => 'district.name',
                'label' => new Expression('CONCAT(`zip`.`zip_code`," ","[",`province`.`name`,", ",`district`.`name`,"]")'),
                'address' => new Expression('CONCAT(`zip`.`zip_code`,", ",`province`.`name`,", ",`district`.`name`)'),
            ]);
            $query->from(['zip' => self::tableName()]);
            $query->leftJoin(['country' => SystemCountry::tableName()], ['country.id' => new Expression('[[zip.system_country_id]]')]);
            $query->leftJoin(['province' => SystemStateProvince::tableName()], ['province.id' => new Expression('[[zip.system_state_province_id]]')]);
            $query->leftJoin(['district' => SystemDistrict::tableName()], ['district.id' => new Expression('[[zip.system_district_id]]')]);
            $query->where($conditions);
            $results = $query->all(self::getDb());
            $cache->set($cacheKey, $results, 3600 * 30);
        }
        return $results;
    }

}