<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-14
 * Time: 13:02
 */

namespace api\modules\v1\controllers;

use Yii;
use api\controllers\BaseApiController;
use common\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use common\models\Package;

class PackageController extends BaseApiController
{

    public function verbs()
    {
        return ArrayHelper::merge(parent::verbs(), [
            'index' => ['GET']
        ]);
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $requestParams = Yii::$app->getRequest()->getQueryParams();
        $query = Package::find();

        $query->filterRelation();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeParam' => 'perPage',
                'params' => $requestParams,
            ],
            'sort' => [
                'params' => $requestParams,
            ],
        ]);

        $query->filter($requestParams);

        return $this->response(true, 'get data success', $dataProvider);
    }
}