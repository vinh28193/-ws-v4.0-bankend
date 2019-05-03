<?php


namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use common\models\Order;

class OrderController extends BaseApiController
{
    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index','update'],
                'roles' => $this->getAllRoles(true),
            ],
        ];
    }

    public function verbs()
    {
        return [
            'index' => ['GET'],
            'update' => ['PUT'],
            'create' => ['POST'],
        ];
    }

    public function actionUpdate($id){
        $model = Order::findOne($id);
        if(!$model){
            return $this->response(false,'Update order false!');
        }
        if ($model->current_status == 'PURCHASED') {
            $model->current_status = 'SELLER_SHIPPED';
            $model->seller_shipped = time();
        } else if ($model->current_status == 'SELLER_SHIPPED') {
            $model->current_status = 'US_RECEIVED';
            $model->stockin_us = time();
        } else if ($model->current_status == 'US_RECEIVED') {
            $model->current_status = 'US_SENDING';
            $model->stockout_us = time();
        } else if ($model->current_status == 'US_SENDING') {
            $model->current_status = 'LOCAL_RECEIVED';
            $model->stockin_local = time();
        } else if ($model->current_status == 'LOCAL_RECEIVED') {
            $model->current_status = 'DELIVERING';
            $model->stockout_local = time();
        } else if ($model->current_status == 'DELIVERING') {
            $model->current_status = 'AT_CUSTOMER';
            $model->at_customer = time();
        }
        $model->save();
        return $this->response(true, 'Update Success');
    }
}