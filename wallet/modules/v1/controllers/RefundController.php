<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 21/05/2018
 * Time: 10:06
 */

namespace wallet\modules\v1\controllers;
use Codeception\Exception\Fail;
use common\components\RedisQueue;
use common\models\model\Order;
use common\models\payment\CallBackPayment;
use common\models\payment\vietnam\NganLuong;
use common\models\User;
use wallet\modules\v1\models\enu\ResponseCode;
use wallet\modules\v1\models\form\CallBackPaymentForm;
use wallet\modules\v1\models\form\RefundRequestForm;
use wallet\modules\v1\models\form\TopUpForm;
use wallet\controllers\ServiceController;
use wallet\modules\v1\controllers\WalletServiceController;
use wallet\modules\v1\models\form\TransactionForm;
use wallet\modules\v1\models\WalletClient;
use Yii;

class RefundController extends WalletServiceController
{
    public function actionCreate(){
        @date_default_timezone_set("Asia/Ho_Chi_Minh");
        $user =Yii::$app->user->getIdentity();
        $post = Yii::$app->request->post();
        $post['oldOrder'];
        $Order = Order::findOne($post['oldOrder']);
        $Order->CustomerId;
        $WalletClient =WalletClient::getByCustomerId($Order->CustomerId,true);
        if(isset($WalletClient['code']) && $WalletClient['code'] == ResponseCode::ERROR){
            return $this->renderJSON(false,'Fail',$WalletClient);
        }


        $orderPayment = json_decode($post['orderPayment']);
        $note = '';
        foreach ($orderPayment->order_payment_request as $key=>$value){
            $note.= 'Note: '.$value->process_note.' ';
        }

        $note.=$orderPayment->update_reason;

        $modelRefund = new RefundRequestForm([
            'wallet_client_id' => $WalletClient->id
        ]);
        $merchant_id = 1;
        if(in_array($WalletClient->id,[1,3])){
            $merchant_id = 4;
        }
        $modelRefund->amount_total = round($orderPayment->total_local_amount);
        $modelRefund->merchant_id = $merchant_id;
        $modelRefund->note = $note;
        $modelRefund->payment_method_id = 44;
        $modelRefund->payment_provider_id =16;
        $modelRefund->merchant_id =4;
        $modelRefund->order_number = $Order->binCode;
        $rs = $modelRefund->refundRequest();
        if($rs['code'] == ResponseCode::SUCCESS){
            //$rs = $modelRefund->refund();
            return $this->renderJSON(true,'ok',$rs);

        }

        return $this->renderJSON(false,'Fail',$rs);
    }
}