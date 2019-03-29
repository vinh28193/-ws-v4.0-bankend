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

    public function actionView($id) {
        $query = PackageItem::find()->where(['order_id' => $id])->asArray()->all();
        return $this->response(true, 'get data success', $query);
    }

    public function actionCreate() {
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
        if (!$model->save()) {
            Yii::$app->wsLog->order->push('createPackageItem', null, [
                'id' => $post['ordercode'],
                'request' => $this->post,
                'response' => $model->getErrors()
            ]);
            return $this->response(false, 'create package item error');
        }
        Yii::$app->wsLog->order->push('createPackageItem', null, [
            'id' => $post['ordercode'],
            'request' => $this->post,
            'response' => $dirtyAttributes
        ]);
        return $this->response(true, 'create package item success', $model);
    }

    public function actionUpdate($id) {
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
            if (!$model->save()) {
                Yii::$app->wsLog->order->push('updatePackageItem', null, [
                    'id' => $post['ordercode'],
                    'request' => $this->post,
                    'response' => $model->getErrors()
                ]);
                return $this->response(false, 'update package item'. $id . 'error');
            }
            Yii::$app->wsLog->order->push('createPackageItem', null, [
                'id' => $post['ordercode'],
                'request' => $this->post,
                'response' => $dirtyAttributes
            ]);
            return $this->response(true, 'update package item' . $id .' success', $model);
        }
    }
}