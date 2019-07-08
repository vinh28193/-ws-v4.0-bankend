<?php


namespace frontend\controllers;

use common\models\SystemZipcode;
use Yii;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
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

    public function actionGetProvince()
    {

    }

    public function actionGetZipCode()
    {
        $queryParams = $this->request->getQueryParams();
        $message = ["load address for country `{$this->storeManager->store->country_code}`"];
        if (($zip = ArrayHelper::getValue($queryParams, 'q', null)) !== null) {
            $message[] = "for zip code `{$zip}`";
        }
        $page = ArrayHelper::getValue($queryParams, 'p', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        list($data, $count) = SystemZipcode::loadZipCode($this->storeManager->store->country_id, $zip, $offset, $limit);
        return ['data' => $data, 'limit' => $limit, 'count' => $count];
    }

}