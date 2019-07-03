<?php


namespace landing;


use yii\web\AssetBundle;
use yii\web\View;

class WeshopOldAsset extends AssetBundle
{
    public $basePath = '@webroot/landing';
    public $baseUrl = '@web/landing';

    public $css = [
        'css/owl.carousel.css',
//        'css/common.css',
        'plugins/tags-input/src/jquery.tagsinput.css'
    ];

    public $js = [
        'js/jquery-1.11.3.js',
        'js/ajax.js',
        'js/utility.js',
        'js/urlcomponent.js',
        'js/textutils.js',
        'js/events.js',
        'js/crawl.js',
        'js/fly.js',
        'js/alias.js',
        'js/owl.carousel.js',
        'js/menu.js',
        'js/jquery.cookie.js',
        'plugins/tags-input/src/jquery.tagsinput.js',
        'js/lazyload.js'
    ];

    public $jsOptions = [
        'position' => View::POS_HEAD
    ];

    public $depends = [
//        'yii\web\JqueryAsset',
        'landing\BootstrapOldAsset',
    ];
}