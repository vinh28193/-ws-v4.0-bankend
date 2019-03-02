<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-28
 * Time: 10:34
 */

namespace weshop\payment;


class PaymentAssets extends \yii\web\AssetBundle
{

    public $sourcePath = '@weshop/payment/assets';
    public $css = [

    ];
    public $js = [
        'ws.payment.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}