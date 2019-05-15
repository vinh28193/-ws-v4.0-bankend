<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 5/28/2018
 * Time: 9:45 AM
 */

namespace wallet\modules\v1\controllers;


use wallet\modules\v1\models\WalletClient;
use wallet\modules\v1\models\WalletTransaction;
use Yii;

class ClientsController extends WalletServiceController
{
    public function actionIndex()
    {
        $post = \Yii::$app->request->post();
        $q = WalletClient::find();
        $count = $q->count();
        if(isset($post['keyword']) && !empty($post['keyword'])){
            $keyword = $post['keyword'];
            $q->where(['or',
                ['id'=>$keyword],
                ['customer_id'=>$keyword],
                ['like', 'email', $keyword],
                ['like', 'username', $keyword],
                ['like', 'customer_phone', $keyword],
            ]);
        }
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
        return $this->response(true, 'Get success', $data, 200, $count);
    }

    /**
     * @return array
     */
    public function actionDetail()
    {
        $data = WalletClient::findOne(\Yii::$app->user->id);
        $count = 1;
        if(!empty($data)){
            return $this->response(true, 'Get success', $data, 200, $count);
        }
        return $this->response(false, 'Get fail', [], 400, $count);

    }

    /**
     * @return mixed
     */
    public function actionGetTotalTransaction()
    {
        $total = 0;
        $post = \Yii::$app->request->post();
        if (isset($post['idWallet'])) {
            $total = WalletTransaction::find()->where(['wallet_client_id'=>$post['idWallet']])->count();
        }
        return $this->response(false, 'Get total for id - '.$post['idWallet'], $total, 200, 1);;
    }
}