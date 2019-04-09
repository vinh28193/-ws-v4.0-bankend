<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 3/26/2019
 * Time: 4:12 PM
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\helpers\ChatHelper;
use common\models\Order;
use common\models\User;
use Yii;

class SaleController extends BaseApiController
{
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => $this->getAllRoles(true),
            ],
            [
                'allow' => true,
                'actions' => ['assign'],
                'roles' => ['sale', 'master_sale'],
            ],
        ];
    }

    protected function verbs()
    {
        return [
            'index' => ['GET', 'POST'],
            'assign' => ['PUT', 'POST']
        ];
    }

    public function actions()
    {
        return array_merge(parent::actions(), [
            'index' => \common\actions\SaleAction::className()
        ]);
    }

    public function actionAssign($id)
    {
        if (($model = Order::findOne($id)) === null) {
            return $this->response(false, "not found order $id");
        }
        $model->setScenario(Order::SCENARIO_SALE_ASSIGN);
        if (!isset($this->post['sale_support_id']) || ($saleId = $this->post['sale_support_id']) === null || $saleId === '') {
            return $this->response(false, 'Invalid sale');
        }
        if (($sale = User::findOne($saleId)) === null) {
            return $this->response(false, 'Not found sale ' . $saleId);
        }
        /** @var $user \common\models\User */
        $user = Yii::$app->getUser()->getIdentity();
        /** @var  $sale \common\models\User */
        $model->sale_support_id = $sale->id;
        $model->support_email = $sale->email;
        if (!$model->save()) {
            return $this->response(false, $model->getFirstErrors());
        }
        $role = Yii::$app->authManager->getRolesByUser($user->id);
        $message = '';
        if (!empty($role)) {
            $role = array_keys($role);
            $role = reset($role);
            $message .= "$role ";
        }
        $message .= "{$user->username} assign order {$model->ordercode} to {$sale->username}";
        Yii::$app->wsLog->push('order', 'assign', null, [
            'id' => $model->ordercode,
            'request' => $saleId,
            'response' => $message
        ]);
        ChatHelper::push($message, $model->ordercode, 'GROUP_WS', 'SYSTEM');
        return $this->response(true, $message, [
            'id' => $model->sale_support_id,
            'username' => $sale->username,
        ]);
    }
}