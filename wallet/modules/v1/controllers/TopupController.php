<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 10/05/2018
 * Time: 10:33
 */

namespace wallet\modules\v1\controllers;


use common\components\RedisQueue;
use common\models\Address;
use wallet\modules\v1\models\form\CallBackPaymentForm;
use wallet\modules\v1\models\form\TopUpForm;
use wallet\modules\v1\models\WalletClient;
use wallet\modules\v1\models\WalletMerchant;
use wallet\modules\v1\models\WalletTransaction;
use Yii;

class TopupController extends WalletServiceController
{
    /**
     *@return string
     */
    public function actionCreate(){

        $user =Yii::$app->user->getIdentity();
        $post = Yii::$app->request->post();
        if(!$user){
            return $this->response(false,'Please verify identity!',[]);
        }
        $topup = new TopUpForm();
        //$topup->merchant_id = $user->id;
//        $topup->amount_total = $post['amount_total'];
//        $topup->payment_provider = $post['payment_provider'];
//        $topup->payment_provider = $post['payment_provider'];
//        $topup->method = $post['method'];
//        $topup->bank_code = $post['bank_code'];
        $topup->load(Yii::$app->request->post(),'');
        \Yii::info('$post Params:' . json_encode($post), __METHOD__);
        $rs =  $topup->topUpRequest();
        \Yii::info('topUpRequest:' . json_encode($rs), __METHOD__);
        if($rs != false){
            return $this->response(true,'ok',$rs);
        }
    }

    public function actionPushtotopup(){
        $post = Yii::$app->request->post();
        \Yii::info('$post Params:' . json_encode($post), __METHOD__);
        $queue = new RedisQueue(Yii::$app->params['QUEUE_TRANSACTION_WALLET_NL']);
        $queue->push($post);

        $checkPayment = new CallBackPaymentForm([
            'wallet_transaction_code' => $post['wallet_transaction_code'],
            'payment_transaction' => $post['payment_transaction']
        ]);

        $checkPayment = $checkPayment->actionReturn();
        $data['checkPayment']=$checkPayment;
        $data['post']=$post;

        \Yii::info('$data:' . json_encode($data), __METHOD__);
        return $this->renderJSON(true,'đã nhân mã giao dịch, hệ thống sẽ cập nhật sau ít phut',$data);
    }

    public function actionGettransaction(){
        $user =Yii::$app->user->getIdentity();
        $post = Yii::$app->request->post();

        if(empty($post['wallet_transaction_code'])){
            return $this->renderJSON(false,'wallet_transaction_code fail',$post);
        }
        $model = WalletTransaction::find()->where(['wallet_transaction_code'=>$post['wallet_transaction_code']])
            ->one();
        $walletMerchant = WalletMerchant::findOne($model->wallet_merchant_id);
        $walletClient = WalletClient::findOne($model->wallet_client_id);
        $address = Address::FindbyCustomerId($user->customer_id);
        $data['WalletTransaction'] = $model;
        $data['WalletMerchant'] = $walletMerchant;
        $data['WalletClient'] = $walletClient->current_balance;
        $data['address'] = !empty($address[0])?$address[0]:'';

        \Yii::info('WalletTransaction:' . json_encode($model), __METHOD__);
//        if(!$model || $model->wallet_client_id !== $user->id ){
//            return $this->response(false,'fail',[],400,0);
//        }
        return $this->response(true,'ok',$data,200,1);

    }

    public function actionCanceltransaction(){

        $user =Yii::$app->user->getIdentity();
        $post = Yii::$app->request->post();

        if(empty($post['wallet_transaction_code'])){
            return $this->renderJSON(false,'wallet_transaction_code fail',$post);
        }

        $model = WalletTransaction::find()->where(['wallet_transaction_code'=>$post['wallet_transaction_code']])
            ->andWhere(['type'=>WalletTransaction::TYPE_TOP_UP])
            ->one();
        $model->_wallet_client = WalletClient::findOne($model->wallet_client_id);
        $data = null;
        if($model->status == WalletTransaction::STATUS_QUEUE){
            $data = $model->updateTransaction(WalletTransaction::STATUS_CANCEL);
        }
//        $walletMerchant = WalletMerchant::findOne($model->wallet_merchant_id);
//        $walletClient = WalletClient::findOne($model->wallet_client_id);
//        $address = Address::FindbyCustomerId($user->customer_id);
//        $data['WalletTransaction'] = $model;
//        $data['WalletMerchant'] = $walletMerchant;
//        $data['WalletClient'] = $walletClient->current_balance;
//        $data['address'] = !empty($address[0])?$address[0]:'';
//        \Yii::info('WalletTransaction:' . json_encode($model), __METHOD__);
        return $this->response(true,'ok',$data,200,1);

    }


}