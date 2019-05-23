<?php


namespace frontend\widgets\item;

use yii\web\AssetBundle;

class ItemDetailAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/client.min.js',
        'js/details-ebay-slider.js',
        'js/ws.item.js',
    ];

    public $css = [
       'css/next.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
        'frontend\assets\FrontendAsset',
    ];
}
