<?php

namespace common\components\cart\storage;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for collection "shopping_cart".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $key
 * @property mixed $identity
 * @property mixed $data
 * @property mixed $created_at
 */
class MongodbCartStorage extends \yii\mongodb\ActiveRecord implements CartStorageInterface
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['weshop_global', 'shopping_cart'];
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
        $query = self::find()->where(['AND', ['key' => $key], ['identity' => $id]]);
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
        $model->created_at = time();
        return $model->save(false);
    }

    /**
     * @param $key
     * @param $value
     * @return boolean
     */
    public function setItem($key, $value)
    {
        /** @var $item $this */
        if (($item = $this->getItem($key)) === false) {
            return $this->setItem($key, $value);
        }
        $item->data = $value;
        return $item->save(false);

    }

    /**
     * @param $key
     * @return boolean|mixed
     */
    public function getItem($key)
    {
        list($key, $id) = $key;
        $query = self::find()->where(['AND', ['key' => $key], ['identity' => $id]]);
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
        return self::deleteAll(['AND', ['key' => $key], ['identity' => $id]]);
    }

    /**
     * @param $identity
     * @return array
     */
    public function getItems($identity)
    {
        $items = self::find()->where( ['identity' => $identity])->all();
        return ArrayHelper::map($items,'key','data');

    }

    /**
     * @param $identity
     * @return int
     * @throws \yii\mongodb\Exception
     */
    public function countItems($identity)
    {
        $query = self::find()->select(['key'])->where( ['identity' => $identity]);
        return $query->count('key');
    }

    /**
     * @param $identity
     * @return boolean
     */
    public function removeItems($identity)
    {
        return self::deleteAll(['identity' => $identity]);
    }
}
