<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-16
 * Time: 09:21
 */

namespace common\additional;

use common\components\StoreManager;
use common\models\Store;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Trait AdditionalFeeRegisterTrait
 * @package ws\base
 * @property-read  StoreAdditionalFee[] $storeAdditionalFee
 * @property-read StoreManager $storeManager
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
            $this->_storeManager = $app->storeManager;

        }
        return $this->_storeManager;
    }

    /**
     * @var StoreAdditionalFee[]
     */
    private $_storeAdditionalFee = [];

    /**
     * @return array|StoreAdditionalFee[]
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