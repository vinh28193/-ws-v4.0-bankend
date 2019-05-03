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
        'css/variables.css'
    ];
    public $js = [
        'js/style.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'frontend\assets\JQueryEzPlus',
        'frontend\assets\FontawesomeAsset',
//        'frontend\assets\PopperAsset',
        'frontend\assets\OwlCarousel',
        'frontend\assets\SlickCarouselAsset',
        'frontend\assets\JQueryLazy'
    ];
}
