<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-06-04
 * Time: 14:57
 */

namespace wallet\modules\v1\helpers;


use wallet\modules\v1\models\WalletTransaction;
use yii\base\InvalidConfigException;

class WalletHelper
{

    public static function generateOtpCode()
    {
        return (string)rand(10000, 99999);
    }

    public static function generateWalletTransactionCode($prefix)
    {
        return $prefix . rand(50, 99) . time();
    }

    public static function generateTransactionDescription($transaction)
    {
        if(!$transaction instanceof \wallet\modules\v1\models\WalletTransaction){
            throw new InvalidConfigException(__METHOD__.'::$transaction must be instance of \wallet\modules\v1\models\WalletTransaction');
        }
        if($transaction->type == WalletTransaction::TYPE_TOP_UP){
            return 'Top up success amount:' . $transaction->totalAmount . ', payment method:' . $transaction->payment_method . ', payment provider' . $transaction->payment_provider_name;
        }elseif ($transaction->type == WalletTransaction::TYPE_PAY_ORDER){
            return 'Payment order:' . $transaction->order_number;
        }elseif ($transaction->type == WalletTransaction::TYPE_REFUND){
            return 'Refund order:' . $transaction->order_number;
        }
    }
}