<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-26
 * Time: 09:17
 */

namespace common\actions;

use Yii;
use common\components\db\ActiveQuery;
use common\models\SystemCountry;
use yii\di\Instance;

/**
 *
 * Class SystemLocationAction
 * @package common\actions
 */
class SystemLocationAction extends \yii\base\Action
{

    /**
     * cache duration(s)
     */
    const CACHE_DURATION = 3600;

    /**@var \yii\caching\CacheInterface|array|string */
    public $cache = 'cache';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->cache = Instance::ensure($this->cache, 'yii\caching\CacheInterface');
    }

    /**
     * @return array
     */
    public function run()
    {
        // Toto refresh
        $refresh = false;
        if (($data = $this->cache->get($this->getCacheKey())) === false || $refresh) {
            $query = SystemCountry::find();
            $query->select([
                $query->getColumnName('id'),
                $query->getColumnName('name'),
                $query->getColumnName('country_code'),
                $query->getColumnName('language'),
            ]);
            $query->with(['systemStateProvinces' => function (ActiveQuery $q1) {
                $q1->select([
                    $q1->getColumnName('id'),
                    $q1->getColumnName('country_id'),
                    $q1->getColumnName('name'),
                    $q1->getColumnName('name_local'),
                    $q1->getColumnName('name_alias'),
                ]);
                $q1->remove();
                $q1->with(['systemDistricts' => function (ActiveQuery $q2) {
                    $q2->select([
                        $q2->getColumnName('id'),
                        $q2->getColumnName('province_id'),
                        $q2->getColumnName('name'),
                        $q2->getColumnName('name_local'),
                        $q2->getColumnName('name_alias'),
                    ]);
                    $q2->remove();
                    // With Systeam Zipcode here
                }]);
            }]);
            $this->cache->set($this->getCacheKey(), $data = $query->all(), self::CACHE_DURATION);
        }
        $response = Yii::$app->getResponse();
        $response->setStatusCode(201);
//        $response->getHeaders()->add('Cache-Control')
        return $data;
    }

    /**
     * cache key
     * @return array
     */
    protected function getCacheKey()
    {
        return [
            __CLASS__,
            $this->getUniqueId()
        ];
    }
}