<?php

namespace common\components\cart\storage;

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
    public $collection = ['weshop_global_40', 'shopping_cart'];


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
     * @param $key
     * @param $value
     * @param $identity
     * @return \MongoDB\BSON\ObjectID
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\mongodb\Exception
     */
    public function addItem($key, $value, $identity)
    {
        return $this->mongodb->getCollection($this->collection)->insert([
            'remove' => 0,
            'key' => $key,
            'value' => $value,
            'identity' => $identity,
            'create_at' => Yii::$app->getFormatter()->asDatetime('now'),
        ]);
    }

    /**
     * @param $id
     * @param $value
     * @return bool|int
     * @throws \yii\mongodb\Exception
     */
    public function setItem($id, $value)
    {
        return $this->mongodb->getCollection($this->collection)->update([
            'AND', ['_id' => $id], ['remove' => 0]
        ], $value);
    }

    /**
     * @param $id
     * @param $key
     * @param null $identity
     * @return array
     */
    public function getItem($id, $key, $identity = null)
    {
        $conditions = ['AND', ['_id' => $id], ['remove' => 0], ['key' => ['$elemMatch' => $key]]];
        if ($identity !== null) {
            $conditions[] = ['identity' => $identity];
        }
        $query = new Query();
        $query->from($this->collection);
        $query->where($conditions);
        return $query->all($this->mongodb);
    }

    public function filterItem($filter, $identity = null)
    {
        $conditions = ['AND', ['remove' => 0], ['key' => ['$elemMatch' => $filter]]];
        if ($identity !== null) {
            $conditions[] = ['identity' => $identity];
        }
        $query = new Query();
        $query->from($this->collection);
        $query->where($conditions);
        return $query->one($this->mongodb);
    }

    /**
     * @param $id
     * @param $key
     * @param null $identity
     * @return bool|int
     * @throws \yii\mongodb\Exception
     */
    public function removeItem($id, $key, $identity = null)
    {
        $conditions = ['AND', ['_id' => $id], ['remove' => 0], ['key' => ['$elemMatch' => $key]]];
        if ($identity !== null) {
            $conditions[] = ['identity' => $identity];
        }
        return $this->mongodb->getCollection($this->collection)->update($conditions, ['remove' => 1]);
    }

    /**
     * @param $identity
     * @param |null $keys
     * @return array|mixed
     */
    public function getItems($identity, $ids = null)
    {
        $conditions = ['AND', ['identity' => $identity], ['remove' => 0]];
        if ($ids !== null) {
            $conditions[] = ['IN', '_id', !is_array($ids) ? [$ids] : $ids];
        }

        $query = new Query();
        $query->from($this->collection);
        $query->where($conditions);
        return $query->all($this->mongodb);
    }

    /**
     * @param $identity
     * @return int
     * @throws \yii\mongodb\Exception
     */
    public function countItems($identity)
    {
        $query = new Query();
        $query->from($this->collection);
        $query->where(['AND', ['identity' => $identity], ['remove' => 0]]);
        return $query->count($this->mongodb);

    }

    /**
     * @param $identity
     * @param null $ids
     * @return bool|int
     * @throws \yii\mongodb\Exception
     */
    public function removeItems($identity, $ids = null)
    {
        $conditions = ['AND', ['identity' => $identity], ['remove' => 0]];
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
}
