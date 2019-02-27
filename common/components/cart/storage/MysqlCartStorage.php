<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-26
 * Time: 15:04
 */

namespace common\components\cart\storage;

use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;

class MysqlCartStorage extends \yii\base\BaseObject implements CartStorageInterface
{

    /**
     * @var Connection|array|string
     */
    public $db = 'db';

    public $tableName = '{{%shopping_cart}}';

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());
    }

    public function hasItem($key)
    {
        list($key, $id) = $key;
        $query = new Query();
        $query->select(['COUNT(*)'])
            ->from($this->tableName)
            ->where('[[key]] = :key AND [[identity]] = :identity', [':key' => $key, ':identity' => $id]);
        $result = $query->createCommand($this->db)->queryScalar();
        return $result > 0;
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     * @throws \Throwable
     */
    public function setItem($key, $value)
    {
        return $this->db->noCache(function (Connection $db) use ($key, $value) {
            list($key, $id) = $key;
            return $db->createCommand()->upsert($this->tableName, [
                'key' => $key,
                'identity' => $id,
                'data' => $value
            ])->execute();
        });
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     * @throws \Throwable
     */
    public function addItem($key, $value)
    {
        return $this->db->noCache(function (Connection $db) use ($key, $value) {
            list($key, $id) = $key;
            return $db->createCommand()->insert($this->tableName, [
                'key' => $key,
                'identity' => $id,
                'data' => $value
            ])->execute();
        });
    }

    /**
     * @param $key
     * @return false|null|string
     * @throws \yii\db\Exception
     */
    public function getItem($key)
    {
        list($key, $id) = $key;
        $query = new Query();
        $query->select(['data'])
            ->from($this->tableName)
            ->where('[[identity]] = :identity AND [[key]] = :key', [':key' => $key, ':identity' => $id]);
        return $query->createCommand($this->db)->queryScalar();
    }

    /**
     * @param $key
     * @return bool
     * @throws \Throwable
     */
    public function removeItem($key)
    {
        return $this->db->noCache(function (Connection $db) use ($key) {
            list($key, $id) = $key;
            return $db->createCommand()
                ->delete($this->tableName, ['key' => $key, 'identity' => $id])
                ->execute();
        });
    }

    public function getItems($identity){
        $query = new Query();
        $query->select(['data'])
            ->from($this->tableName)
            ->where('[[identity]] = :identity ', [ ':identity' => $identity]);
        return $query->createCommand($this->db)->queryAll();
    }

    public function countItems($identity){
        $query = new Query();
        $query->select(['COUNT(*)'])
            ->from($this->tableName)
            ->where('[[identity]] = :identity', [':identity' => $identity]);
       return $query->createCommand($this->db)->queryScalar();
    }

    public function removeItems($identity){
        return $this->db->noCache(function (Connection $db) use ($identity) {
            return $db->createCommand()
                ->delete($this->tableName, ['identity' => $identity])
                ->execute();
        });
    }
}