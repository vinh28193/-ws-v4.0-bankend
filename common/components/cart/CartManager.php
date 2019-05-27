<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-26
 * Time: 14:36
 */

namespace common\components\cart;

use common\components\cart\item\OrderCartItem;
use common\components\cart\storage\MongodbCartStorage;
use phpDocumentor\Reflection\Type;
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

    const TYPE_SHOPPING = 'shopping';
    const TYPE_BUY_NOW = 'buynow';
    const TYPE_SHIPPING = 'shipping';
    const TYPE_REQUEST = 'request';

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
     *  $key = [
     *      'type' => 'type',
     *      'primary' => 'asdasdad',
     *      'child' => [
     *          'asdasdasda'
     *      ]
     *  ]
     * @param $type
     * @param $primary
     * @param $child
     * @return array
     */
    private function buildKey($type, $primary, $child = null)
    {
        return [
            'type' => $type,
            'primary' => $primary,
            'child' => $child
        ];
    }

    /**
     * @param $type
     * @param $key
     * @param bool $safeOnly
     * @return mixed
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function hasItem($type, $key, $safeOnly = true)
    {
        return $this->getStorage()->hasItem($type, $key, $this->getIsSafe($safeOnly));
    }

    /**
     * @param $id
     * @param $key
     * @param bool $safeOnly
     * @return bool|mixed
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function getItem($id)
    {
        return $this->getStorage()->getItem($id);
    }


    public function setMeOwnerItem($id)
    {
        return $this->getStorage()->setMeOwnerItem($id);

    }

    public function filterItem($key, $safeOnly = true)
    {
        return $this->storage->filterItem($key, $this->getIsSafe($safeOnly));
    }

    /**
     * @param $type :shopping buynow ...
     * @param $params
     *      -type: ebay/amazon
     *      -seller: seller name
     *      -id: primary id of item
     *      -sku: sku of item (default null)
     *      -quantity: quantity
     *      -image: image
     * @param bool $safeOnly
     * @return array
     * @throws \Throwable
     */
    public function addItem($type, $params, $safeOnly = true)
    {
        $key = $this->createKeyFormParams($type, $params);
        try {
            if ($this->filterItem($key, $safeOnly)) {
                return [false, 'sản phẩm đã trong giỏ hàng'];
            } else {
                $item = new OrderCartItem();
                foreach ($params as $name => $val) {
                    $item->$name = $val;
                }
                $filter = $key;
                unset($filter['child']);
                if (($parent = $this->filterItem($filter, $this->getIsSafe())) === null) {
                    // Todo Validate data before call
                    list($ok, $order) = $item->process();
                    if (!$ok) {
                        return [false, $order];
                    }
                    return [$this->getStorage()->addItem($type, $params, $order, $this->getIsSafe($safeOnly));
                }


                // Todo Validate data before call
                list($ok, $order) = $item->process();
                if (!$ok) {
                    return [false, $order];
                }
                return [$this->getStorage()->addItem($type, $params, $order, $this->getIsSafe($safeOnly)), 'Thêm sản phẩm thành công'];
            }
        } catch (\Exception $exception) {
            Yii::info($exception);
            return [false, $exception->getMessage()];
        }
    }

    public function setItem($key, $params, $safeOnly = false)
    {
        if (!$this->hasItem($key, $safeOnly)) {
            return [false, 'sản phẩm không có trong giỏ hàng'];
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
    }


    public function update($key, $params = [], $safeOnly = true)
    {
        try {
            if (($value = $this->getItem($key, $safeOnly)) === false) {
                return [false, 'sản phẩm không có trong giỏ hàng'];
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
                return [false, 'không thể lấy thông tin sản phẩm'];
            }
            $value['key'] = $key;
            $value = $this->getSerializer()->serializer($raw);
            $key = $this->normalPrimaryKey($key, $safeOnly);
            $this->getStorage()->setItem($key, $value);
            return [false, 'sản phẩm không có trong giỏ hàng', $raw['order']];


        } catch (\Exception $exception) {
            Yii::info($exception);
            return [false, $exception->getMessage()];
        }
    }

    public function createKeyFormParams($type, $params = [])
    {
        $p = [];
        foreach (['source', 'seller'] as $k) {
            if (!isset($params[$k]) || (isset($params[$k]) && ($params[$k] === null || $params[$k] === ''))) {
                continue;
            }
            $p[$k] = "$k:{$params[$k]}";
        }
        $c = [];
        foreach (['id', 'sku'] as $g) {
            if (!isset($params[$g]) || (isset($params[$g]) && ($params[$g] === null || $params[$g] === ''))) {
                continue;
            }
            $c[$g] = "$g:{$params[$g]}";
        }
        return $this->buildKey($type, implode('|', $p), implode('|', $c));
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

    public function getCartItems($get)
    {
        $model = new MongodbCartStorage();
        $items = $model->GetAllShopingCarts($get);
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
    public function normalKey($key, $safeOnly = true)
    {
        return $this->buildPrimaryKey($key, $safeOnly);
    }

    /**
     * @param bool $force
     * @return int|string|null
     * @throws \Throwable
     */
    public function getIsSafe($force = false)
    {
        if (($user = $this->getUser()) !== null && $force) {
            return $user->getId();
        }
        return null;
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
