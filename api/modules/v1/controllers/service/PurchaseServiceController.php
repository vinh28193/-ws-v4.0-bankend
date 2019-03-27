<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 27/03/2019
 * Time: 2:57 CH
 */

namespace api\modules\v1\controllers\service;


use api\modules\v1\controllers\PurchaseController;
use common\models\db\ListAccountPurchase;
use common\models\db\PurchasePaymentCard;
use Yii;

class PurchaseServiceController extends PurchaseController
{
    protected function verbs()
    {
        return [
            'get-list-account' => ['GET'],
            'get-list-card-payment' => ['GET']
        ];
    }

    public function actionGetListAccount(){
        $type = Yii::$app->request->get('type','all');
        $account = ListAccountPurchase::find()->where(['active' => 1]);
        if($type !== 'all'){
            $account->andWhere(['type' => strtolower($type)]);
        }
        $account = $account->asArray()->all();
        return $this->response(true,"SUccess" , $account);
    }
    public function actionGetListCardPayment(){
        $storeId = Yii::$app->request->get('store',1);
        $storeId = $storeId ? $storeId : 1;
//        $list_data = PurchasePaymentCard::find()->where(['store_id' => $storeId , 'status' => 1])->asArray()->all();
        $list_data = PurchasePaymentCard::find()->where(['status' => 1])->asArray()->all();
        return $this->response(true,"Success" , $list_data);
    }
}
