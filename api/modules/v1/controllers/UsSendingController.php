<?php


namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\helpers\ExcelHelper;
use common\models\draft\DraftDataTracking;
use common\models\draft\DraftExtensionTrackingMap;
use common\models\Manifest;
use common\models\Package;
use common\models\Product;
use common\models\TrackingCode;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class UsSendingController extends BaseApiController
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
            'view' => ['GET'],
        ];
    }
    public function actionIndex(){
        $manifest_id = \Yii::$app->request->get('m');
        $limit = \Yii::$app->request->get('ps',20);
        $page = \Yii::$app->request->get('p',1);
        $limit_t = \Yii::$app->request->get('ps_t',20);
        $page_t = \Yii::$app->request->get('p_t',1);
        $limit_e = \Yii::$app->request->get('ps_e',20);
        $page_e = \Yii::$app->request->get('p_e',1);
        $filter_t = \Yii::$app->request->get('f_t');
        $filter_e = \Yii::$app->request->get('f_e');
        $filter_t = $filter_t ? @json_decode(@base64_decode($filter_t),true) : false;
        $filter_e = $filter_e ? @json_decode(@base64_decode($filter_e),true) : false;
        $manifest = Manifest::find()->with(['receiveWarehouse']);
        if($manifest_id){
            $manifest->andWhere(['id'=>$manifest_id]);
        }
        $tracking = DraftDataTracking::find()->with(['order','product']);
        $tracking->leftJoin('product','product.id = '.DraftDataTracking::tableName().'.product_id')
            ->leftJoin('order','order.id = '.DraftDataTracking::tableName().'.order_id');
        if($filter_t){
            if(isset($filter_t['tracking_code']) && $filter_t['tracking_code']){
                $tracking->andWhere(['tracking_code'=>$filter_t['tracking_code']]);
            }
            if(isset($filter_t['sku']) && $filter_t['sku']){
                $tracking->andWhere(['product.sku'=>$filter_t['sku']]);
            }
            if(isset($filter_t['order_code']) && $filter_t['order_code']){
                $tracking->andWhere(['order.ordercode'=>$filter_t['order_code']]);
            }
            if(isset($filter_t['type_tracking']) && $filter_t['type_tracking']){
                $tracking->andWhere(['type_tracking'=>$filter_t['type_tracking']]);
            }
        }
        $ext = DraftExtensionTrackingMap::find()->with(['order','product'])
            ->where(['or',['status' => DraftExtensionTrackingMap::STATUST_NEW],['draft_data_tracking_id' => null],['draft_data_tracking_id' => '']]);
        $ext->leftJoin('product','product.id = '.DraftExtensionTrackingMap::tableName().'.product_id')
            ->leftJoin('order','order.id = '.DraftExtensionTrackingMap::tableName().'.order_id');
        if($filter_e){
            if(isset($filter_e['tracking_code']) && $filter_e['tracking_code']){
                $ext->andWhere(['tracking_code'=>$filter_e['tracking_code']]);
            }
            if(isset($filter_e['sku']) && $filter_e['sku']){
                $ext->andWhere(['product.sku'=>$filter_e['sku']]);
            }
            if(isset($filter_e['order_code']) && $filter_e['order_code']){
                $ext->andWhere(['order.ordercode'=>$filter_e['order_code']]);
            }
            if(isset($filter_e['type_tracking']) && $filter_e['type_tracking']){
                $ext->andWhere(['type_tracking'=>$filter_e['type_tracking']]);
            }
        }
        $data['_tracking_total'] = 0;
        $data['_tracking'] = [];
        $data['_ext_total'] = 0;
        $data['_ext'] = [];
        $data['_manifest_total'] = $manifest->count();
        $data['_manifest'] = $manifest->limit($limit)->offset($limit*$page - $limit)->orderBy('id desc')->asArray()->all();
        if($data['_manifest']){
            $tracking->andWhere(['manifest_id' => $data['_manifest'][0]['id']]);
            $data['_tracking_total'] = $tracking->count();
            $data['_tracking'] = $tracking->limit($limit_t)->offset($limit_t*$page_t - $limit_t)->orderBy('id desc')->asArray()->all();
            $data['_ext_total'] = $ext->count();
            $data['_ext'] = $ext->limit($limit_e)->offset($limit_e*$page_e - $limit_e)->orderBy('id desc')->asArray()->all();
        }
        return $this->response(true, "Success", $data);
    }
    public function actionUpdate($id){
        $modal = DraftDataTracking::findOne($id);
        if(!$modal){
            return $this->response(false,'Cannot find your tracking!');
        }
        $product = Product::findOne($this->post['product_id']);
        if(!$product){
            return $this->response(false,'Cannot find your product id!');
        }
        if($this->post['order_id'] && $product->order_id != $this->post['order_id']){
            return $this->response(false,'Order id and product id not mapping!');
        }
        if(!$this->post['item_name'] || !$this->post['purchase_invoice_number']){
            return $this->response(false,'Fill all field!');
        }
        $modal->order_id = $product->order_id;
        $modal->product_id = $product->id;
        $modal->purchase_invoice_number = $this->post['purchase_invoice_number'];
        $modal->updated_by = Yii::$app->user->getId();
        $modal->updated_at = time();
        $modal->item_name = $this->post['item_name'];
        $modal->save(0);
        Package::updateAll([
            'order_id' => $product->order_id,
            'product_id' => $product->id,
            'purchase_invoice_number' => $this->post['purchase_invoice_number'],
            'updated_by' => Yii::$app->user->getId(),
            'updated_at' => time(),
            'item_name' => $this->post['item_name']
        ],['draft_data_tracking_id' => $modal->id]);
        return $this->response(true,'Update success!');
    }
    /** XUất excel cho boxme */
    public function actionView($id){
        /** @var DraftDataTracking[] $TrackingCodes */
        $TrackingCodes = DraftDataTracking::find()->with(['order'])->where(['manifest_id' => $id])->all();
        if(!$TrackingCodes){
            return $this->response(false,'Manifest empty!');
        }
        $data = [
            'A1' => 'STT',
            'B1' => 'Packing Code',
            'C1' => 'Tracking Code',
            'D1' => 'Tracking Code WS',
            'E1' => 'Item Name',
            'F1' => 'Tracking Type',
            'G1' => 'Image',
            'H1' => 'Quantity',
            'I1' => 'Note',
        ];

        $rows = [];
        $rowIndex = 1;
        $fileName = 'file/canfind.xlsx';

        foreach ($TrackingCodes as $key => $val) {
            $fileName = 'file/' . $val->manifest_code.'-'.$val->manifest_id . '.xlsx';
            $rowIndex++;
            $rows['A' . $rowIndex] = $key+1;
            $rows['B' . $rowIndex] = $val->manifest_code.'-'.$val->manifest_id;
            $rows['C' . $rowIndex] = $val->tracking_code;
            $rows['D' . $rowIndex] = $val->ws_tracking_code;
            $rows['E' . $rowIndex] = $val->item_name;
            $rows['F' . $rowIndex] = $val->type_tracking;
            $rows['G' . $rowIndex] = $val->image;
            $rows['H' . $rowIndex] = $val->quantity;
            $rows['I' . $rowIndex] = $val->order ? $val->order->note : '';
            $data = ArrayHelper::merge($data, $rows);
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                $sheet->setCellValue($key, $val);
                $sheet->getColumnDimension(preg_replace('/[0-9]+/', '', $key))->setAutoSize(true);
            }
        }
        $fileDirPath = 'file';
        if (!file_exists($fileDirPath)) {
            @mkdir($fileDirPath, 0777, true);
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save($fileName);
        return $this->response(true,'Ok', ['link' => Url::home(true).'/'.$fileName]);
    }
}