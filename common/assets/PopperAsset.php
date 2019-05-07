<?php


namespace common\assets;

use yii\web\AssetBundle;

class PopperAsset extends AssetBundle
{
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.15.0/esm/popper.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];

}