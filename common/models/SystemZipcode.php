<?php


namespace common\models;

use common\helpers\WeshopHelper;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use common\models\db\SystemZipcode as DbSystemZipcode;


class SystemZipcode extends DbSystemZipcode
{


    public static function loadZipCode($country, $zipcode = null, $offset = 0, $limit = 10)
    {


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
        $query->andWhere(['zip.system_country_id' => $country]);
        if (!WeshopHelper::isEmpty($zipcode)) {
            $query->andWhere('`zip`.`zip_code` like :zip', [':zip' => $zipcode . '%']);
        }

        $query->limit($limit);
        $query->offset($offset);
        $totalCount = (clone $query)->limit(-1)->offset(-1)->count(new Expression('[[zip.zip_code]]'));
        $results = $query->all(self::getDb());
        return [$results, $totalCount];
    }

}