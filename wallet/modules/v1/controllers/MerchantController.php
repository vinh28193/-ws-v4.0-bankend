<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 5/28/2018
 * Time: 10:08 AM
 */

namespace wallet\modules\v1\controllers;


use wallet\modules\v1\models\WalletMerchant;
use wallet\modules\v1\models\WalletTransaction;

class MerchantController extends WalletServiceController
{
    public function actionIndex()
    {
        $post = \Yii::$app->request->post();
        $q = WalletMerchant::find();
        $count = 0;
        if (isset($post['limit'])) {
            $count = $q->count();
            $q->offset($post['offset'])->limit($post['limit']);
        }
        if (isset($post['order_by'])) {
            $q->orderBy($post['order_by']);
        } else {
            $q->orderBy('id DESC');
        }
        $data = $q->all();
        return $this->response(true, 'Get success' , $data,200, $count);
    }

    /**
     * @return mixed
     */
    public function actionGetTotalTransaction()
    {
        $total = 0;
        $post = \Yii::$app->request->post();
        if (isset($post['idWallet'])) {
            $total = WalletTransaction::find()->where(['wallet_merchant_id'=>$post['idWallet']])->count();
        }
        return $this->response(false, 'Get total for id - '.$post['idWallet'], $total, 200, 1);;
    }
}