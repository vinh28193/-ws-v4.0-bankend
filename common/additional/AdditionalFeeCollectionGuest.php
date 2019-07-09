<?php


namespace common\additional;


use common\components\ExchangeRate;
use Yii;

class AdditionalFeeCollectionGuest extends AdditionalFeeCollection
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
        if($this->_storeManager === null){
            $this->_storeManager = parent::getStoreManager();
            if ($this->_storeManager->getId() !== (int)$this->storeId) {
                $this->_storeManager->setStore($this->storeId);
            }
            if ($this->userId !== null && $this->userId !== '') {
                /** @var  $exRate ExchangeRate */
                $exRate = Yii::$app->exRate;
                $exRate->usedIdentity = false;
                $exRate->setUser($this->userId);
                $rate = $exRate->load('USD', $this->_storeManager->store->currency);
                $this->_storeManager->setExchangeRate($rate);
            }
        }
        return $this->_storeManager;
    }
}