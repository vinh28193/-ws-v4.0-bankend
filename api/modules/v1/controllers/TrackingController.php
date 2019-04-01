<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 01/04/2019
 * Time: 2:59 CH
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;

class TrackingController extends BaseApiController
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
            'index' => ['GET'],
            'create' => ['POST']
        ];
    }

    public function actionCreate(){
        $tranId = \Yii::$app->request->post("tran_id");
        $trackingCode = \Yii::$app->request->post("tracking_code");
        $sku = \Yii::$app->request->post("sku");
        $estimate = \Yii::$app->request->post("estimate");
        $quantity = \Yii::$app->request->post("quantity");
        $status = \Yii::$app->request->post("status");

    }
}
