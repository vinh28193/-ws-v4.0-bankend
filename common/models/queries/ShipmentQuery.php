<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-15
 * Time: 21:05
 */

namespace common\models\queries;

use yii\helpers\ArrayHelper;
use common\helpers\WeshopHelper;
use common\components\db\ActiveQuery;

class ShipmentQuery extends ActiveQuery
{

    /**
     * @return $this
     */
    public function filterRelation()
    {
        $this->joinWith(['customer', 'warehouseSend', 'packages', 'packageItems.product']);
        return $this;
    }

    /**
     * @param $params
     * @return $this
     */
    public function filter($params)
    {
        return $this;
    }
}