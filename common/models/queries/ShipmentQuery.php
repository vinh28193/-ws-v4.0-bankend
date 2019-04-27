<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-15
 * Time: 21:05
 */

namespace common\models\queries;

use common\models\DeliveryNote;
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
        $this->with(['customer','warehouseSend']);
        $this->joinWith(['packages', 'packageItems' ,'packageItems.product', 'packageItems.order']);
        return $this;
    }

    /**
     * @param $params
     * @return $this
     */
    public function filter($params)
    {

        $this->andWhere(['active' => 1]);
        if (isset($params['q']) && ($q = $params['q']) !== null && $q !== '') {
            if (isset($params['qref']) && ($qref = $params['qref']) !== null && $qref !== '') {
                if ($qref === 'shipmentCode') {
                    $this->andWhere([$this->getColumnName('shipment_code') => $q]);
                } elseif ($qref === 'packageCode') {
                    $this->andWhere([$this->getColumnName('package_code', DeliveryNote::tableName()) => $q]);
                }
            } else {
                $this->andWhere([
                    'OR',
                    [$this->getColumnName('shipment_code') => $q],
                    [$this->getColumnName('package_code', DeliveryNote::tableName()) => $q]
                ]);
            }
        }
        if (isset($params['s']) && ($status = $params['s']) !== null && $status !== '') {
            $this->andWhere([$this->getColumnName('shipment_status') => $status]);
        }
        if (isset($params['wh']) && ($warehouse = $params['s']) !== null && $warehouse !== '') {
            $this->andWhere([$this->getColumnName('warehouse_send_id') => $warehouse]);
        }
        return $this;
    }
}