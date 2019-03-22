<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-21
 * Time: 08:52
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\data\ActiveDataProvider;
use common\models\TrackingCode;
use Yii;

class TrackingCodeController extends BaseApiController
{

    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => ['operation', 'master_operation']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function verbs()
    {
        return [
            'index' => ['GET']
        ];
    }

    /**
     * @return array list of tracking code
     */
    public function actionIndex()
    {
        $queryParams = Yii::$app->request->getQueryParams();
        $query = TrackingCode::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeParam' => 'ps',
                'pageParam' => 'p',
                'params' => $queryParams,
            ],
            'sort' => [
                'params' => $queryParams,
            ],
        ]);

        return $this->response(true, 'Ok', $dataProvider);

    }
}