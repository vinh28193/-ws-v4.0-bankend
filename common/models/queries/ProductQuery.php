<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-19
 * Time: 19:47
 */

namespace common\models\queries;


class ProductQuery extends \common\components\db\ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function defaultSelect($columns = [])
    {
        $this->select(array_merge([
            $this->getColumnName('id'),
            $this->getColumnName('order_id'),
            $this->getColumnName('portal'),
            $this->getColumnName('sku'),
            $this->getColumnName('parent_sku'),
            $this->getColumnName('link_img'),
            $this->getColumnName('link_origin'),
            $this->getColumnName('product_name'),
            $this->getColumnName('created_at'),

        ], $columns));
        return $this;
    }
}