<?php


namespace common\components\cart;

use yii\base\BaseObject;

class BaseStorage extends BaseObject
{

    /**
     * @var CartManager
     */
    public $cartManager;

    public function __construct(CartManager $cartManager, $config = [])
    {
        parent::__construct($config);
        $this->cartManager = $cartManager;
    }

    public function getItem($type, $key, $child)
    {

    }

    public function getChildItem($type, $key)
    {

    }

}