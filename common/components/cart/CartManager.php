<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-26
 * Time: 14:36
 */

namespace common\components\cart;

use common\components\cart\storage\MongodbCartStorage;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use common\components\cart\item\SimpleItem;
use common\components\cart\serialize\BaseCartSerialize;
use common\components\cart\storage\CartStorageInterface;

/**
 * Class CartManager
 * @package common\components\cart
 *
 * [
 *      1 => [
 *         ebay:fun_shop => [
 *              sku => [
 *                   // item data
 *              ],
 *              sku:parentSku => [
 *                  // item data
 *             ]
 *          ],
 *          ebay:shop2 => [
 *
 *          ]
 *
 * ]
 */
class CartManager extends Component
{

    /**
     * @var string | \common\components\cart\storage\CartStorageInterface
     */
    public $storage = 'common\components\cart\storage\MongodbCartStorage';

    /**
     * @return CartStorageInterface|string
     * @throws InvalidConfigException
     */
    public function getStorage()
    {
        if (!is_object($this->storage)) {
            $this->storage = Yii::createObject($this->storage);
            if (!$this->storage instanceof CartStorageInterface) {
                throw new InvalidConfigException(get_class($this->storage) . " not instanceof common\components\cart\CartStorageInterface");
            }
        }
        return $this->storage;
    }

    /**
     * @var BaseCartSerialize
     */
    private $_serializer = 'common\components\cart\serialize\NoneSerialize';

    /**
     * get Serialize
     * @return BaseCartSerialize
     * @throws \yii\base\InvalidConfigException
     */
    public function getSerializer()
    {
        if (!is_object($this->_serializer)) {
            $this->_serializer = Yii::createObject($this->_serializer);
            if (!$this->_serializer instanceof BaseCartSerialize) {
                throw new InvalidConfigException(get_class($this->_serializer) . " not instanceof common\components\cart\serialize\BaseCartSerialize");
            }
        }
        return $this->_serializer;
    }

    /**
     * @var \yii\web\IdentityInterface
     */
    private $_user;

    /**
     * @return null|\yii\web\IdentityInterface
     * @throws \Throwable
     */
    public function getUser()
    {
        if (!is_object($this->_user)) {
            $this->_user = Yii::$app->getUser()->getIdentity();
        }
        return $this->_user;
    }

    public function setUser($user)
    {
        if ($user instanceof \yii\web\IdentityInterface) {
            $this->_user = $user;
        } elseif (is_string($user) || is_numeric($user)) {
            /** @var  $class \yii\web\IdentityInterface */
            $class = Yii::$app->getUser()->identityClass;
            $this->_user = $class::findIdentity($user);
        }
    }


    /**
     * @param $key
     * @param bool $safeOnly
     * @return array
     * @throws \Throwable
     */
    private function buildPrimaryKey($key, $safeOnly = true)
    {
        if (($user = $this->getUser()) === null && $safeOnly) {
            $safeOnly = false;
        }
        return [$key, $safeOnly ? $user->getId() : null];
    }

    /**
     * @param $key
     * @param bool $safeOnly
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function hasItem($key, $safeOnly = true)
    {
        $key = $this->normalPrimaryKey($key, $safeOnly);
        return $this->getStorage()->hasItem($key);
    }

    /**
     * @param $key
     * @param bool $safeOnly
     * @return bool|mixed
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function getItem($key, $safeOnly = true)
    {
        $key = $this->normalPrimaryKey($key, $safeOnly);
        if (($value = $this->getStorage()->getItem($key)) !== false) {
            return $this->getSerializer()->unserialize($value);
        }
        return false;
    }

    /**
     * @param $key
     * @return bool
     * @throws \Throwable
     */
    public function refreshItem($key)
    {
        try {
            if (($value = $this->getItem($key)) === false) {
                return false;
            }

            $item = new SimpleItem();
            $params = $value['request'];
            foreach ((array)$params as $name => $val) {
                if ($name === 'with_detail') {
                    continue;
                } elseif ($name === 'type') {
                    $name = 'source';
                } elseif ($name === 'id') {
                    $name = 'parentSku';
                }
                $item->$name = $val;
            }
            // Todo Validate data before call
            // pass new param for CartItem
            list($ok, $value) = $item->process();
            if (!$ok) {
                return false;
            }
            $value = $this->getSerializer()->serializer($value);
            $key = $this->normalPrimaryKey($key);
            return $this->getStorage()->setItem($key, $value);
        } catch (\Exception $exception) {
            Yii::info($exception);
            return false;
        }
    }

    /**
     *
     */
    public function refreshItems()
    {
        foreach (array_keys($this->getItems()) as $key) {
            $this->refreshItem($key);
        }
    }

    public function setMeOwnerItem($key)
    {
        if ($this->hasItem($key, false)) {
            $key = $this->normalPrimaryKey($key, true);
            return $this->storage->setMeOwnerItem($key);
        }
        return false;

    }

