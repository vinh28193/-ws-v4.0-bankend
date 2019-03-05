<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-04
 * Time: 17:29
 */

namespace api\modules\v1\controllers;

use Yii;
use common\models\Order;
use common\data\ActiveDataProvider;
use api\controllers\BaseApiController;

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
        $query = Order::find();
        $query->withFullRelations();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->filter($requestParams);

        return $this->response(true, 'ok', $dataProvider);
    }
}