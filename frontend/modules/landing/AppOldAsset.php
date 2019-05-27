<?php


namespace landing;

use yii\web\AssetBundle;
use yii\web\View;

class AppOldAsset extends AssetBundle
{
    public $basePath = '@webroot/landing';
    public $baseUrl = '@web/landing';


    public $css = [
        'css/app.css', //all css website
        'css/fonts.css',
        'css/jquery-ui.css',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
        'css/magiczoomplus.css',
        'css/animate.css',
        'css/drawer.css',
        'css/normalize.css',
        'css/daterangepicker.css',
        'css/responsive.css',
        'css/weshop.css', //weshop css customize
        'css/home.css',
    ];

    public $js = [
        'js/app.js', //all js website
        'js/common.js',
        'js/bootstrap.js',
        'js/lazyload.js',
        'js/skrollr.js',
        'js/backgroundVideo.js',
        'js/iscroll.min.js',
        'js/drawer.js',
        'js/magiczoomplus.js',
        'js/wow.min.js',
        'js/moment.min.js',
        'js/daterangepicker.js',
        'js/prefixfree.min.js',
        'js/style-new.js',
        'js/newletter.js',
        'js/user.js',
        'https://cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js',
        'js/flipclock.js',
        'js/popup.js',
    ];

    public $jsOptions = [
        'position' => View::POS_END
    ];
    public $depends = [
        'landing\WeshopOldAsset',
    ];
}