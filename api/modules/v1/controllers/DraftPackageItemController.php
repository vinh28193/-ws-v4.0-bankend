<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-04-03
 * Time: 11:07
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\models\draft\DraftDataTracking;
use common\models\Package;
use common\data\ActiveDataProvider;
use common\helpers\ChatHelper;
use Yii;
use common\models\PackageSearch;

/**
 * Class DraftPackageItemController
 * @package api\modules\v1\controllers
 */
class DraftPackageItemController extends BaseApiController
{
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
            'index' => ['GET']
        ];
    }

    /**
     * @return array list draft package item
     */
    public function actionIndex()
    {
        $page = \Yii::$app->request->get('p',1);
        $limit = \Yii::$app->request->get('l',20);
//        $modelSearch = new DraftPackageItemSearch();
//        $dataProvider = $modelSearch->search($this->get);
//        return $this->response(true, $modelSearch->createResponseMessage(), $dataProvider);
        $model = DraftDataTracking::find()->with([
            'draftExtensionTrackingMap',
            'trackingCode',
            'draftBoxmeTracking',
            'draftMissingTracking',
            'draftWastingTracking',
            'draftPackageItem'])->where(['is not', 'product_id', null]);
        $countD = clone $model;
        $data['_items'] = $model->limit($limit)->offset($page*$limit - $limit)->asArray()->orderBy('id desc')->all();
        $data['_total'] = $countD->count();
        return $this->response(true, "Success", $data);
    }

    public function actionView($id)
    {
        if ($id) {
            $package = Package::find()->where(['order_id' => (int)$id])->with('shipment')->asArray()->all();
            return $this->response(true, 'success', $package);
        }
    }
    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        $model = new Package();
        $model->product_id = $post['product_id'];
        $model->tracking_code = $post['tracking_code'];
        $model->weight = $post['weight'];
        $model->quantity = $post['quantity'];
        $model->dimension_l = $post['dimension_l'];
        $model->dimension_h = $post['dimension_h'];
        $model->dimension_w = $post['dimension_w'];
        $model->price = $post['price'];
        $model->order_id = $post['order_id'];
        $dirtyAttributes = $model->getDirtyAttributes();
        $messages = "order {$post['ordercode']} Create Draft Package Item {$this->resolveChatMessage($dirtyAttributes,$model)}";
        if (!$model->save()) {
            Yii::$app->wsLog->push('order', 'createDraftPackageItem', null, [
                'id' => $model->order->ordercode,
                'request' => $this->post,
                'response' => $model->getErrors()
            ]);
            return $this->response(false, 'create draft package item error');
        }
        ChatHelper::push($messages, $model->order->ordercode, 'GROUP_WS', 'SYSTEM');
        Yii::$app->wsLog->push('order', $model->getScenario(), null, [
            'id' => $model->order->ordercode,
            'request' => $this->post,
            'response' => $dirtyAttributes
        ]);
        return $this->response(true, 'create draft package item success', $model);
    }

    public function actionDelete($id)
    {
        $model = Package::findOne($id);
        if (!$model->delete()) {
            Yii::$app->wsLog->push('order', 'deleteDraftPackageItem', null, [
                'id' => $model->order->ordercode,
                'request' => $this->post,
            ]);
            return $this->response(false, 'delete draft package item' . $id . 'error');
        }
        $dirtyAttributes = $model->getDirtyAttributes();
        $messages = "order {$model->order->ordercode} Delete Draft Package Item {$this->resolveChatMessage($dirtyAttributes,$model)}";
        ChatHelper::push($messages, $model->order->ordercode, 'GROUP_WS', 'SYSTEM');
        Yii::$app->wsLog->push('order', 'deleteDraftPackageItem', null, [
            'id' => $model->order->ordercode,
            'request' => $this->post,
        ]);
        return $this->response(true, 'update draft package item' . $id . 'success');
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

    public function actionUpdate($id)
    {
        $post = Yii::$app->request->post();
        $model = Package::findOne($id);
        if ($model) {
            $model->tracking_code = $post['tracking_code'];
            $model->weight = $post['weight'];
            $model->quantity = $post['quantity'];
            $model->dimension_l = $post['dimension_l'];
            $model->dimension_h = $post['dimension_h'];
            $model->dimension_w = $post['dimension_w'];
            $dirtyAttributes = $model->getDirtyAttributes();
            $messages = "order {$model->order->ordercode} Update Draft Package Item {$this->resolveChatMessage($dirtyAttributes,$model)}";
            if (!$model->save()) {
                Yii::$app->wsLog->push('order', 'updateDraftPackageItem', null, [
                    'id' => $model->order->ordercode,
                    'request' => $this->post,
                    'response' => $model->getErrors()
                ]);
                return $this->response(false, $messages);
            }
            ChatHelper::push($messages, $model->order->ordercode, 'GROUP_WS', 'SYSTEM');
            Yii::$app->wsLog->push('order', 'update draft package item', null, [
                'id' => $model->order->ordercode,
                'request' => $this->post,
                'response' => $dirtyAttributes
            ]);
            return $this->response(true, $messages);
        }
    }
}