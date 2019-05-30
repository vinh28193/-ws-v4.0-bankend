<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 5/30/2018
 * Time: 3:58 PM
 */

namespace wallet\modules\v1\controllers;


use Yii;
use wallet\modules\v1\models\WalletClient;
use wallet\modules\v1\models\enu\ResponseCode;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class WalletController extends WalletServiceController
{

    public function actionTestConnection()
    {
        return $this->response(true, 'You are connected', Yii::$app->user->identity, ResponseCode::SUCCESS, 1);
    }

    public function actionInformation()
    {
        $request = Yii::$app->request->post();
        $identity = Yii::$app->user->identity;

        $data = [];
        $attribute = [];
        $attribute = ArrayHelper::merge($attribute, [
           'id', 'username', 'email', 'customer_phone', 'customer_name',
            'current_balance', 'freeze_balance', 'usable_balance', 'withdrawable_balance',
            'total_refunded_amount', 'total_topup_amount', 'total_using_amount', 'total_withdraw_amount',
            'last_refund_amount', 'last_topup_amount', 'last_using_amount', 'last_withdraw_amount', 'current_bulk_balance'
        ]);
        if(isset($request['id']) && !empty($request['id'])){
            $model = $this->findModel($request['id'], $attribute);
        }else{
            $model = $this->findModel($identity->getId(), $attribute);
        }
        return $this->response(true, 'get data success', $model->getAttributes($attribute), ResponseCode::SUCCESS, 1);

    }

    /**
     * @param $id
     * @param string $selects
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {

        $query = WalletClient::find();;
//        $query->with([
//            ''
//        ]);
        $query->where([
            'AND',
            ['id' => $id],
            ['status' => WalletClient::STATUS_ACTIVE]
        ]);
        if (($model = $query->one()) !== null) {
            return $model;
        }
        throw  new NotFoundHttpException('Not found wallet client id:' . $id);
    }
}