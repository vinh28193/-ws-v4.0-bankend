<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-16
 * Time: 09:21
 */

namespace common\components;

use Yii;
use yii\base\InvalidConfigException;

/**
 * Trait AdditionalFeeRegisterTrait
 * @package ws\base
 * @property \common\models\StoreAdditionalFee[] $storeAdditionalFee
 */
trait StoreAdditionalFeeRegisterTrait
{

    /**
     * @var string
     */
    public $storeManager = 'storeManager';

    /**
     * @var
     */
    private $_storeAdditionalFee = [];

    /**
     * @throws InvalidConfigException
     */
    public function getStoreAdditionalFee()
    {
        if (empty($this->_storeAdditionalFee) && ($app = Yii::$app) && $app->has($this->storeManager)) {
            $this->_storeAdditionalFee = [];
            $this->storeManager = $app->get($this->storeManager);
            /** @var  $store \yii\db\ActiveRecord */
            $store = $this->storeManager->store;
            $key = 'storeAdditionalFee';
            if ($store->isRelationPopulated($key)) {
                $this->_storeAdditionalFee = $store->$key;
            }
        }
        return $this->_storeAdditionalFee;
    }
}