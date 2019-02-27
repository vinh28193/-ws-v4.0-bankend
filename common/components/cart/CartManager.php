<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-26
 * Time: 14:36
 */

namespace common\components\cart;

use Yii;

class CartManager extends \yii\base\Component
{
    public $serializer = 'common\components\cart\CartSerializer';

    /**
     * @return \common\components\cart\CartSerializer
     * @throws \yii\base\InvalidConfigException
     */
    public function getSerializer()
    {
        return Yii::createObject($this->serializer);
    }

    /**
     * @var string | \common\components\cart\storage\CartStorageInterface
     */
    public $storage = 'common\components\cart\storage\SessionCartStorage';

    /**
     * @return \stdClass
     */
    public function getUser()
    {
        $user = new \stdClass();
        $user->id = 123;
        return $user;
    }

    public function getStorage()
    {
        if (!is_object($this->storage)) {
            $this->storage = Yii::createObject($this->storage);
        }
        return $this->storage;
    }

    public function init()
    {
        parent::init();
    }

    public function buildPrimaryKey($key)
    {
        if ($key === null) {
            $key = false;
        }
        return [$key, $this->getUser()->id];
    }

    public function hasItem($sku,$parentSku = null)
    {
        $key = $sku;
        if ($parentSku !== null) {
            $key .= "-$parentSku";
        }
        $key = $this->buildPrimaryKey($key);
        return $this->getStorage()->hasItem($key);
    }

    /**
     * @param $sku
     * @param null $parentSku
     * @return boolean | CartItem
     * @throws \yii\base\InvalidConfigException
     */
    public function getItem($sku, $parentSku = null)
    {
        $key = $sku;
        if ($parentSku !== null) {
            $key .= "-$parentSku";
        }
        $key = $this->buildPrimaryKey($key);
        if (!($value = $this->getStorage()->getItem($key))) {
            return $this->getSerializer()->unserialize($value);
        }
        return false;
    }

    public function addItem($sku, $seller, $quantity, $source, $images, $parentSku = null)
    {
        $key = $sku;
        if ($parentSku !== null) {
            $key .= "-$parentSku";
        }

        $key = $this->buildPrimaryKey($key);

        try {
            if ($this->getStorage()->hasItem($key)) {
                if (!($item = $this->getItem($sku, $parentSku))) {
                    return false;
                }
                // pass new param for CartItem
                $item->quantity += 1;
                //$item = $item->process();
                $item = $this->getSerializer()->serializer($item);
                $this->getStorage()->setItem($key, $item);
                return true;
            } else {
                $item = new CartItem(['sku' => $sku, 'parentSku' => $parentSku, 'quantity' => $quantity, 'seller' => $seller, 'source' => $source, 'images' => $images]);
                //$item = $item->process();
                $item = $this->getSerializer()->serializer($item);
                $this->getStorage()->addItem($key, $item);
                return true;
            }
        } catch (\Exception $exception) {
            Yii::info($exception);
            return false;
        }
    }

    public function removeItem($sku, $parentSku = null)
    {
        $key = $sku;
        if ($parentSku !== null) {
            $key .= "-$parentSku";
        }

        $key = $this->buildPrimaryKey($key);
        if ($this->hasItem($key)) {
            return $this->getStorage()->removeItem($key);
        }
        return false;

    }

    public function getItems(){
        $items = $this->getStorage()->getItems($this->getUser()->id);
        $results = [];
        foreach ($items as $key => $item){
            $results[$key] = $this->getSerializer()->unserialize($item);
        }
        return $results;
    }

    public function countItems(){
        return $this->getStorage()->countItems($this->getUser()->id);
    }

    public function removeItems(){
        return $this->getStorage()->removeItems($this->getUser()->id);
    }
}