    /**
     * @param $params
     * @param bool $safeOnly
     * @return bool
     * @throws \Throwable
     */
    public function addItem($params, $safeOnly = true)
    {
        $key = $this->createKeyFormParams($params);

        try {
            if ($this->hasItem($key, $safeOnly)) {
                if (($value = $this->getItem($key, $safeOnly)) === false) {
                    return false;
                }
                $item = new SimpleItem();
                foreach ($params as $name => $val) {
                    $item->$name = $val;
                }
                // Todo Validate data before call
                // pass new param for CartItem
                list($ok, $value) = $item->process();
                if (!$ok) {
                    return false;
                }
                $value['key'] = $key;
                $key = $this->normalPrimaryKey($key, $safeOnly);
                $value = $this->getSerializer()->serializer($value);
                return $this->getStorage()->setItem($key, $value);
            } else {
                /** Todo : Thiếu link Gốc sản phẩm **/
                $item = new SimpleItem();
                foreach ($params as $name => $val) {
                    $item->$name = $val;
                }
                // Todo Validate data before call
                list($ok, $value) = $item->process();
                if (!$ok) {
                    return false;
                }
                $value['key'] = $key;
                $key = $this->normalPrimaryKey($key, $safeOnly);

                $value = $this->getSerializer()->serializer($value);
                return $this->getStorage()->addItem($key, $value);
            }
        } catch (\Exception $exception) {
            Yii::info($exception);
            return false;
        }
    }

    private function createKeyFormParams($params)
    {
        $keys = [];
        foreach (['seller', 'source', 'sku', 'parentSku'] as $k) {
            if (!isset($params[$k]) || (isset($params[$k]) && ($params[$k] === null || $params[$k] === ''))) {
                continue;
            }
            $keys[$k] = $params[$k];
        }
        return md5(json_encode($keys));
    }

    public function update($key, $params = [], $safeOnly = true)
    {
        try {
            if (($value = $this->getItem($key, $safeOnly)) === false) {
                return false;
            }
            $item = new SimpleItem();
            $oldParams = $value['params'];
            foreach ((array)$oldParams as $name => $val) {
                if ($name === 'with_detail') {
                    continue;
                } elseif ($name === 'type') {
                    $name = 'source';
                } elseif ($name === 'id') {
                    $name = 'parentSku';
                }
                $item->$name = $val;
            }

            if (isset($params['seller']) && ($seller = $params['seller']) !== null && !$this->compareValue($item->seller, $seller, 'string')) {
                $item->seller = $seller;
            }

            if (isset($params['quantity']) && ($quantity = $params['quantity']) !== null && !$this->compareValue($item->quantity, $quantity, 'int')) {
                $item->quantity = $quantity;
            }
            if (isset($params['image']) && ($image = $params['image']) !== null && !$this->compareValue($item->image, $image, 'string')) {
                $item->image = $image;
            }

            list($ok, $raw) = $item->process();
            if (!$ok) {
                return false;
            }
            $value['key'] = $key;
            $value = $this->getSerializer()->serializer($raw);
            $key = $this->normalPrimaryKey($key, $safeOnly);
            $this->getStorage()->setItem($key, $value);
            return $raw['order'];

        } catch (\Exception $exception) {
            Yii::info($exception);
            return false;
        }
    }

    public function removeItem($key)
    {
        $key = $this->normalPrimaryKey($key);
        if ($this->hasItem($key)) {
            return $this->getStorage()->removeItem($key);
        }
        return false;

    }

    public function getAllKeys($safeOnly = true)
    {
        if (($user = $this->getUser()) === null && $safeOnly) {
            $safeOnly = false;
        }
        return $this->getStorage()->keys($safeOnly ? $user->getId() : null);
    }

    public function getItems($keys = null, $safeOnly = true)
    {
        if (($user = $this->getUser()) === null && $safeOnly) {
            $safeOnly = false;
        }
        $items = $this->getStorage()->getItems($safeOnly ? $user->getId() : null);
        $results = [];
        foreach ($items as $key => $item) {
            $results[$key] = $this->getSerializer()->unserialize($item);
        }
        return $results;
    }

    public function getCartItems($safeOnly = true)
    {
        $model = new MongodbCartStorage();
        $items = $model->GetAllShopingCarts();
        return $items;
    }

    /**
     * @param bool $safeOnly
     * @return int
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function countItems($safeOnly = true)
    {
        if (($user = $this->getUser()) === null && $safeOnly) {
            $safeOnly = false;
        }
        return $this->getStorage()->countItems($safeOnly ? $user->getId() : null);
    }

    /**
     * @param bool $safeOnly
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function removeItems($safeOnly = true)
    {
        if (($user = $this->getUser()) === null && $safeOnly) {
            $safeOnly = false;
        }
        return $this->getStorage()->removeItems($safeOnly ? $user->getId() : null);
    }

    /**
     * @param $key
     * @param bool $safeOnly
     * @return array
     * @throws \Throwable
     */
    public function normalPrimaryKey($key, $safeOnly = true)
    {
        return $this->buildPrimaryKey($key, $safeOnly);
    }

    /**
     * @param $target
     * @param null $source
     * @param string $convertType
     * @return bool|mixed
     */
    private function compareValue($target, $source = null, $convertType = 'string')
    {
        return \common\helpers\WeshopHelper::compareValue($target, $source, $convertType, '===');
    }

}
