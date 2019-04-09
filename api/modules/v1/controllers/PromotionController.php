<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 4/9/2019
 * Time: 11:55 AM
 */

namespace api\modules\v1\controllers;


use common\models\db\Promotion;
use api\controllers\BaseApiController;
use Yii;
use common\helpers\ChatHelper;

class PromotionController  extends BaseApiController
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
        $model = Promotion::find()->asArray()->all();
        return $this->response(true, 'get data success', $model);
    }

    public function actionView($id) {
        $model = Promotion::find()
            ->where(['id' => $id])
            ->asArray()->all();
        return $this->response(true, 'get data success', $model);
    }

    public function  actionUpdate($id) {
        $post = \Yii::$app->request->post();
        $model = Promotion::findOne($id);
        if (isset($post['conditionStartTime'])) {
            $model->conditionStartTime = $post['conditionStartTime'];
        }
        if (isset($post['conditionEndTime'])) {
            $model->conditionEndTime = $post['conditionEndTime'];
        }
        if (isset($post['name'])) {
            $model->name = $post['name'];
        }
        if (isset($post['code'])) {
            $model->code = $post['code'];
        }
        if (isset($post['discountAmount'])) {
            $model->discountAmount = $post['discountAmount'];
        }
        if (isset($post['discountOverWeight'])) {
            $model->discountOverWeight = $post['discountOverWeight'];
        }
        if (isset($post['message'])) {
            $model->message = $post['message'];
        }
        if (isset($post['conditionLimitUsageCount'])) {
            $model->conditionLimitUsageCount = $post['conditionLimitUsageCount'];
        }
        if (isset($post['conditionLimitUsageAmount'])) {
            $model->conditionLimitUsageAmount = $post['conditionLimitUsageAmount'];
        }
        if (isset($post['conditionLimitByCustomerUsageCount'])) {
            $model->conditionLimitByCustomerUsageCount = $post['conditionLimitByCustomerUsageCount'];
        }
        if (isset($post['conditionLimitByCustomerUsageAmount'])) {
            $model->conditionLimitByCustomerUsageAmount = $post['conditionLimitByCustomerUsageAmount'];
        }
        $dirtyAttributes = $model->getDirtyAttributes();
        $messages = "order {$post['ordercode']} Update Promotion {$this->resolveChatMessage($dirtyAttributes,$model)}";
        if (!$model->save()) {
            Yii::$app->wsLog->order->push('updateCoupon', null, [
                'id' => $post['ordercode'],
                'request' => $this->post,
                'response' => $model->getErrors()
            ]);
            return $this->response(false, 'update coupon error');
        }
        ChatHelper::push($messages, $post['ordercode'],'GROUP_WS', 'SYSTEM');
        Yii::$app->wsLog->push('order','updateCoupon', null, [
            'id' => $post['ordercode'],
            'request' => $this->post,
            'response' => $dirtyAttributes
        ]);
        return $this->response(true, 'update promotion success', $model);
    }

    protected function resolveChatMessage($dirtyAttributes,$reference)
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