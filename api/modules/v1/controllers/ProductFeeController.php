<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-23
 * Time: 08:44
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\models\Product;
use common\models\ProductFee;
use yii\web\NotFoundHttpException;


class ProductFeeController extends BaseApiController
{

    protected function verbs()
    {
        return [
            'update' => ['PATCH','PUT']
        ];
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        /** @var ProductFee $model */
        $model = $this->findModel(['id' => $id]);
        $this->post['created_at'] = $model->created_at;
        $this->post['updated_at'] = time();
        $old_amount = $model->amount;
        $old_local_amount = $model->local_amount;
        if (!$model->load($this->post, '')) {
            return $this->response(false, 'Can not resolve current request parameter');
        }
        if (!$model->save()) {
            return $this->response(false, $model->getFirstErrors());
        }
        $product = Product::findOne($model->product_id);
        $order = $product->order;
        $type_total = 'total_'.$model->type.'_local';


        $order->$type_total = ($product->getAdditionalFees()->getTotalAdditionFees($model->type))[1];

        $order->save(0);
//        die;
        // Todo update back to Order
        return $this->response(true, "update fee $id success");
    }

    /**
     * @param $condition
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function findModel($condition)
    {
        if (is_numeric($condition)) {
            $condition = ['id' => $condition];
        }
        if (($model = ProductFee::findOne($condition)) === null) {
            throw new NotFoundHttpException("not found");
        }
        return $model;
    }
}
