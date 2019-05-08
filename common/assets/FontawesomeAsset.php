<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 4/19/2019
 * Time: 6:05 PM
 */

namespace common\assets;
use yii\web\AssetBundle;

class FontawesomeAsset extends AssetBundle
{

    public $js = [
        'https://use.fontawesome.com/releases/v5.8.1/js/all.js'
    ];

    public $css = [
        'https://use.fontawesome.com/releases/v5.8.1/css/all.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];

}
