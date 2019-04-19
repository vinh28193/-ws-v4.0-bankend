<?php


namespace frontend\assets;

use yii\web\AssetBundle;

class FontawesomeAsset extends AssetBundle
{

    public $js = [
        'https://use.fontawesome.com/releases/v5.8.1/js/all.js'
    ];

    public $css = [
        'https://use.fontawesome.com/releases/v5.8.1/css/all.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
}