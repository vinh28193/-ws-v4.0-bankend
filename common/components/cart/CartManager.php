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
        if(get_class($this->storage) === 'common\components\cart\storage\MongodbCartStorage'){
            if(get_class($this->serializer) !== 'common\components\cart\serialize\NoneSerialize'){
                throw new InvalidConfigException("common\components\cart\storage\MongodbCartStorage only use common\components\cart\serialize\NoneSerialize");
            }
        }
    }

    /**
     * @param $key
     * @return array
     * @throws \Throwable
     */
    public function buildPrimaryKey($key)
    {
        if ($key === null) {
            $key = false;
        }
        return [$key, $this->getUser()->getId()];
    }

    /**
     * @param $sku
     * @param null $parentSku
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function hasItem($sku, $parentSku = null)
    {
        $key = $this->normalPrimaryKey($sku, $parentSku);
        return $this->getStorage()->hasItem($key);
    }

    /**
     * @param $sku
     * @param null $parentSku
     * @return bool|mixed
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function getItem($sku, $parentSku = null)
    {
        $key = $this->normalPrimaryKey($sku, $parentSku);
        if (($value = $this->getStorage()->getItem($key)) !== false) {
            return $this->getSerializer()->unserialize($value);
        }
        return false;
    }

    /**
     * @param $sku
     * @param $seller
     * @param $quantity
     * @param $source
     * @param $image
     * @param null $parentSku
     * @return bool
     * @throws \Throwable
     */
    public function addItem($sku, $seller, $quantity, $source, $image, $parentSku = null)
    {
        $key = $this->normalPrimaryKey($sku, $parentSku);
        try {
            if ($this->hasItem($sku, $parentSku)) {
                if (($item = $this->getItem($sku, $parentSku)) === false) {
                    return false;
                }
                if(!is_object($item)){
                    $item = new SimpleItem($item);
                }
                // pass new param for CartItem
                $item->quantity += 1;
                $item = $item->process();
                $item = $this->getSerializer()->serializer($item);
                $this->getStorage()->setItem($key, $item);
                return true;
            } else {
                $item = new SimpleItem(['sku' => $sku, 'parentSku' => $parentSku, 'quantity' => $quantity, 'seller' => $seller, 'source' => $source, 'image' => $image]);
                $item = $item->process();
                $item = $this->getSerializer()->serializer($item);
                $this->getStorage()->addItem($key, $item);
                return true;
            }
        } catch (\Exception $exception) {
            Yii::info($exception);

            return false;
        }
    }

    public function updateItem($sku, $seller = null, $quantity = 1, $image, $parentSku = null)
    {
        $key = $this->normalPrimaryKey($sku, $parentSku);

        try {
            if ($this->hasItem($sku, $parentSku)) {
                if (($item = $this->getItem($sku, $parentSku)) === false) {
                    return false;
                }
                if ($seller !== null && !$this->compareValue($item->seller, $seller, 'string')) {
                    $item->seller = $seller;
                }
                if ($quantity !== null && !$this->compareValue($item->quantity, $quantity, 'int')) {
                    $item->quantity = $quantity;
                }
                if ($image !== null && !$this->compareValue($item->image, $image, 'string')) {
                    $item->image = $image;
                }
                $item = $item->process();
                $item = $this->getSerializer()->serializer($item);
                $this->getStorage()->setItem($key, $item);
                return true;
            }else{
                return false;
            }

        } catch (\Exception $exception) {
            Yii::info($exception);
            return false;
        }
    }

    public function removeItem($sku, $parentSku = null)
    {

        if ($this->hasItem($sku, $parentSku)) {
            $key = $this->normalPrimaryKey($sku, $parentSku);
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
     * @param $sku
     * @param null $parentSku
     * @return array
     * @throws \Throwable
     */
    private function normalPrimaryKey($sku, $parentSku = null)
    {
        $key = $sku;
        if ($parentSku !== null) {
            $key .= "-$parentSku";
        }
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