<?php


namespace common\assets;


use yii\web\AssetBundle;

class WeshopAsset extends AssetBundle
{
    public $sourcePath = '@common/assets/src';

    public $js = [
        'js/ws.js',
        'js/ws.browse.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}