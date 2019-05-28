<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-21
 * Time: 08:52
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\components\db\ActiveQuery;
use common\data\ActiveDataProvider;
use common\helpers\ChatHelper;
use common\helpers\ExcelHelper;
use common\models\draft\DraftDataTracking;
use common\models\draft\DraftExtensionTrackingMap;
use common\models\draft\DraftMissingTracking;
use common\models\Package;
use common\models\draft\DraftWastingTracking;
use common\models\Manifest;
use common\models\Product;
use common\models\TrackingCode;
use common\models\Warehouse;
use Yii;
use yii\helpers\ArrayHelper;

class TrackingCodeController extends BaseApiController
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
            'create' => ['POST'],
            'update' => ['PUT'],
        ];
    }

    /**
     * @return array list of tracking code
     */
    public function actionIndex()
    {
        $page_m = \Yii::$app->request->get('p_m',1);
        $limit_m = \Yii::$app->request->get('ps_m',20);
        $page_c = \Yii::$app->request->get('p_c',1);
        $limit_c = \Yii::$app->request->get('ps_c',20);
        $page_w = \Yii::$app->request->get('p_w',1);
        $limit_w = \Yii::$app->request->get('ps_w',20);
        $page_ms = \Yii::$app->request->get('p_ms',1);
        $limit_ms = \Yii::$app->request->get('ps_ms',20);
        $page_u = \Yii::$app->request->get('p_mu',1);
        $limit_u = \Yii::$app->request->get('ps_mu',20);
        $manifest_id = \Yii::$app->request->get('m');
        $trackingC = \Yii::$app->request->get('trackingC');
        $trackingU = \Yii::$app->request->get('trackingU');
        $trackingW = \Yii::$app->request->get('trackingW');
        $trackingM = \Yii::$app->request->get('trackingM');

        $page = \Yii::$app->request->get('p',1);
        $limit = \Yii::$app->request->get('ps',20);
        $model = Manifest::find()->where(['active' => 1]);
        //#Todo filter
        http://weshop-v4.back-end.local.vn/v1/tracking-code?trackingC=&trackingW=&trackingM=&trackingU=&ps_ms=20&ps_u=20&ps_c=20&ps_w=20&p_ms=1&p_u=1&p_c=1&p_w=1&ps_m=20&p_m=1
        $manifests = clone $model;
        if($manifest_id){
            $model->andWhere(['id'=>$manifest_id]);
            $manifests->andWhere(['id'=>$manifest_id]);
        }
        if(!$trackingC && !$trackingM && !$trackingU && !$trackingW){
            $data['_total_manifest'] = $manifests->count();
            $data['_manifest'] = $manifests->with(['receiveWarehouse','sendWarehouse'])->orderBy('id desc')->limit($limit_m)->offset($limit_m*$page_m - $limit_m)->asArray()->all();
        }
        $data['_items'] = $model->limit($limit)->offset($page*$limit - $limit)->orderBy('id desc')->asArray()->one();
        $miss = DraftDataTracking::find()->where(['manifest_id' => $data['_items']['id']])
            ->andWhere(['<>','status',DraftDataTracking::STATUS_LOCAL_INSPECTED])
            ->andWhere(['<>','status',DraftDataTracking::STATUS_LOCAL_RECEIVED])
            ->andWhere(['<>','status',DraftDataTracking::MERGE_MANUAL]);
        if($trackingM){
            $miss->andWhere(['like','tracking_code',$trackingM]);
        }
        $wast = DraftWastingTracking::find()->where(['manifest_id' => $data['_items']['id']])
            ->andWhere(['<>','status',DraftWastingTracking::MERGE_CALLBACK])
            ->andWhere(['<>','status',DraftWastingTracking::MERGE_MANUAL]);
        if($trackingW){
            $wast->andWhere(['like','tracking_code',$trackingW]);
        }
        $complete = Package::find()->where(['manifest_id' => $data['_items']['id']])
            ->andWhere(['and',['is not','product_id',null],['<>','product_id',''],['<>','status',Package::STATUS_SPLITED]]);
        if($trackingC){
            $complete->andWhere(['like','tracking_code',$trackingC]);
        }
        $unknown = Package::find()->where(['manifest_id' => $data['_items']['id']])
            ->andWhere(['or',['product_id' => null],['product_id' => '']])->andWhere(['<>','status',Package::STATUS_SPLITED]);
        if($trackingU){
            $unknown->andWhere(['like','tracking_code',$trackingU]);
        }

        $ext = DraftExtensionTrackingMap::find()->with(['order','product'])
            ->where(['or',['status' => DraftExtensionTrackingMap::STATUST_NEW],['draft_data_tracking_id' => null],['draft_data_tracking_id' => '']]);
        $data['_items']['draftWastingTrackings_total'] = $wast->count();
        $data['_items']['draftMissingTrackings_total'] = $miss->count();
        $data['_items']['draftPackageItems_total'] = $complete->count();
        $data['_items']['unknownTrackings_total'] = $unknown->count();

        $data['_items']['draftWastingTrackings'] = $wast->with(['order','product','purchaseOrder'])->orderBy('id desc')->limit($limit_w)->offset($page_w*$limit_w - $limit_w)->asArray()->all();
        $data['_items']['draftMissingTrackings'] = $miss->with(['order','product','purchaseOrder'])->orderBy('id desc')->limit($limit_ms)->offset($page_ms*$limit_ms - $limit_ms)->asArray()->all();
        $data['_items']['draftPackageItems'] = $complete->with(['order','product','purchaseOrder'])->orderBy('id desc')->limit($limit_c)->offset($page_c*$limit_c - $limit_c)->asArray()->all();
        $data['_items']['unknownTrackings'] = $unknown->with(['order','product','purchaseOrder'])->orderBy('id desc')->limit($limit_u)->offset($page_u*$limit_u - $limit_u)->asArray()->all();
        $data['_ext'] = $ext->orderBy('id desc')->asArray()->all();
        return $this->response(true, "Success", $data);
    }

    /**
     * @param ActiveQuery $find
     * @param string $tracking_code
     * @param int $limit
     * @param int $page
     * @return array|\yii\db\ActiveRecord[]
     */
    function getDataListTracking($find , $tracking_code = '' ,$limit = 20, $page = 1){
        if($tracking_code){
            $find->andWhere(['like','tracking_code',$tracking_code]);
        }
        $rs = $find->orderBy('id desc')->limit($limit)->offset($page*$limit - $limit)->asArray()->all();
        return $rs;
    }


    public function actionCreate()
    {
        $start = microtime(true);
        $post = $this->post;
        $tokens = [];
        if (($store = ArrayHelper::getValue($post, 'store')) === null) {
            return $this->response(false, "create form undefined store !.");
        }
        if (($warehouse_id = ArrayHelper::getValue($post, 'warehouse')) === null) {
            return $this->response(false, "create form undefined warehouse !.");
        }
        if (($warehouse_us_id = ArrayHelper::getValue($post, 'warehouse_us')) === null) {
            return $this->response(false, "create form undefined warehouse !.");
        }
        if (($manifest = ArrayHelper::getValue($post, 'manifest')) === null) {
            return $this->response(false, "create form undefined manifest !.");
        }
        if (($date = ArrayHelper::getValue($post, 'date')) === null) {
            return $this->response(false, "Date undefined !");
        }
        $warehouse = Warehouse::findOne($warehouse_id);
        $warehouse_us = Warehouse::findOne($warehouse_us_id);
        if(!$warehouse){
            return $this->response(false, "Cannot find warehouse !");
        }
        $transaction = Yii::$app->getDb()->beginTransaction();

        try {
            $manifest = Manifest::createSafe($manifest, $warehouse->id, 1);

            foreach (ExcelHelper::readFromFile('file') as $name => $sheet) {
                $count = 0;
                $tokens[$name]['total'] = count($sheet);
                foreach ($sheet as $row) {
                    if (($trackingCode = ArrayHelper::getValue($row, 'TrackingCode')) === null) {
                        $tokens[$name]['error'][] = $row;
                        continue;
                    }
                    $model = TrackingCode::find()
                        ->where([
                            'tracking_code' => $trackingCode,
                            'remove' => 0,
                            'status' => TrackingCode::STATUS_US_RECEIVED
                        ])->andWhere([
                            'or',
                            ['manifest_code'=>''],
                            ['manifest_code'=>null],
                            ['manifest_code'=>$manifest->manifest_code],
                        ])->limit(1)->one();
                    if(!$model){
                        $model = new TrackingCode();
                    }
                    $model->tracking_code = $trackingCode;
                    $model->store_id = $manifest->store_id;
                    $model->manifest_id = $manifest->id;
                    $model->manifest_code = $manifest->manifest_code;
                    $model->stock_out_us = strtotime($date);
                    $model->stock_in_us = $model->stock_in_us ? $model->stock_in_us : strtotime($date) - (60*60*24*3);
                    $model->warehouse_us_id = $warehouse_us->id;
                    $model->warehouse_us_name = $warehouse_us->name;
                    $model->warehouse_local_id = $warehouse->id;
                    $model->warehouse_local_name = $warehouse->name;
                    $model->status = TrackingCode::STATUS_US_SENDING;
                    if (!$model->save(false)) {
                        $tokens[$name]['error'][] = $row;
                    }
                     //save tracking log
                    $params = array(
                        'manifest'=>$manifest->manifest_code,
                        'trackingcode' => $trackingCode,
                        'text' => 'Chuyển từ kho mỹ',
                        'log_type' => 'US Sending',
                        'store' => $store,
                        'boxme_warehosue' => $warehouse
                    );
                   
                    Yii::$app->wsLog->push('TrackingLog',null,null,$params);
                    $count++;
                }
                $tokens[$name]['success'] = $count;
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e);
            return $this->response(false, $e->getMessage());
        }
        $time = microtime(true) - $start;
        $message = ["Sending `$manifest->manifest_code` success"];
        foreach ($tokens as $name => $token){
            $error = isset($token['error']) ? count($token['error']) : 0;
            $message[] = "from `$name` {$token['total']} executed $error error/{$token['success']} success";
        }
        $message = implode(", ",$message);

//        /** Log + Push chat**/
//        ChatHelper::push($message,$model->ordercode,'GROUP_WS', 'SYSTEM');
//        Yii::$app->wsLog->order->push('Us Sending', null, [
//            'id' => $model->order->ordercode,
//            'request' => $this->post,
//        ]);
        return $this->response(true, $message);
    }

    function getList(){

    }

    public function actionUpdate($id){
        $modal = Package::findOne($id);
        if(!$modal){
            return $this->response(false,'Cannot find your tracking!');
        }
        $product = Product::findOne($this->post['product_id']);
        if(!$product){
            return $this->response(false,'Cannot find your product id!');
        }
        if($product->order_id != $this->post['order_id']){
            return $this->response(false,'Order id and product id not mapping!');
        }
        if(!$this->post['item_name'] || !$this->post['purchase_invoice_number']){
            return $this->response(false,'Fill all field!');
        }
        $modal->order_id = $product->order_id;
        $modal->product_id = $product->id;
        $modal->item_name = $this->post['item_name'];
        $modal->purchase_invoice_number = $this->post['purchase_invoice_number'];
        $modal->updated_by = Yii::$app->user->getId();
        $modal->updated_at = time();
        $modal->save(0);
        DraftDataTracking::updateAll([
            'order_id' => $product->order_id,
            'product_id' => $product->id,
            'purchase_invoice_number' => $this->post['purchase_invoice_number'],
            'updated_by' => Yii::$app->user->getId(),
            'updated_at' => time(),
            'item_name' => $this->post['item_name']
        ],['id' => $modal->draft_data_tracking_id]);
        return $this->response(true,'Update success!');
    }
}