<?php

namespace common\components\cart\storage;

use phpDocumentor\Reflection\Type;
use Yii;
use common\models\User;
use yii\base\BaseObject;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\mongodb\Connection;
use yii\mongodb\Query;

class MongodbCartStorage extends BaseObject
{

    /**
     * @var string|Connection
     */
    public $mongodb = 'mongodb';

    /**
     * @var array|string
     */
    public $collection = ['weshop_global_40', 'shopping_cart_upgrade'];


    public function init()
    {
        parent::init();
        if (!$this->mongodb instanceof Connection) {
            $this->mongodb = Yii::$app->get($this->mongodb);
        }

    }

    /**
     * @param $id
     * @return bool
     * @throws \yii\mongodb\Exception
     */
    public function hasItem($id)
    {
        $query = new Query();
        $where = [
            'AND',
            ['_id' => $id],
            ['remove' => 0],
        ];
        $query->from($this->collection);
        $query->where($where);
        return $query->count($this->mongodb) > 0;
    }

    /**
     * @param $type
     * @param $key
     * @param $value
     * @param $identity
     * @return \MongoDB\BSON\ObjectID
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\mongodb\Exception
     */
    public function addItem($type, $key, $value, $identity)
    {
        return $this->mongodb->getCollection($this->collection)->insert([
            'remove' => 0,
            'type' => $type,
            'key' => $key,
            'value' => $value,
            'identity' => $identity,
            'create_at' => Yii::$app->getFormatter()->asDatetime('now'),
        ]);
    }

    /**
     * @param $id
     * @param $key
     * @param $value
     * @return bool|int
     * @throws \yii\mongodb\Exception
     */
    public function setItem($id, $key, $value)
    {
        return $this->mongodb->getCollection($this->collection)->update([
            'AND', ['_id' => $id], ['remove' => 0]
        ], ['key' => $key, 'value' => $value]);
    }

    /**
     * @param $type
     * @param $id
     * @param null $identity
     * @return array|false
     */
    public function getItem($type, $id, $identity = null)
    {
        $conditions = ['AND', ['type' => $type], ['_id' => $id], ['remove' => 0]];
        if ($identity !== null) {
            $conditions[] = ['identity' => $identity];
        }
        $query = new Query();
        $query->from($this->collection);
        $query->where($conditions);
        return $query->one($this->mongodb);
    }

    public function filterItem($type, $filter, $identity = null)
    {
        $conditions = ['type' => $type, 'remove' => 0];
        if ($identity !== null) {
            $conditions['identity'] = $identity;
        }
        return $this->aggregate(ArrayHelper::merge($conditions, $this->buildFilterAggregationPipeline('key', $filter)));
    }

    /**
     * @param $id
     * @param null $identity
     * @return bool|int
     * @throws \yii\mongodb\Exception
     */
    public function removeItem($id)
    {
        return $this->mongodb->getCollection($this->collection)->update(['AND', ['_id' => $id], ['remove' => 0]], ['remove' => 1]);
    }

    /**
     * @param $type
     * @param $identity
     * @param null $ids
     * @return array
     */
    public function getItems($identity, $type = null, $ids = null)
    {
        $conditions = ['AND', ['identity' => $identity], ['remove' => 0]];
        if ($type !== null) {
            $conditions[] = ['type' => $type];
        }
        if ($ids !== null) {
            $conditions[] = ['IN', '_id', !is_array($ids) ? [$ids] : $ids];
        }

        return $this->aggregate($conditions);
    }

    protected function aggregate($conditions)
    {
        return $this->mongodb->getCollection($this->collection)->aggregate([
            [
                '$match' => $conditions
            ],
            [
                '$project' => [
                    '_id' => ['$toString' => '$_id'],
                    'type' => '$type',
                    'key' => '$key',
                    'value' => '$value',
                    'identity' => '$identity'
                ]
            ]
        ]);
    }

    /**
     * @param $type
     * @param $identity
     * @return int
     * @throws \yii\mongodb\Exception
     */
    public function countItems($identity, $type = null)
    {
        $query = new Query();
        $conditions = ['AND', ['identity' => $identity], ['remove' => 0]];
        if ($type !== null) {
            $conditions[] = ['type' => $type];
        }
        $query->from($this->collection);
        $query->where($conditions);
        return $query->count($this->mongodb);

    }

    /**
     * @param $type
     * @param $identity
     * @param null $ids
     * @return bool|int
     * @throws \yii\mongodb\Exception
     */
    public function removeItems($type, $identity, $ids = null)
    {
        $conditions = ['AND', ['type' => $type], ['identity' => $identity], ['remove' => 0]];
        if ($ids !== null) {
            $conditions[] = ['IN', '_id', !is_array($ids) ? [$ids] : $ids];
        }

        return $this->mongodb->getCollection($this->collection)->update($conditions, ['remove' => 1]);
    }

    /**
     * @param $id
     * @return bool|int
     * @throws \yii\mongodb\Exception
     */
    public function setMeOwnerItem($id)
    {
        if (($item = self::find()->where()->one()) === null) {
            return false;
        }
        return $this->mongodb->getCollection($this->collection)->update(['AND', ['_id' => $id], ['remove' => 0], ['identity' => null]], ['remove' => 1]);
    }


    public function filterShoppingCarts($params)
    {
        $query = new Query();
        $query->from($this->collection);

        $conditions = [
            'AND',
            ['remove' => 0],
            ['is not', 'identity', new Expression('null')],
        ];
        if (isset($params['value'])) {
            $conditions = ['OR', [
                'value' => [
                    '$elemMatch' => [
                        'customer.email' => $params['value'],
                        'customer.phone' => $params['value'],
                    ]
                ]
            ]];
        }
        $query->where($conditions);
        return $query->all($this->mongodb);
    }

    private function buildKeyFilter($key)
    {
        $filter = [];
        foreach ($key as $name => $value) {
            if ($name === 'products' && is_array($value)) {
                $match = [];
                foreach ($value as $array) {
                    foreach ($array as $k => $v) {
                        if ($k === 'id' || $k === 'sku') {
                            $match[$k] = $v;;
                        }
                    }

                }
                $value = ['$elemMatch' => $match];
            }
            $filter["key.{$name}"] = $value;
        }
        return $filter;
    }

    public function buildFilterAggregationPipeline($attribute, $params)
    {
        $lines = [];
        foreach ($params as $name => $value) {
            $key = "$attribute.$name";
            if (is_array($value)) {
                $lines = ArrayHelper::merge($lines, $this->buildFilterAggregationPipeline($key, $value));
            } else {
                $lines[$key] = $value;
            }


        }
        return $lines;
    }
}
