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
                'actions' => ['index'],
                'roles' => ['operation','master_operation']
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
        $tracking_code = Yii::$app->request->get('tracking_code');
        $sku = Yii::$app->request->get('sku');
        $order_code = Yii::$app->request->get('order_code');
        $type_tracking = Yii::$app->request->get('type_tracking');
        $query = Package::find()
            ->with(['order','manifest.receiveWarehouse'])
            ->leftJoin('product','product.id = package.product_id')
            ->leftJoin('order','order.id = package.order_id');
        if($tracking_code){
            $query->andWhere(['like' , 'package.tracking_code' , $tracking_code]);
        }
        if($sku){
            $query->andWhere(['or',['like','product.sku',$sku],['like','product.parent_sku',$sku]]);
        }
        if($order_code){
            $query->andWhere([['like','order.ordercode',$order_code]]);
        }
        if($type_tracking){
            $query->andWhere([['package.type_tracking',$type_tracking]]);
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
                'id' => $model->$post['ordercode'],
                'request' => $this->post,
            ]);
            return $this->response(false, 'delete package' . $id . 'error');
        }
        $dirtyAttributes = $model->getDirtyAttributes();
        $messages = "order {$model->$post['ordercode']} Delete Package {$this->resolveChatMessage($dirtyAttributes,$model)}";
        ChatHelper::push($messages, $model->$post['ordercode'], 'GROUP_WS', 'SYSTEM');
        Yii::$app->wsLog->push('order', 'deletePackage', null, [
            'id' => $model->$post['ordercode'],
            'request' => $this->post,
        ]);
        return $this->response(true, 'delete package' . $id . 'success');
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