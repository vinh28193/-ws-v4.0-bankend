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
use Yii;
use yii\web\NotFoundHttpException;


class ProductFeeController extends BaseApiController
{

    protected function verbs()
    {
        return [
            'update' => ['PATCH','PUT'],
            'view' => ['GET','HEAD']
        ];
    }

    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['view', 'update'],
                'roles' => ['operation','master_operation']

            ],
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
        if(in_array($model->type,['product_price_origin','tax_fee_origin','origin_shipping_fee'])){
            return $this->response(false, 'Can not edit '.$model->type.' .');
        }
        $type_total = $this->getTotalFeeOrder($model->type);
        if(!$type_total){
            return $this->response(false, 'Can not find type fee : '.$model->type.' in order table');
        }

        if (!$model->load($this->post, '')) {
            return $this->response(false, 'Can not resolve current request parameter');
        }
        $dirtyAttributes = $model->getDirtyAttributes();
        if (!$model->save()) {
            return $this->response(false, $model->getFirstErrors());
        }
        $product = Product::findOne($model->product_id);
        $order = $product->order;

        $product->total_fee_product_local += $model->local_amount - $old_local_amount ;
        $product->updated_at = time();
        $product->save();
        $order->updated_at = time();

        $order->$type_total += $model->local_amount - $old_local_amount;
        $order->total_fee_amount_local += $model->local_amount - $old_local_amount;
        $order->total_final_amount_local += $model->local_amount - $old_local_amount;
        $order->total_amount_local += $model->local_amount - $old_local_amount;

        $order->save(0);
        Yii::$app->wsLog->push('order','updateFee', null, [
            'id' => $model->id,
            'request' => $this->post,
            'response' => $dirtyAttributes
        ]);
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

    protected function getTotalFeeOrder($type){
        $typeTotal = "";
        switch (strtolower($type)){
            // Không cho phép thay đổi phí gốc : price , us tax, us ship
//            case 'product_price_origin':
//                $typeTotal = 'total_price_amount_origin';
//                break;
//            case 'tax_fee_origin':
//                $typeTotal = 'total_origin_tax_fee_local';
//                break;
//            case 'origin_shipping_fee':
//                $typeTotal = 'total_origin_shipping_fee_local';
//                break;
            case 'weshop_fee':
                $typeTotal = 'total_weshop_fee_local';
                break;
            case 'intl_shipping_fee':
                $typeTotal = 'total_intl_shipping_fee_local';
                break;
            case 'custom_fee':
                $typeTotal = 'total_custom_fee_amount_local';
                break;
            case 'delivery_fee_local':
                $typeTotal = 'total_delivery_fee_local';
                break;
            case 'packing_fee':
                $typeTotal = 'total_packing_fee_local';
                break;
            case 'inspection_fee ':
                $typeTotal = 'total_inspection_fee_local';
                break;
            case 'insurance_fee ':
                $typeTotal = 'total_insurance_fee_local';
                break;
            case 'vat_fee ':
                $typeTotal = 'total_vat_amount_local';
                break;
        }
        return $typeTotal;
    }

    public function actionView($id){
//        $fees = ProductFee::
    }
}
