<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 5/30/2018
 * Time: 3:59 PM
 */

namespace wallet\modules\v1\controllers;


use common\components\RedisQueue;
use common\models\User as Customer;
use wallet\controllers\ServiceController;
use wallet\modules\v1\models\form\CallBackPaymentForm;
use wallet\modules\v1\models\WalletClient;
use Yii;

class WalletNoAuthController extends ServiceController
{
    public function actionCreateWallet()
    {
        $customer = \Yii::$app->request->post('customer');
        $client = WalletClient::findOne(['customer_id' => $customer['id']]);
        $checkCus = Customer::findOne($customer['id']);
        if ($client != null) {
            return $this->response(false . 'Customer wallet exists' . null);
        } elseif (!$checkCus) {
            return $this->response(false . 'Customer not exists' . null);
        } else {
            $client = new WalletClient();
            $client->customer_id = $checkCus->id;
            $client->password_hash = $checkCus->password_hash;
            $client->auth_key = 'wallet';
            $client->username = $checkCus->username;
            $client->email = $checkCus->email;
            $client->customer_phone = $checkCus->phone;
            $client->customer_name = $checkCus->last_name . ' ' . $checkCus->first_name;
            $client->created_at = date('Y-m-d H:i:s');
            $client->status = 1;
            $client->store_id = $checkCus->store_id;
            $client->save(false);
            return $this->response(true . 'Create wallet success' . null);
        }
    }

    public function actionPushToTopUp()
    {
        $post = Yii::$app->request->post();
        $queue = new RedisQueue(Yii::$app->params['QUEUE_TRANSACTION_WALLET_NL']);
        $queue->push($post);

        $checkPayment = new CallBackPaymentForm([
            'wallet_transaction_code' => $post['wallet_transaction_code'],
            'payment_transaction' => $post['payment_transaction']
        ]);

        $checkPayment = $checkPayment->actionReturn();
        $data['checkPayment'] = $checkPayment;
        $data['post'] = $post;
        return $this->renderJSON(true, 'đã nhân mã giao dịch, hệ thống sẽ cập nhật sau ít phut', $data);
    }
}