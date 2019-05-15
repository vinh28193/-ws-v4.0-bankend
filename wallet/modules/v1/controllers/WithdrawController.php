<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/06/2018
 * Time: 03:27 PM
 */

namespace wallet\modules\v1\controllers;


use wallet\modules\v1\models\form\ChangeBalanceForm;
use wallet\modules\v1\models\form\WithDrawForm;
use wallet\modules\v1\models\WalletTransaction;
use Yii;

class WithdrawController extends WalletServiceController
{
    public function actionCreate(){

        $user =Yii::$app->user->getIdentity();
        $post = Yii::$app->request->post();

        $withdraw = new WithDrawForm();
        $withdraw->load(Yii::$app->request->post(),'');
        \Yii::info('$post Params:' . json_encode($post), __METHOD__);
        $rs =  $withdraw->withDrawRequest();
        $data['wallet_transaction_code'] = $rs;
        \Yii::info('topUpRequest:' . json_encode($data), __METHOD__);
        if($rs != false){
            $tran = WalletTransaction::find()->where(['wallet_transaction_code' => $rs['data']['code']])->one();
            if($tran && $tran->status === 0){
                $isChange = new ChangeBalanceForm();
                $isChange->amount = $post['amount_total'];
                $isChange->walletTransactionId = $tran->id;
                $res =  $isChange->freeze(true);
                if($res['success']){
                    return $this->renderJSON(true,'ok',$data);
                }else{
                    return $this->renderJSON(false,$res['message'],$data);
                }
            }else{
                return $this->renderJSON(false,'Transaction invalid',$data);
            }

        }
    }
}