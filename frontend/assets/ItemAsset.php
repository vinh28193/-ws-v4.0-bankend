<?php


namespace frontend\assets;

use yii\web\AssetBundle;

class ItemAsset extends AssetBundle
{

    public $js = [

    ];

    public $css = [

    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
        'frontend\assets\FrontendAsset',
        'frontend\assets\FancyboxPlusAsset',
    ];
}