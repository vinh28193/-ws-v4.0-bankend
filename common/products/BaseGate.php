<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-29
 * Time: 16:26
 */

namespace common\products;

use Yii;
use yii\httpclient\Client;

abstract class BaseGate extends \yii\base\BaseObject
{

    const MAX_CACHE_DURATION = 300;

    public $baseUrl = '';

    public $searchUrl = 'search';

    public $lookupUrl = 'product';

    /**
     * @var string|\yii\caching\CacheInterface
     */
    public $cache = 'cache';

    /**
     * @var Client;
     */
    private $_httpClient;

    /**
     * @return Client
     * @throws \yii\base\InvalidConfigException
     */
    public function getHttpClient()
    {
        if ($this->_httpClient === null) {
            $config = $this->_httpClient;
            $this->_httpClient = $this->createHttpClient($config);
        }
        return $this->_httpClient;
    }

    /**
     * @param $config
     * @return Client
     * @throws \yii\base\InvalidConfigException
     */
    public function createHttpClient($config)
    {
        if ($config === null || (is_array($config) && !isset($config['class']))) {
            $config['class'] = Client::className();
        };
        $config['baseUrl'] = $this->baseUrl;
        return Yii::createObject($config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->cache = Yii::$app->cache;
    }

    /**
     * @param $params
     * @param bool $refresh
     * @return mixed
     */
    abstract function search($params, $refresh = false);

    /**
     * @param $condition
     * @param bool $refresh
     * @return mixed
     */
    abstract function lookup($condition, $refresh = false);

    /**
     * @param $value
     * @return bool
     */
    public function isEmpty($value)
    {
        return \common\helpers\WeshopHelper::isEmpty($value);
    }

    public function isBanned($id)
    {
        return false;
    }
}