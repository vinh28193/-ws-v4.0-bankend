<?php


namespace frontend\modules\checkout;

use yii\web\AssetBundle;

class PaymentAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/ws.payment.js'
    ];

    public $css = [

    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
        'frontend\assets\WeshopAsset',
    ];
}