<?php


namespace frontend\assets;


use Yii;
use yii\helpers\Json;
use yii\web\AssetBundle;
use yii\web\View;

class ClientJsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/client.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];

}