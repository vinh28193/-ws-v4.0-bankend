<?php


namespace frontend\assets;

use yii\web\AssetBundle;

class SearchAsset extends AssetBundle
{

    public $js = [

    ];

    public $css = [

    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'frontend\assets\FrontendAsset'
    ];
}