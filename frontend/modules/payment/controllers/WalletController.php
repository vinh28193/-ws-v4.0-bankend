<?php


namespace frontend\modules\payment\controllers;


use frontend\modules\payment\providers\wallet\WalletService;

class WalletController extends BasePaymentController
{

    private $_walletClient;

    public function getWalletClient()
    {
        if (!is_object($this->_walletClient) && $this->getIsGuest() === false) {
            $this->_walletClient = (new WalletService())->getWalletClient();
        }
        return $this->_walletClient;
    }

    public function getIsGuest()
    {
        return (new WalletService())->isGuest();
    }
}