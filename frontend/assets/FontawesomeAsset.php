<?php


namespace frontend\assets;

use yii\web\AssetBundle;

class FontawesomeAsset extends AssetBundle
{

    public $sourcePath = '@bower/fontawesome';

    public $js = [
        'js/fontawesome.js'
    ];

    public $css = [
        'css/fontawesome.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
}