<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 5/8/2018
 * Time: 9:10 AM
 */

namespace wallet\modules\v1\models;


use wallet\modules\v1\base\interfaces\IWalletMerchant;

class WalletMerchant extends \common\models\db\WalletMerchant implements IWalletMerchant
{

    const WALEET_MERCHAN_ID_ESC_PRO = 1;
    const WALEET_MERCHAN_ID_ESC_DEV = 4;

    public function getWallet()
    {
        // TODO: Implement getWallet() method.
    }

    public function checkBalance()
    {
        // TODO: Implement checkBalance() method.
    }

    public function createTransferMoney()
    {
        // TODO: Implement createTransferMoney() method.
    }
}