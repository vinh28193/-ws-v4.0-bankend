<?php


namespace common\assets;

use yii\web\AssetBundle;

class SlickCarouselAsset extends AssetBundle
{
    public $sourcePath = '@bower/slick-carousel/slick';

    public $js = [
        'slick.js',
    ];
    public $css = [
        'slick.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
}
