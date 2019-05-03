<?php


namespace frontend\widgets\imagelazy;

use yii\web\AssetBundle;

class ImageLazyLoadAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery.lazyload';
    public $js = [
        'jquery.lazyload.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
}