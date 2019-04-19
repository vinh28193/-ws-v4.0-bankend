<?php


namespace frontend\assets;

use yii\web\AssetBundle;

class JQueryEzPlus extends AssetBundle
{
    public $sourcePath = '@bower/ez-plus/src';

    public $js = [
        'jquery.ez-plus.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}