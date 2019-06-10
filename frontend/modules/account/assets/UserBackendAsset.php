<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 4/19/2019
 * Time: 5:30 PM
 */

namespace frontend\modules\account\assets;
use yii\web\AssetBundle;

class UserBackendAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css',
        'css/style-new.css',
        'css/slick.css',
        'owlcarousel/owl.carousel.min.css',
        'owlcarousel/owl.theme.default.min.css',
        'fonts/line-awesome/css/line-awesome.min.css',
        'fonts/line-awesome/css/line-awesome-font-awesome.min.css',
    ];
    public $js = [
        'js/ws.js',
        'js/style.js',
        'js/account/user.js',
        'owlcarousel/owl.carousel.min.js',
        'js/account/wallet.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'common\assets\JQueryEzPlus',
        'common\assets\FontawesomeAsset',
        'common\assets\PopperAsset',
        'common\assets\OwlCarousel',
        'common\assets\SlickCarouselAsset',
        'userbackend\assets\GijgoAsset'
    ];
}