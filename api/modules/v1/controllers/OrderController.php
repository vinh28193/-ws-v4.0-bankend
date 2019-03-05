<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-04
 * Time: 17:29
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\models\Order;
use common\models\searchs\OrderSearch;
use Yii;

class OrderController extends BaseApiController
{

    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => ['admin', 'sale']
            ]
        ];
    }

    public function verbs()
    {
        return [
            'index' => ['GET', 'POST'],
            'create' => ['POST'],
            'update' => ['PUT'],
            'view' => ['GET'],
            'delete' => ['DELETE']
        ];
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        return $this->response(true, 'ok', (new OrderSearch())->search2($requestParams));
    }
}