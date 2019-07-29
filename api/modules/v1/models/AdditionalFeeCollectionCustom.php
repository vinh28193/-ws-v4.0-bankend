<?php

namespace api\modules\v1\models;

use common\additional\AdditionalFeeCollection;
use common\components\ExchangeRate;
use Yii;

class AdditionalFeeCollectionCustom extends AdditionalFeeCollection
{

    public $storeId;

    public $userId;


    /**
     * @var \common\components\StoreManager
     */
    private $_storeManager;

    /**
     * fix always load for storeManager
     * @inheritDoc
     */
    public function getStoreManager()
    {
        if ($this->_storeManager === null) {
            $this->_storeManager = parent::getStoreManager();
            if ($this->_storeManager->getId() !== (int)$this->storeId) {
                $this->_storeManager->setStore($this->storeId);
            }
            $this->_storeManager->setExchangeRate($this->getExRate());
        }
        return $this->_storeManager;
    }

    private $_exRate;

    public function getExRate()
    {
        if ($this->_exRate === null) {
            /** @var  $exRate ExchangeRate */
            $exRate = Yii::$app->exRate;
            if ($this->userId !== null && $this->userId !== '') {
                $exRate->usedIdentity = false;
                $exRate->setUser($this->userId);
            }
            $this->_exRate = $exRate->load('USD', $this->_storeManager->store->currency);
        }
        return $this->_exRate;
    }

    public function setExRate($rate)
    {
        $this->_exRate = $rate;
    }
}