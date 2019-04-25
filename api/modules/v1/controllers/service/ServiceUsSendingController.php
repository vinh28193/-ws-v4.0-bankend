<?php


namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use common\models\draft\DraftDataTracking;
use common\models\draft\DraftPackageItem;
use common\models\Product;

class ServiceUsSendingController extends BaseApiController
{
    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['merge', 'index', 'map-unknown', 'split-tracking', 'seller-refund','mark-hold'],
                'roles' => $this->getAllRoles(true),
            ],
        ];
    }

    public function verbs()
    {
        return [
            'split-tracking' => ['DELETE'],
            'merge' => ['POST'],
            'map-unknown' => ['POST'],
            'mark-hold' => ['POST'],
            'seller-refund' => ['POST'],
            'index' => ['GET'],
        ];
    }
    public function actionMapUnknown($id){
        $model = DraftDataTracking::findOne($id);
        if(!$model){
            return $this->response(false,'Cannot find your tracking!');
        }
        $product = Product::findOne($this->post['product_id']);
        if(!$product){
            return $this->response(false,'Cannot find your product id!');
        }
        $count = DraftDataTracking::find()->where(['tracking_code' => $model])->count();
        $model->product_id = $product->id;
        $model->order_id = $product->order_id;
        $model->save();
        if($count > 1){
            DraftDataTracking::updateAll(
                ['type_tracking' => DraftDataTracking::TYPE_SPLIT],
                [
                    'and',
                    ['tracking_code' => $model->tracking_code],
                    ['<>' ,'product_id' , ''],
                    ['<>' ,'product_id' , null],
                ]
            );
        }else{
            $model->type_tracking = DraftDataTracking::TYPE_NORMAL;
            $model->save(0);
        }
        return $this->response(true,'Map tracking success!');
    }
}