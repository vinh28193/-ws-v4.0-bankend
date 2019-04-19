<?php


namespace frontend\assets;

use yii\web\AssetBundle;

class FontawesomeAsset extends AssetBundle
{

    public $sourcePath = '@bower/fontawesome';

    public $js = [
        'css/fontawesome.js'
    ];

    public $css = [
        'js/fontawesome.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}