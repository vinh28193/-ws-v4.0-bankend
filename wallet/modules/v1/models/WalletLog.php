<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 12/05/2018
 * Time: 08:52 AM
 */

namespace wallet\modules\v1\models;


class WalletLog extends \common\models\db\WalletLog
{
    //type wallet
    const TYPE_WALLET_CLIENT = 'CLIENT';
    const TYPE_WALLET_MERCHANT  = 'MERCHANT';

    //TypeTransaction
    const TYPE_TRANSACTION_TOPUP  = 'TOPUP';
    const TYPE_TRANSACTION_REFUN  = 'REFUN';
    const TYPE_TRANSACTION_WITHDRAW  = 'WITHDRAW';
    const TYPE_TRANSACTION_FREEZE  = 'FREEZE';
    const TYPE_TRANSACTION_UNFREEZEADD  = 'UNFREEZEADD';
    const TYPE_TRANSACTION_UNFREEZEREDUCE   = 'UNFREEZEREDUCE';
    const TYPE_TRANSACTION_PAYMENT   = 'PAYMENT';


    const TYPE_TRANSACTION_MERCHANTADD  = 'MERCHANTADD';
    const TYPE_TRANSACTION_MERCHANTREDUCE   = 'MERCHANTREDUCE';
}