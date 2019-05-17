<?php


namespace common\payment;

use yii\web\AssetBundle;

class PaymentAssets extends AssetBundle
{
    public $sourcePath = '@common/payment/assets';

    public $js = [
        'js/ws.wallet.js',
        'js/ws.payment.js'
    ];

    public $css = [

    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
        'common\assets\WeshopAsset',
    ];
}