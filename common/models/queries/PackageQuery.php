<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-15
 * Time: 08:46
 */

namespace common\models\queries;

use common\helpers\WeshopHelper;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class PackageQuery
 * @package common\models\queries
 */
class PackageQuery extends ActiveQuery
{


    /**
     * @return string
     */
    public function getTableName()
    {
        /** @var  $class \yii\db\ActiveRecord */
        $class = $this->modelClass;
        return $class::tableName();
    }

    /**
     * @param $name
     * @param bool|string $tableName
     * @return string
     */
    public function getColumnName($name, $tableName = true)
    {
        if ($tableName === true) {
            return $this->getTableName() . '.' . $name;
        } elseif (is_string($tableName)) {
            return "$tableName.$name";
        }
        return $name;
    }

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