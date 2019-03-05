<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-04
 * Time: 17:55
 */

namespace common\models\searchs;

class OrderSearch extends \common\models\Order
{

    public function search2($params)
    {
        $query = self::find();
        $query->withFullRelations();
        $query->filter($params);

        return new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
    }

}