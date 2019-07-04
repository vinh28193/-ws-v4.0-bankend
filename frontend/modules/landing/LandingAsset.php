<?php


namespace landing;

use yii\helpers\Url;
use yii\web\AssetBundle;
use yii\web\View;

class LandingAsset extends AssetBundle
{

    public $basePath = '@webroot/landing';
    public $baseUrl = '@web/landing';

    public $css = [
        'css/fonts.css',
        'css/jquery-ui.css',
        'css/font-awesome.css',
        'css/magiczoomplus.css',
        'css/animate.css',
        'css/drawer.css',
        'css/normalize.css',
        'css/daterangepicker.css',
//        'css/common.css',
        'css/responsive.css',
        'css/weshop.css', //weshop css customize
        'css/home.css',
        'css/amazon.css',
        'css/dhgate.css',
        'css/ebay.css',
        'css/landing-mkt.css',
        'css/flipclock.css',
        'css/landing-mkt.css',
        'css/landing-top-store.css',
        'css/landing-pd.css',
        'css/landing.css',
        'css/popup-new.css',
        'css/app.css',
    ];
    public $js = [
        'js/owl.carousel.js',
        'js/style.js',
        'js/style-new.js',
        'js/skrollr.js',
        'js/jquery.mmenu.min.all.js',
        'js/app.js',
        'js/order.js',
//        'js/common.js',
        'js/bootstrap.js',
        'js/skrollr.js',
        'js/backgroundVideo.js',
        'js/iscroll.min.js',
        'js/drawer.js',
        'js/magiczoomplus.js',
        'js/wow.min.js',
        'js/moment.min.js',
        'js/daterangepicker.js',
        'js/prefixfree.min.js',
        'js/newletter.js',
        'js/user.js',
        'js/flipclock.js',
        'js/popup.js',
        'js/style-new.js',
        'js/lazyload.js',
    ];

    public $jsOptions = [
        'position' => View::POS_END
    ];

    public $depends = [
        'landing\AppOldAsset',
        'frontend\assets\FrontendAsset'
    ];
}