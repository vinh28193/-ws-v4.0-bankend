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
     * @param $uuid
     * @return \MongoDB\BSON\ObjectID
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\mongodb\Exception
     */
    public function addItem($type, $key, $value, $uuid)
    {
        return $this->mongodb->getCollection($this->collection)->insert([
            'remove' => 0,
            'type' => $type,
            'key' => $key,
            'value' => $value,
            'uuid' => $uuid,
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
     * @param null $uuid
     * @return array|false
     */
    public function getItem($type, $id, $uuid = null)
    {
        $conditions = ['AND', ['type' => $type], ['_id' => $id], ['remove' => 0]];
        if ($uuid !== null) {
            $conditions[] = ['uuid' => $uuid];
        }
        $query = new Query();
        $query->from($this->collection);
        $query->where($conditions);
        return $query->one($this->mongodb);
    }

    public function filterItem($filters, $type = null, $uuid = null)
    {
        $conditions = ['remove' => 0];
        if ($type !== null) {
            $conditions['type'] = $type;
        }
        if ($uuid !== null) {
            $conditions['uuid'] = $uuid;
        }
        foreach ($filters as $attr => $value) {
            $conditions = ArrayHelper::merge($conditions, $this->buildFilterAggregationPipeline($attr, $value));
        }
        return $this->aggregate($conditions);
    }

    /**
     * @param $id
     * @param null $uuid
     * @return bool|int
     * @throws \yii\mongodb\Exception
     */
    public function removeItem($id)
    {
        return $this->mongodb->getCollection($this->collection)->update(['AND', ['_id' => $id], ['remove' => 0]], ['remove' => 1]);
    }

    /**
     * @param $type
     * @param $uuid
     * @param null $ids
     * @return array
     */
    public function getItems($uuid, $type = null, $ids = null)
    {
        $conditions = ['AND', ['uuid' => $uuid], ['remove' => 0]];
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
                    'uuid' => '$uuid'
                ]
            ]
        ]);
    }

    /**
     * @param $type
     * @param $uuid
     * @return int
     * @throws \yii\mongodb\Exception
     */
    public function countItems($uuid, $type = null)
    {
        $query = new Query();
        $conditions = ['AND', ['uuid' => $uuid], ['remove' => 0]];
        if ($type !== null) {
            $conditions[] = ['type' => $type];
        }
        $query->from($this->collection);
        $query->where($conditions);
        return $query->count($this->mongodb);

    }

    /**
     * @param $type
     * @param $uuid
     * @param null $ids
     * @return bool|int
     * @throws \yii\mongodb\Exception
     */
    public function removeItems($type, $uuid, $ids = null)
    {
        $conditions = ['AND', ['type' => $type], ['uuid' => $uuid], ['remove' => 0]];
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
        if (($this->mongodb->getCollection($this->collection)->findOne($id)) === null) {
            return false;
        }
        return $this->mongodb->getCollection($this->collection)->update(['AND', ['_id' => $id], ['remove' => 0], ['uuid' => null]], ['uuid' => Yii::$app->user->isGuest ? null : Yii::$app->user->id]);
    }

    public function calculateSupported($supportId = null)
    {
        $match = [
            'AND',
            ['$gt', 'create_at', CartHelper::beginSupportDay()],
            ['$lt', 'create_at', CartHelper::endSupportDay()],
            ['$ne', 'uuid', new Expression('NULL')]
        ];
        if ($supportId !== null) {
            $match[] = is_array($supportId) ? ['$in', 'key.supportId', $supportId] : ['key.supportId' => (string)$supportId];
        }

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
            ['NOT', 'uuid', new Expression('null')],
        ];
        if (isset($params['value']) && !isset($params['keyword'])) {
            $conditions = ['OR',
                ['LIKE', 'key.buyer.buyer_email', $params['value']],
                ['value.ordercode', $params['value']],
                ['LIKE', 'key.buyer.buyer_phone', $params['value']],
            ];
        }
        if (isset($params['statusShopping']) && !empty($params['statusShopping'])) {
            $conditions = ['value.current_status' => $params['statusShopping']];
        }

        if (isset($params['portal']) && !empty($params['portal'])) {
            $conditions = ['value.portal' => $params['portal']];
        }

        if (isset($params['saleID']) && !empty($params['saleID'])) {
            $conditions = ['value.sale_support_id' => $params['saleID']];
        }
        if (isset($params['potential']) && !empty($params['potential'])) {
            $conditions = ['value.potential' => (int)$params['potential']];
        }
        if (isset($params['value']) && isset($params['keyword'])) {
            $conditions = [$params['keyword'] => $params['value']];
        }
        if (isset($params['timeKey']) && isset($params['startTime']) && $params['endTime']) {
            $start = (Yii::$app->formatter->asTimestamp($params['startTime']));
            $end = (Yii::$app->formatter->asTimestamp($params['endTime']));
            $conditions = ['between', $params['timeKey'], $start, $end];
        }
        if (!isset($params['timeKey']) && isset($params['startTime']) && $params['endTime']) {
            $start = (Yii::$app->formatter->asTimestamp($params['startTime']));
            $end = (Yii::$app->formatter->asTimestamp($params['endTime']));
            $conditions = ['OR',
                ['between', 'value.new', $start, $end],
                ['between', 'value.supporting', $start, $end],
                ['between', 'value.supported', $start, $end],
                ['between', 'value.cancelled', $start, $end],
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
                    'uuid' => '$uuid'
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
