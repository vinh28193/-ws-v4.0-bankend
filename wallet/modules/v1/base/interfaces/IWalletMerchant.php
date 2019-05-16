<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 4/28/2018
 * Time: 9:44 AM
 */

namespace wallet\modules\v1\base\interfaces;


interface IWalletMerchant
{
    public function getWallet();

    public function checkBalance();

    public function createTransferMoney();
}