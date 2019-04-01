<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 4/1/2019
 * Time: 10:38 AM
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\models\db\Coupon;
use Yii;

class CouponController extends BaseApiController
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
    public function actionIndex() {
        $model = Coupon::find()
            ->select(['name', 'id'])
            ->asArray()->all();
        return $this->response(true, 'get data success', $model);
    }

    public function actionView($id) {
        $model = Coupon::find()
            ->where(['id' => $id])
            ->asArray()->all();
        return $this->response(true, 'get data success', $model);
    }

    public function  actionUpdate($id) {
        $post = \Yii::$app->request->post();
        $model = Coupon::findOne($id);
        if (isset($post['start_time'])) {

        }
        if (isset($post['start_time'])) {

        }
        if (isset($post['start_time'])) {
            $model->start_time = $post['start_time'];
        }
        if (isset($post['end_time'])) {
            $model->end_time = $post['end_time'];
        }
        if (isset($post['name'])) {
            $model->name = $post['name'];
        }
        if (isset($post['code'])) {
            $model->code = $post['code'];
        }
        if (isset($post['limit_amount_use'])) {
            $model->limit_amount_use = $post['limit_amount_use'];
        }
        if (isset($post['limit_amount_use_order'])) {
            $model->limit_amount_use_order = $post['limit_amount_use_order'];
        }
        if (isset($post['message'])) {
            $model->message = $post['message'];
        }
        $dirtyAttributes = $model->getDirtyAttributes();
        if (!$model->save()) {
            Yii::$app->wsLog->order->push('updateCoupon', null, [
                'id' => $post['ordercode'],
                'request' => $this->post,
                'response' => $model->getErrors()
            ]);
            return $this->response(false, 'update coupon error');
        }
        Yii::$app->wsLog->order->push('updateCoupon', null, [
            'id' => $post['ordercode'],
            'request' => $this->post,
            'response' => $dirtyAttributes
        ]);
        return $this->response(true, 'update coupon success', $model);
    }

}