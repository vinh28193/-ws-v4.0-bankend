<?php


namespace common\actions;


use common\models\Warehouse;
use yii\db\Query;
use yii\di\Instance;

class WarehouseAction extends \yii\base\Action
{

    /**
     * cache key(s)
     */
    const CACHE_KEY= 'WH_CACHE';

    /**
     * cache duration(s)
     */
    const CACHE_DURATION = 3600;

    /**@var \yii\caching\CacheInterface|string */
    public $cache = 'cache';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->cache = Instance::ensure($this->cache, 'yii\caching\CacheInterface');
    }

    public function run(){
        if(!($warehouses = $this->cache->get(self::CACHE_KEY))){
            $query = new Query();
            $query->from(['wh' => Warehouse::tableName()]);
            $query->select(['id','name', 'description', 'address', 'warehouse_group']);
            $warehouses = $query->all(Warehouse::getDb());
            $this->cache->set($warehouses,self::CACHE_KEY,self::CACHE_DURATION);
        }
        return $warehouses;
    }
}