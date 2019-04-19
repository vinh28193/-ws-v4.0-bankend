<?php


namespace common\assets;

use yii\web\AssetBundle;

class OwlCarousel extends AssetBundle
{
    public $sourcePath = '@bower/owl.carousel/dist';

    public $js = [
        'owl.carousel.js'
    ];
    public $css = [
        'assets/owl.carousel.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];

}