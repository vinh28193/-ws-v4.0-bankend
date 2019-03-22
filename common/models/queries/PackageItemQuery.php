<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-19
 * Time: 19:17
 */

namespace common\models\queries;


class PackageItemQuery extends \common\components\db\ActiveQuery
{

    /**
     * @inheritdoc
     */
    public function defaultSelect($columns = [])
    {
        $this->select(array_merge([
            $this->getColumnName('id'),
            $this->getColumnName('package_id'),
            $this->getColumnName('box_me_warehouse_tag'),
            $this->getColumnName('order_id'),
            $this->getColumnName('quantity'),
            $this->getColumnName('weight'),
            $this->getColumnName('dimension_l'),
            $this->getColumnName('dimension_w'),
            $this->getColumnName('dimension_h'),
            $this->getColumnName('stock_in_local'),
            $this->getColumnName('stock_in_local'),
            $this->getColumnName('current_status'),
            $this->getColumnName('price'),
            $this->getColumnName('cod'),
        ], $columns));
        return $this;
    }

    /**
     * @return $this
     */
    public function filterRelation(){
        $this->joinWith(['package' => function(PackageQuery $packageQuery){
            $packageQuery->defaultSelect();
        }]);
        $this->joinWith(['order' => function(OrderQuery $orderQuery){
            $orderQuery->defaultSelect();
        }]);
        $this->with(['products' => function(ProductQuery $productQuery){
            $productQuery->defaultSelect();
        }]);
        $this->with(['shipment']);
        return $this;
    }

    public function filter($params){
        return $this;
    }
}