<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 4/19/2019
 * Time: 8:45 PM
 */

namespace frontend\modules\account\assets;
use yii\web\AssetBundle;

class GijgoAsset extends AssetBundle
{
    public $css = [
        'https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css',
    ];
    public $js = [
        'https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}