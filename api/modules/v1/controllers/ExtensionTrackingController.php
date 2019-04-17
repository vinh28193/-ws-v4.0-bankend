<?php


namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\components\db\ActiveQuery;
use common\models\db\PurchaseProduct;
use common\models\draft\DraftDataTracking;
use common\models\draft\DraftExtensionTrackingMap;

class ExtensionTrackingController extends BaseApiController
{
    /** @var ActiveQuery $query */
    public $query;
    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'roles' => $this->getAllRoles(true)
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['POST'],
            'update' => ['PUT'],
            'create' => ['POST']
        ];
    }

    public function actionIndex(){

        $this->query = DraftExtensionTrackingMap::find();
        $this->setJoinWith();
        $this->setFilter();
        $data['total'] = $this->query->count();
        $this->query->limit($this->post['limit'])->offset($this->post['page']*$this->post['limit'] - $this->post['limit']);
        $model = $this->query->orderBy('id desc')->asArray()->all();
        $data['data'] = $model;
        $data['totalPage'] = ceil($data['total']/$this->post['limit']);
        $data['page'] = $this->post['page'];
        return $this->response(true,'Get success',$data);
    }

    public function actionUpdate(){

    }

    public function actionCreate(){
        if ($this->post['trackingCode'] && $this->post['warehouse'] && $this->post['purchaseInvoice'] && $this->post['time']) {
            /** @var PurchaseProduct[] $purchaseProducts */
            $trackings = explode(',', $this->post['trackingCode']);
            foreach ($trackings as $tracking) {
                $trackingData = explode('-', $tracking);
                if ($trackingData > 1) {
                    /** @var PurchaseProduct $purchaseProduct */
                    $purchaseProduct = PurchaseProduct::find()
                        ->innerJoin('purchase_order','purchase_order.id = purchase_product.purchase_order_id')
                        ->where([
                            'product_id' => $trackingData[1],
                            'purchase_order.purchase_order_number' => $this->post['purchaseInvoice']
                        ])->one();
                    if($purchaseProduct){
                        $ext = DraftExtensionTrackingMap::find()->where([
                            'tracking_code' => $trackingData[0],
                            'product_id' => $trackingData[1],
                            'purchase_invoice_number' => $this->post['purchaseInvoice']
                        ])->one();
                        if(!$ext){
                            $ext = new DraftExtensionTrackingMap();
                            $ext->tracking_code = $trackingData[0];
                            $ext->product_id = $trackingData[1];
                            $ext->order_id = $purchaseProduct->order_id;
                            $ext->purchase_invoice_number = $this->post['purchaseInvoice'];
                            $ext->created_at = time();
                            $ext->updated_at = time();
                            $ext->created_by = \Yii::$app->user->id;
                            $ext->updated_by = \Yii::$app->user->id;
                        }
                        $ext->status = DraftExtensionTrackingMap::US_RECEIVED;
                        $ext->quantity = isset($trackingData[2]) && $trackingData[2] ? $trackingData[2] : $purchaseProduct->purchase_quantity;
                        $ext->number_run = $ext->number_run ? $ext->number_run + 1 : 1;
                        $ext->save();
                        $purchaseProduct->receive_quantity = $ext->quantity;
                        $purchaseProduct->save(0);
                        $draft_data = DraftDataTracking::find()->where([
                            'tracking_code' => $trackingData[0],
                            'product_id' => $trackingData[1],
                            'purchase_invoice_number' => $this->post['purchaseInvoice']
                        ])->one();
                        if(!$draft_data){
                            $draft_data = DraftDataTracking::find()->where([
                                'tracking_code' => $trackingData[0],
                                'product_id' => null,
                                'order_id' => null,
                            ])->one();
                            if(!$draft_data){
                                $tmp = DraftDataTracking::find()->where([
                                    'tracking_code' => $trackingData[0],
                                ])->one();
                                $draft_data = new DraftDataTracking();
                                $draft_data->created_at = time();
                                $draft_data->updated_at = time();
                                $draft_data->created_by = \Yii::$app->user->id;
                                $draft_data->updated_by = \Yii::$app->user->id;
                                if($tmp){
                                    $draft_data->manifest_code = $tmp->manifest_code;
                                    $draft_data->manifest_id = $tmp->manifest_id;
                                }
                            }
                        }
                        $draft_data->tracking_code = $trackingData[0];
                        $draft_data->product_id = $purchaseProduct->product_id;
                        $draft_data->order_id = $purchaseProduct->order_id;
                        $draft_data->purchase_invoice_number = $this->post['purchaseInvoice'];
                        $draft_data->quantity = $ext->quantity;
                        $draft_data->createOrUpdate(false);
                    }
                } elseif ($trackingData == 1) {
                    /** @var PurchaseProduct $purchaseProduct */
                    $purchaseProduct = PurchaseProduct::find()
                        ->innerJoin('purchase_order','purchase_order.id = purchase_product.purchase_order_id')
                        ->where([
                            'product_id' => $trackingData[1],
                            'purchase_order.purchase_order_number' => $this->post['purchaseInvoice']
                        ])->one();
                    if($purchaseProduct){
                        $ext = DraftExtensionTrackingMap::find()->where([
                            'tracking_code' => $trackingData[0],
                            'product_id' => $trackingData[1],
                            'purchase_invoice_number' => $this->post['purchaseInvoice']
                        ])->one();
                        if(!$ext){
                            $ext = new DraftExtensionTrackingMap();
                            $ext->tracking_code = $trackingData[0];
                            $ext->product_id = $trackingData[1];
                            $ext->order_id = $purchaseProduct->order_id;
                            $ext->purchase_invoice_number = $this->post['purchaseInvoice'];
                            $ext->created_at = time();
                            $ext->updated_at = time();
                            $ext->created_by = \Yii::$app->user->id;
                            $ext->updated_by = \Yii::$app->user->id;
                        }
                        $ext->status = DraftExtensionTrackingMap::US_RECEIVED;
                        $ext->quantity = isset($trackingData[2]) && $trackingData[2] ? $trackingData[2] : $purchaseProduct->purchase_quantity;
                        $ext->number_run = $ext->number_run ? $ext->number_run + 1 : 1;
                        $ext->save();
                        $purchaseProduct->receive_quantity = $ext->quantity;
                        $purchaseProduct->save(0);
                        $draft_data = DraftDataTracking::find()->where([
                            'tracking_code' => $trackingData[0],
                            'product_id' => $trackingData[1],
                            'purchase_invoice_number' => $this->post['purchaseInvoice']
                        ])->one();
                        if(!$draft_data){
                            $draft_data = DraftDataTracking::find()->where([
                                'tracking_code' => $trackingData[0],
                                'product_id' => null,
                                'order_id' => null,
                            ])->one();
                            if(!$draft_data){
                                $tmp = DraftDataTracking::find()->where([
                                    'tracking_code' => $trackingData[0],
                                ])->one();
                                $draft_data = new DraftDataTracking();
                                $draft_data->created_at = time();
                                $draft_data->updated_at = time();
                                $draft_data->created_by = \Yii::$app->user->id;
                                $draft_data->updated_by = \Yii::$app->user->id;
                                if($tmp){
                                    $draft_data->manifest_code = $tmp->manifest_code;
                                    $draft_data->manifest_id = $tmp->manifest_id;
                                }
                            }
                        }
                        $draft_data->tracking_code = $trackingData[0];
                        $draft_data->product_id = $purchaseProduct->product_id;
                        $draft_data->order_id = $purchaseProduct->order_id;
                        $draft_data->purchase_invoice_number = $this->post['purchaseInvoice'];
                        $draft_data->quantity = $ext->quantity;
                        $draft_data->createOrUpdate(false);
                    }
                }
            }
        } else {
            return $this->response(true, 'create fail', []);
        }
    }

    public function setFilter(){
        $filter = $this->post['filter'];
        $this->query->leftJoin('order','order.id = draft_extension_tracking_map.order_id');
        $this->query->leftJoin('product','product.id = draft_extension_tracking_map.product_id');
        $this->query->leftJoin('purchase_order','purchase_order.id = draft_extension_tracking_map.purchase_invoice_number');
        if($filter['orderCode']){
            $this->query->andWhere(['like','order.ordercode' , $filter['orderCode']]);
        }
        if($filter['orderId']){
            $this->query->andWhere(['like','draft_extension_tracking_map.order_id' , $filter['orderId']]);
        }
        if($filter['trackingCode']){
            $this->query->andWhere(['like','draft_extension_tracking_map.tracking_code' , $filter['trackingCode']]);
        }
        if($filter['sku']){
            $this->query->andWhere(['or',['like','product.sku' ,$filter['sku']],['like','product.parent_sku' ,$filter['sku']]]);
        }
        if($filter['purchaseInvoice']){
            $this->query->andWhere(['or',['like','purchase_order.id',$filter['purchaseInvoice']],['like','purchase_order.purchase_order_number',$filter['purchaseInvoice']]]);
        }
    }
    public function setJoinWith(){
        $this->query->with(['order','product','purchase']);
    }
}