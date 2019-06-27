<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-14
 * Time: 13:02
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\models\Package;
use common\helpers\ChatHelper;
use Yii;

class PackageController extends BaseApiController
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
                'actions' => ['index', 'view'],
                'roles' => ['operation','master_operation', 'tester', 'master_sale', 'sale']
            ],
        ];
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $limit = Yii::$app->request->get('limit',20);
        $page = Yii::$app->request->get('page',1);
        $manifest_id = Yii::$app->request->get('manifest_id');
        $customer_id = Yii::$app->request->get('customer_id');
        $name = Yii::$app->request->get('name');
        $email = Yii::$app->request->get('email');
        $phone = Yii::$app->request->get('phone');
        $tracking_code = Yii::$app->request->get('tracking_code');
        $package_code = Yii::$app->request->get('package_code');
        $product_id = Yii::$app->request->get('product_id');
        $order_code = Yii::$app->request->get('order_code');
        $type_tracking = Yii::$app->request->get('type_tracking');
        $tab_tracking = Yii::$app->request->get('tab_tracking');
        $status = Yii::$app->request->get('status');
        $query = Package::find()
            ->with(['order','product','manifest.receiveWarehouse'])
            ->leftJoin('product','product.id = package.product_id')
            ->leftJoin('order','order.id = package.order_id')
            ->active();
        if($tracking_code){
            $query->whereLikeMore('package.tracking_code' , $tracking_code);
        }
        if($package_code){
            $query->whereLikeMore('package.package_code' , $package_code);
        }
        if($product_id){
            $query->andWhere(['product.id' => $product_id]);
        }
        if($manifest_id){
            $query->andWhere(['package.manifest_id' => $manifest_id]);
        }
        if($order_code){
            $query->whereLikeMore('order.ordercode' , $order_code);
        }
        if($type_tracking){
            $query->andWhere(['package.type_tracking' => $type_tracking]);
        }
        if($customer_id){
            $query->andWhere(['order.customer_id' => $customer_id]);
        }
        if($phone){
            $query->andWhere(['like', 'order.receiver_phone' , $phone]);
        }
        if($name){
            $query->andWhere(['like', 'order.receiver_name' , $name]);
        }
        if($email){
            $query->andWhere(['like', 'order.receiver_email' , $email]);
        }
        if($tab_tracking == 'complete'){
            $query->andWhere(['<>','package.type_tracking' , 'UNKNOWN']);
//            $query->andWhere(['and',['is not','product_id',null],['<>','product_id',''],['<>','status',Package::STATUS_SPLITED]]);
        }
        if($status){
            if($status == 'created'){
                $query->andWhere(['and',['is not','package.delivery_note_code' , null],['<>','package.delivery_note_code' , '']]);
            }
            if($status == 'not_create'){
                $query->andWhere(['or',['package.delivery_note_code' => null],['package.delivery_note_code' => '']]);
            }
        }
        $data['total'] = $query->count();
        if($limit != 'all'){
            $query->limit($limit)->offset($limit*$page-$limit);
        }
        $data['data'] = $query->orderBy('id desc')->asArray()->all();
        return $this->response(true, 'get data success', $data);
    }
    public function actionView($id)
    {
        if ($id) {
            $package = Package::find()->where(['order_id' => (int)$id])->with('shipment')->asArray()->all();
            return $this->response(true, 'success', $package);
        }
    }

    public function actionDelete($id)
    {
        $post = Yii::$app->request->post();
        $model = Package::findOne($id);
        if (!$model->delete()) {
            Yii::$app->wsLog->push('order', 'deletePackageItem', null, [
                'id' => $model->order->ordercode,
                'request' => $this->post,
            ]);
            return $this->response(false, 'delete package' . $id . 'error');
        }
        $dirtyAttributes = $model->getDirtyAttributes();
        $messages = "order {$model->order->ordercode} Delete Package {$this->resolveChatMessage($dirtyAttributes,$model)}";
        ChatHelper::push($messages, $model->order->ordercode, 'GROUP_WS', 'SYSTEM');
        Yii::$app->wsLog->push('order', 'deletePackage', null, [
            'id' => $model->order->ordercode,
            'request' => $this->post,
            'response' => $dirtyAttributes,
        ]);
        return $this->response(true, 'delete package' . $id . 'success');
    }

    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        $model = new Package();
        $model->weight = $post['weight'];
        $model->quantity = $post['quantity'];
        $model->dimension_l = $post['dimension_l'];
        $model->dimension_h = $post['dimension_h'];
        $model->dimension_w = $post['dimension_w'];
        $model->price = $post['price'];
        $model->order_id = $post['order_id'];
        $model->tracking_code = $post['tracking_code'];
        $model->created_by = Yii::$app->getFormatter()->asTimestamp('now');
        $dirtyAttributes = $model->getDirtyAttributes();
        $messages = "order {$model->order->ordercode} Create Package {$this->resolveChatMessage($dirtyAttributes,$model)}";
        if (!$model->save()) {
            Yii::$app->wsLog->push('order', 'createPackageItem', null, [
                'id' => $model->order->ordercode,
                'request' => $this->post,
                'response' => $model->getErrors()
            ]);
            return $this->response(false, 'create package error');
        }
        ChatHelper::push($messages, $model->order->ordercode, 'GROUP_WS', 'SYSTEM');
        Yii::$app->wsLog->push('order', 'Create Package', null, [
            'id' => $model->order->ordercode,
            'request' => $this->post,
            'response' => $dirtyAttributes
        ]);
        return $this->response(true, "create package {$messages}", $model);
    }

    public function actionUpdate($id)
    {
        $post = Yii::$app->request->post();
        $model = Package::findOne($id);
        if ($model) {
            $model->weight = $post['weight'];
            $model->quantity = $post['quantity'];
            $model->dimension_l = $post['dimension_l'];
            $model->dimension_h = $post['dimension_h'];
            $model->dimension_w = $post['dimension_w'];
            $model->updated_by = Yii::$app->getFormatter()->asTimestamp('now');
            $dirtyAttributes = $model->getDirtyAttributes();
            $messages = "order {$model->order->ordercode} update package {$this->resolveChatMessage($dirtyAttributes,$model)}";
            if (!$model->save()) {
                Yii::$app->wsLog->push('order', 'updatePackage', null, [
                    'id' => $model->order->ordercode,
                    'request' => $this->post,
                    'response' => $model->getErrors()
                ]);
                return $this->response(false, $messages);
            }
            ChatHelper::push($messages, $model->order->ordercode, 'GROUP_WS', 'SYSTEM');
            Yii::$app->wsLog->push('order', 'update package', null, [
                'id' => $model->order->ordercode,
                'request' => $this->post,
                'response' => $this->resolveChatMessage($dirtyAttributes,$model)
            ]);
            return $this->response(true, $messages);
        }
    }

    protected function resolveChatMessage($dirtyAttributes, $reference)
    {

        $results = [];
        foreach ($dirtyAttributes as $name => $value) {
            if (strpos($name, '_id') !== false && is_numeric($value)) {
                continue;
            }
            $results[] = "`{$reference->getAttributeLabel($name)}` changed from `{$reference->getOldAttribute($name)}` to `$value`";
        }

        return implode(", ", $results);
    }
}