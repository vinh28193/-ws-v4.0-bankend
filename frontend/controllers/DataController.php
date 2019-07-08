<?php


namespace frontend\controllers;

use Yii;
use yii\di\Instance;
use yii\web\Response;
use yii\caching\CacheInterface;
use common\models\SystemCountry;
use common\models\SystemStateProvince;
use common\models\SystemDistrict;

class DataController extends FrontendController
{

    /**
     * @var bool
     */
    public $enableCsrfValidation = true;

    /**
     * @var string| CacheInterface
     */
    public $cache = 'cache';

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->cache = Instance::ensure($this->cache, 'yii\caching\CacheInterface');
        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    public function actionSelect2Province()
    {
    }

}