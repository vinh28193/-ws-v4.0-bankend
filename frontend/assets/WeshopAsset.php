<?php


namespace frontend\assets;


use yii\web\AssetBundle;

class WeshopAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/ws.js',
        'js/ws.browse.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];

    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);
        $view->registerJs('ws.i18nLoadMessages([{"category":"javascript","message":"Ok"}])');
    }
}