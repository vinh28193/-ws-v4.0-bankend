<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-15
 * Time: 08:46
 */

namespace common\models\queries;


use yii\helpers\ArrayHelper;
use common\components\db\ActiveQuery;
use common\helpers\WeshopHelper;

/**
 * Class PackageQuery
 * @package common\models\queries
 */
class PackageQuery extends ActiveQuery
{


    public function remove()
    {
        $this->andWhere([$this->getColumnName('remove') => 0]);
    }

    public function filterRelation()
    {
        $this->joinWith(['packageItems' => function (ActiveQuery $q) {
            $q->with(['order' => function (OrderQuery $orderQuery) {
                $orderQuery->with('products');
            }]);
        }]);
    }

    public function filter($params)
    {

        $this->remove();
        if (($filters = json_decode(ArrayHelper::getValue($params, 'filters', '{}'), true)) !== [] && !WeshopHelper::isEmpty($filters)) {
            $filterWhere = ['AND'];
            if (isset($filters['keyword']) && ($keyWord = ArrayHelper::getValue($filters, 'keyword')) !== null && !WeshopHelper::isEmpty($keyWord)) {
                $filterWhere[] = [
                    'OR',
                    ['LIKE', $this->getColumnName('package_code'), $keyWord],
                    ['LIKE', $this->getColumnName('tracking_seller'), $keyWord],
                    ['LIKE', $this->getColumnName('tracking_reference_1'), $keyWord],
                    ['LIKE', $this->getColumnName('tracking_reference_2'), $keyWord],
                    ['LIKE', $this->getColumnName('manifest_code'), $keyWord],
                ];
            }

            // Thêm filter ở đây

            if (count($filterWhere) > 1) {
                $this->andWhere($filterWhere);
            }
        }
        return $this;

    }
}