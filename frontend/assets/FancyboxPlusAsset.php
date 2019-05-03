<?php


namespace frontend\assets;

use yii\web\AssetBundle;

class FancyboxPlusAsset extends AssetBundle
{
    public $js = [
        'https://cdn.rawgit.com/igorlino/fancybox-plus/1.3.6/src/jquery.fancybox-plus.js',
    ];
    public $css = [
        'https://cdn.rawgit.com/igorlino/fancybox-plus/1.3.6/css/jquery.fancybox-plus.css'
    ];
    public $depends = [
        'frontend\assets\FrontendAsset'
    ];

}