<?php

namespace common\components\cart\storage;

use common\components\cart\CartHelper;
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

    public function filterItem($filters, $type = null, $identity = null)
    {
        $conditions = ['remove' => 0];
        if ($type !== null) {
            $conditions = ['type' => $type];
        }
        if ($identity !== null) {
            $conditions['identity'] = $identity;
        }
        foreach ($filters as $attr => $value) {
            $conditions = ArrayHelper::merge($conditions, $this->buildFilterAggregationPipeline($attr, $value));
        }
        return $this->aggregate($conditions);
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
            $conditions[] = !is_array($ids) ? ['_id' => $ids] : ['IN', '_id', $ids];
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

    public function calculateSupported($supportId = null)
    {
        $match = [
            'AND',
            ['$gt', 'create_at', CartHelper::beginSupportDay()],
            ['$lt', 'create_at', CartHelper::endSupportDay()],
            ['$ne', 'identity', new Expression('NULL')]
        ];
        if ($supportId !== null) {
            $match[] = is_array($supportId) ? ['$in', 'key.supportId', $supportId] : ['key.supportId' => (string)$supportId];
        }

        $aggregate = [
            [
                [
                    '$match' => $match
                ],
            ],
            [

            ]
        ];
        return $this->mongodb->getCollection($this->collection)->aggregate([
            [
                '$match' => $match
            ],
            [
                '$group' => [
                    '_id' => ['$toInt' => '$key.supportId'],
                    'price' => [
                        '$sum' => '$value.total_amount_local'
                    ],
                    'count' => [
                        '$sum' => 1
                    ]
                ]
            ],
            [
                '$sort' => [
                    'count' => 1, 'price' => 1
                ]
            ]
        ]);
    }

    public function filterShoppingCarts($params)
    {
        $limit = (int)ArrayHelper::remove($params, 'limit', 10);
        $page = ArrayHelper::remove($params, 'page', 1);
        $skip = ($page - 1) * $limit;
        $conditions = [
            'AND',
            ['remove' => 0],
            ['NOT', 'identity', new Expression('null')],
        ];
        if (isset($params['value']) && !isset($params['keyword'])) {
            $conditions[] = ['OR',
                ['data.order.customer.email', $params['value']],
                ['data.order.customer.phone', $params['value']],
            ];
        }
        if (isset($params['value']) && isset($params['keyword'])) {
            $conditions[] = ['OR',
                [$params['keyword'], $params['value']],
                [$params['keyword'], $params['value']],
            ];
        }

        $aggregate = [
            [
                '$match' => $conditions
            ],
            [
                '$project' => [
                    '_id' => ['$toString' => '$_id'],
                    'type' => '$type',
                    'key' => '$key',
                    'order' => '$value',
                    'identity' => '$identity'
                ]
            ],
        ];
        $countAggregate = $aggregate;
        $countAggregate [] = [
            '$count' => 'sum'
        ];
        $aggregate[] = [
            '$limit' => $limit,
        ];
        return [
            'count' => $this->mongodb->getCollection($this->collection)->aggregate($countAggregate),
            '_items' => $this->mongodb->getCollection($this->collection)->aggregate($aggregate)
        ];
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
