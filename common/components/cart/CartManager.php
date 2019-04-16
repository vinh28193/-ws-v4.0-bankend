<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-26
 * Time: 14:36
 */

namespace common\components\cart;

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
    public $serializer = 'common\components\cart\serialize\NoneSerialize';

    /**
     * get Serialize
     * @return BaseCartSerialize
     * @throws \yii\base\InvalidConfigException
     */
    public function getSerializer()
    {
        if (!is_object($this->serializer)) {
            $this->serializer = Yii::createObject($this->serializer);
            if (!$this->serializer instanceof BaseCartSerialize) {
                throw new InvalidConfigException(get_class($this->serializer) . " not instanceof common\components\cart\serialize\BaseCartSerialize");
            }
        }
        return $this->serializer;
    }

    /**
     * @var \yii\web\IdentityInterface
     */
    protected $user;

    /**
     * @return null|\yii\web\IdentityInterface
     * @throws \Throwable
     */
    public function getUser()
    {
        if ($this->user === null) {
            $this->user = Yii::$app->getUser()->getIdentity();
        }
        return $this->user;
    }

    /**
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function init()
    {
        parent::init();
        $this->getSerializer();
        $this->getStorage();
        $this->getUser();
        if (get_class($this->storage) === 'common\components\cart\storage\MongodbCartStorage') {
            if (get_class($this->serializer) !== 'common\components\cart\serialize\NoneSerialize') {
                throw new InvalidConfigException("common\components\cart\storage\MongodbCartStorage only use common\components\cart\serialize\NoneSerialize");
            }
        }
    }

    /**
     * @param $key
     * @return array
     * @throws \Throwable
     */
    private function buildPrimaryKey($key)
    {
        return [$key, $this->getUser()->getId()];
    }

    /**
     * @param $key
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function hasItem($key)
    {
        $key = $this->normalPrimaryKey($key);
        return $this->getStorage()->hasItem($key);
    }

    /**
     * @param $key
     * @return bool|mixed
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function getItem($key)
    {
        if (($value = $this->getStorage()->getItem($key)) !== false) {
            return $this->getSerializer()->unserialize($value);
        }
        return false;
    }

    /**
     * @param $params
     * @return bool
     * @throws \Throwable
     */
    public function addItem($params)
    {
        $key = $this->createKeyFormParams($params);
        $key = $this->normalPrimaryKey($key);
        try {
            if ($this->hasItem($key)) {
                if (($value = $this->getItem($key)) === false) {
                    return false;
                }
                $item = new SimpleItem();
                foreach ($params as $name => $val){
                    $item->$name = $val;
                }
                // Todo Validate data before call
                // pass new param for CartItem
                list($ok, $value) = $item->process();
                if (!$ok) {
                    return false;
                }
                $value = $this->getSerializer()->serializer($value);
                return $this->getStorage()->setItem($key, $value);
            } else {
                /** Todo : Thiếu link Gốc sản phẩm **/
                $item = new SimpleItem();
                foreach ($params as $name => $val){
                    $item->$name = $val;
                }
                // Todo Validate data before call
                list($ok, $value) = $item->process();
                if (!$ok) {
                    return false;
                }
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
       foreach (['seller', 'source','sku','parentSku'] as $k){
           if(!isset($params[$k]) || (isset($params[$k]) && ($params[$k] === null || $params[$k] === ''))){
               continue;
           }
           $keys[$k] = $params[$k];
       }
        return md5(json_encode($keys));
    }

    public function update($key, $params = [])
    {
        $key = $this->normalPrimaryKey($key);

        try {
            if ($this->hasItem($key)) {
                if (($item = $this->getItem($key)) === false) {
                    return false;
                }
                if (isset($params['seller']) !== null && ($seller = $params['seller']) !== null && !$this->compareValue($item->seller, $seller, 'string')) {
                    $item->seller = $seller;
                }
                if (isset($params['quantity']) !== null && ($quantity = $params['quantity']) !== null && !$this->compareValue($item->quantity, $quantity, 'int')) {
                    $item->quantity = $quantity;
                }
                if (isset($params['image']) !== null && ($image = $params['image']) !== null && !$this->compareValue($item->image, $image, 'string')) {
                    $item->image = $image;
                }
                $item = $item->process();
                $item = $this->getSerializer()->serializer($item);
                $this->getStorage()->setItem($key, $item);
                return true;
            } else {
                return false;
            }

        } catch (\Exception $exception) {
            Yii::info($exception);
            return false;
        }
    }

    public function removeItem($key)
    {

        if ($this->hasItem($key)) {
            return $this->getStorage()->removeItem($key);
        }
        return false;

    }

    public function getItems()
    {
        $items = $this->getStorage()->getItems($this->getUser()->id);
        $results = [];
        foreach ($items as $key => $item) {
            $results[$key] = $this->getSerializer()->unserialize($item);
        }
        return $results;
    }

    /**
     * @return int
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function countItems()
    {
        return $this->getStorage()->countItems($this->getUser()->getId());
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function removeItems()
    {
        return $this->getStorage()->removeItems($this->getUser()->getId());
    }

    /**
     * @param $key
     * @return array
     * @throws \Throwable
     */
    public function normalPrimaryKey($key)
    {
        return $this->buildPrimaryKey($key);
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
