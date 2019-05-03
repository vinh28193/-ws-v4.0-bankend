<?php


namespace frontend\assets;

use yii\web\AssetBundle;

class ItemAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/ws.item.js'
    ];

    public $css = [

    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
        'frontend\assets\FrontendAsset',
        'frontend\assets\FancyboxPlusAsset',
    ];
}