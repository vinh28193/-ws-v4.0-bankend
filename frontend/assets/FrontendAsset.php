<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class FrontendAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css',
        'css/variables.css',
//        'css/mobile_style.css'
    ];
    public $js = [
        'js/style.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'common\assets\JQueryEzPlus',
        'common\assets\FontawesomeAsset',
        'common\assets\OwlCarousel',
        'common\assets\SlickCarouselAsset',
        'common\assets\WeshopAsset',
        'frontend\assets\JQueryLazy',
        'frontend\assets\FancyboxPlusAsset'
    ];
}
