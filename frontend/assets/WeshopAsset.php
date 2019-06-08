<?php


namespace frontend\assets;

use Yii;
use yii\helpers\Json;
use yii\web\AssetBundle;
use yii\web\View;

class WeshopAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/ws.js',
        'js/ws.sweetalert.js',
        'js/ws.browse.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
        'frontend\assets\ClientJsAsset'
    ];

}