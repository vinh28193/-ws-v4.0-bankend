<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-20
 * Time: 09:29
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\data\ActiveDataProvider;
use common\helpers\ChatHelper;
use common\models\PackageItem;
use Yii;

class PackageItemController extends BaseApiController
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
                'roles' => $this->getAllRoles(true)
            ],
        ];
    }

    public function actionIndex()
    {
        $requestParams = Yii::$app->getRequest()->getQueryParams();
        $query = PackageItem::find();

        $query->filterRelation();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeParam' => 'perPage',
                'params' => $requestParams,
            ],
            'sort' => [
                'params' => $requestParams,
            ],
        ]);

        $query->filter($requestParams);

        return $this->response(true, 'get data success', $dataProvider);
    }

    public function actionView($id)
    {
        $query = PackageItem::find()->where(['order_id' => $id])->asArray()->all();
        return $this->response(true, 'get data success', $query);
    }

    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        $model = new PackageItem;
        $model->package_code = $post['package_code'];
        $model->weight = $post['weight'];
        $model->quantity = $post['quantity'];
        $model->dimension_l = $post['dimension_l'];
        $model->dimension_h = $post['dimension_h'];
        $model->dimension_w = $post['dimension_w'];
        $model->price = $post['price'];
        $model->order_id = $post['order_id'];
        $model->change_weight = $post['change_weight'];
        $model->box_me_warehouse_tag = $post['box_me_warehouse_tag'];
        $dirtyAttributes = $model->getDirtyAttributes();
        $messages = "order {$post['ordercode']} Create Package Item {$this->resolveChatMessage($dirtyAttributes,$model)}";
        if (!$model->save()) {
            Yii::$app->wsLog->push('order', 'createPackageItem', null, [
                'id' => $model->order->ordercode,
                'request' => $this->post,
                'response' => $model->getErrors()
            ]);
            return $this->response(false, 'create package item error');
        }
        ChatHelper::push($messages, $model->order->ordercode, 'GROUP_WS', 'SYSTEM');
        Yii::$app->wsLog->push('order', $model->getScenario(), null, [
            'id' => $model->order->ordercode,
            'request' => $this->post,
            'response' => $dirtyAttributes
        ]);
        return $this->response(true, 'create package item success', $model);
    }

    public function actionUpdate($id)
    {
        $post = Yii::$app->request->post();
        $model = PackageItem::findOne($id);
        if ($model) {
            $model->package_code = $post['package_code'];
            $model->weight = $post['weight'];
            $model->quantity = $post['quantity'];
            $model->dimension_l = $post['dimension_l'];
            $model->dimension_h = $post['dimension_h'];
            $model->dimension_w = $post['dimension_w'];
            $dirtyAttributes = $model->getDirtyAttributes();
            $messages = "order {$model->order->ordercode} Create Package Item {$this->resolveChatMessage($dirtyAttributes,$model)}";
            if (!$model->save()) {
                Yii::$app->wsLog->push('order', 'updatePackageItem', null, [
                    'id' => $model->order->ordercode,
                    'request' => $this->post,
                    'response' => $model->getErrors()
                ]);
                return $this->response(false, $messages);
            }
            ChatHelper::push($messages, $model->order->ordercode, 'GROUP_WS', 'SYSTEM');
            Yii::$app->wsLog->push('order', 'update package item', null, [
                'id' => $model->order->ordercode,
                'request' => $this->post,
                'response' => $dirtyAttributes
            ]);
            return $this->response(true, $messages);
        }
    }

    public function actionDelete($id)
    {
        $model = PackageItem::findOne($id);
        if (!$model->delete()) {
            Yii::$app->wsLog->push('order', 'deletePackageItem', null, [
                'id' => $model->order->ordercode,
                'request' => $this->post,
            ]);
            return $this->response(false, 'delete package item' . $id . 'error');
        }
        $dirtyAttributes = $model->getDirtyAttributes();
        $messages = "order {$model->order->ordercode} Delete Package Item {$this->resolveChatMessage($dirtyAttributes,$model)}";
        ChatHelper::push($messages, $model->order->ordercode, 'GROUP_WS', 'SYSTEM');
        Yii::$app->wsLog->push('order', 'deletePackageItem', null, [
            'id' => $model->order->ordercode,
            'request' => $this->post,
        ]);
        return $this->response(true, 'update package item' . $id . 'success');
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