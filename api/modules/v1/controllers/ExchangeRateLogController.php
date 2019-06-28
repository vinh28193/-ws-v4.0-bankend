<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 6/28/2019
 * Time: 2:14 PM
 */

namespace api\modules\v1\controllers;
use api\controllers\BaseApiController;
use common\modelsMongo\ExchangeRateLog;
use Yii;


class ExchangeRateLogController extends BaseApiController
{
    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index','view','delete','update','create'],
                'roles' => ['superAdmin']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function verbs()
    {
        return [
            'index' => ['GET'],
            'create' => ['POST'],
            'update' => ['PUT'],
            'view' => ['GET'],
            'delete' => ['DELETE'],
        ];
    }

    public function actionIndex() {
        $model = ExchangeRateLog::find()->orderBy(['updated_at' => SORT_DESC])->asArray()->all();
        $total = count($model);
        $data = [
            'model' => $model,
            'total' => $total
        ];
        return $this->response(true, 'success', $data);

    }
}