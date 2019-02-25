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
     * @var  \common\components\StoreManager|void
     */
    private $_storeManager;

    /**
     * @return \common\components\StoreManager|void
     */
    public function getStoreManager()
    {
        if ($this->_storeManager === null) {
            $app = Yii::$app;
            if ($app instanceof \yii\console\Application) {
                $app->storeManager->setStore(1);
            }
            $this->_storeManager = $app;

        }
        return $this->_storeManager;
    }

    /**
     * @var
     */
    private $_storeAdditionalFee = [];

    /**
     * @throws InvalidConfigException
     */
    public function getStoreAdditionalFee()
    {
        if (empty($this->_storeAdditionalFee)) {
            $this->_storeAdditionalFee = [];
            /** @var  $store \yii\db\ActiveRecord */
            $store = $this->getStoreManager()->store;
            $key = 'storeAdditionalFee';
            if ($store->isRelationPopulated($key)) {
                $this->_storeAdditionalFee = $store->$key;
            }
        }
        return $this->_storeAdditionalFee;
    }
}