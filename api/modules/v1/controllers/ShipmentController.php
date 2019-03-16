<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-15
 * Time: 20:41
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\data\ActiveDataProvider;
use common\models\Shipment;

class ShipmentController extends BaseApiController
{

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET']
        ];
    }

    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => $this->getAllRoles(true, ['user', 'sale', 'marketing'])
            ],
        ];
    }

    /**
     * list all shipment
     * @return array
     */
    public function actionIndex()
    {
        $params = $this->get;
        $query = Shipment::find();

        $query->joinWith(['packageItems']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeParam' => 'perPage',
                'params' => $params,
            ],
            'sort' => [
                'params' => $params,
            ],
        ]);

        $query->filter($params);

        return $this->response(true, "get shipment success", $dataProvider);

    }
}