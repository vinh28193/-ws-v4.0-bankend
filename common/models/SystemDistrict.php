<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class SystemDistrict extends \common\models\db\SystemDistrict
{

    public static function filterDistrictByProvinceId($pId){
        $query = new Query();
        $query->select(['d.id', 'd.name']);
        $query->from(['d' => self::tableName()]);
        $query->where(['and',
            ['d.province_id' => $pId],
            ['d.remove' => 0]
        ]);
        return $query->all(self::getDb());
    }
}
