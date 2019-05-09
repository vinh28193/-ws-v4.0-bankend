<?php


namespace frontend\widgets\search;

use yii\web\AssetBundle;

class SearchAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/ws.search.js'
    ];

    public $css = [

    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
        'frontend\assets\FrontendAsset',
    ];
}