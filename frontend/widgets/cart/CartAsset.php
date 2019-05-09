<?php


namespace frontend\widgets\cart;


use yii\web\AssetBundle;

class CartAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/ws.cart.js'
    ];

    public $css = [

    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
        'frontend\assets\FrontendAsset',
    ];
}