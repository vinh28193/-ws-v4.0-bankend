<?php


namespace frontend\widgets\item;

use yii\web\AssetBundle;

class ItemDetailAsset extends AssetBundle
{
    public $sourcePath = '@frontend/widgets/item/assets';

    public $js = [
        'ws.item.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
}