<?php

namespace common\components\cart\storage;

use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "shopping_cart".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $key
 * @property mixed $identity
 * @property mixed $data
 * @property mixed $remove
 * @property mixed $created_at
 */
class MongodbCartStorage extends ActiveRecord implements CartStorageInterface
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['weshop_global_40', 'shopping_cart'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'key',
            'identity',
            'data',
            'remove',
            'created_at',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key', 'identity', 'data', 'created_at'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'key' => 'Key',
            'identity' => 'Identity',
            'data' => 'Data',
            'remove' => 'Remove',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @param $key
     * @return bool
     * @throws \yii\mongodb\Exception
     */
    public function hasItem($key)
    {
        list($key, $id) = $key;
        $query = self::find()->where(['AND', ['key' => $key], ['identity' => $id], ['remove' => 0]]);
        return $query->count() > 0;
    }

    /**
     * @param $key
     * @param $value
     * @return boolean
     */
    public function addItem($key, $value)
    {
        list($key, $id) = $key;
        $model = new self();
        $model->key = $key;
        $model->identity = $id;
        $model->data = $value;
        $model->remove = 0;
        $model->created_at = time();
        $model->save(false);
        return $key;
    }

    /**
     * @param $key
     * @param $value
     * @return boolean
     */
    public function setItem($key, $value)
    {
        list($key2, $id) = $key;
        /** @var $item $this */
        $query = self::find()->where(['AND', ['key' => $key2], ['identity' => $id], ['remove' => 0]]);
        /** @var $model $this */
        if (($model = $query->one()) === null) {
            return $this->addItem($key, $value);
        }
        $value['key'] = $key2;
        $model->data = $value;
        $model->save(false);
        return $key2;

    }

    /**
     * @param $key
     * @return boolean|mixed
     */
    public function getItem($key)
    {
        list($key, $id) = $key;
        $query = self::find()->where(['AND', ['key' => $key], ['identity' => $id], ['remove' => 0]]);
        /** @var $model $this */
        if (($model = $query->one()) === null) {
            return false;
        }
        return $model->data;
    }

    /**
     * @param $key
     * @return boolean|mixed
     */
    public function removeItem($key)
    {
        list($key, $id) = $key;
        return self::updateAll(['remove' => 1], ['AND', ['key' => $key], ['identity' => $id]]);
    }

    /**
     * @param $identity
     * @param |null $keys
     * @return array|mixed
     */
    public function getItems($identity, $keys = null)
    {
        $conditions = ['AND', ['identity' => $identity], ['remove' => 0]];
        if ($keys !== null) {
            $conditions[] = ['IN', 'key', !is_array($keys) ? [$keys] : $keys];
        }
        return ArrayHelper::map(self::find()->where($conditions)->all(), 'key', 'data');
    }

    /**
     * @param $identity
     * @return int
     * @throws \yii\mongodb\Exception
     */
    public function countItems($identity)
    {
        $query = self::find()->select(['key'])->where(['AND', ['identity' => $identity], ['remove' => 0]]);
        return $query->count('key');
    }

    /**
     * @param $identity
     * @param null $keys
     * @return int
     */
    public function removeItems($identity, $keys = null)
    {

        if ($keys !== null) {
            $conditions = ['AND', ['IN', 'key', !is_array($keys) ? [$keys] : $keys], ['identity' => $identity]];
        } else {
            $conditions = ['identity' => $identity];
        }
        return self::updateAll(['remove' => 1], $conditions);
    }

    /**
     * @param $key
     * @return bool
     */
    public function setMeOwnerItem($key)
    {
        list($key2, $id) = $key;
        if (($item = self::find()->where(['AND', ['key' => $key2], ['remove' => 0]])->one()) === null) {
            return false;
        }
        return $item->updateAttributes(['identity' => $id]) > 0;
    }

    public function keys($identity)
    {
        $query = new \yii\mongodb\Query();
        $query->from(self::collectionName())->select(['key'])->where(['AND', ['identity' => $identity], ['remove' => 0]]);
        return $query->column();
    }
}
