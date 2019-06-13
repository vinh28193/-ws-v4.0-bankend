<?php


namespace frontend\modules\payment\providers\alepay;

use yii\web\AssetBundle;

class AlepayAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/ws.alepay.js'
    ];

    public $css = [

    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
        'frontend\modules\payment\PaymentAssets',
    ];
}