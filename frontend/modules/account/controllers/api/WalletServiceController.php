<?php


namespace frontend\modules\account\controllers\api;


use common\models\db\User;
use common\models\PaymentTransaction;
use common\models\WalletTransaction;
use frontend\modules\payment\Payment;
use frontend\modules\payment\providers\wallet\WalletService;
use Yii;
use yii\base\Controller;
use yii\helpers\Url;
use yii\web\Response;

class WalletServiceController extends Controller
{
    public function actionLoginWallet(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $password = \Yii::$app->request->post('password');
        if($password){
            return (new WalletService())->login($password);
        }
        return ['success' => false , 'message' => 'Mật khẩu không đúng'];
    }
    public function actionTopup(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $bodyParams = \Yii::$app->request->post();
        $payment = new Payment($bodyParams['payment']);
        /** @var User $user */
        $user = \Yii::$app->user->getIdentity();
        $payment->customer_email = $user->email;
        $payment->customer_phone = $user->phone;
        $payment->customer_name = $user->last_name . ' ' .$user->first_name;

        $payment->createTransactionCode();
        $payment->total_amount_display = $payment->total_amount;
        $paymentTransaction = new PaymentTransaction();
        $paymentTransaction->customer_id = $user->id;
        $paymentTransaction->store_id = $payment->storeManager->getId();
        $paymentTransaction->transaction_type = PaymentTransaction::TRANSACTION_TYPE_TOP_UP;
        $paymentTransaction->carts = '';
        $paymentTransaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_CREATED;
        $paymentTransaction->transaction_code = $payment->transaction_code;
        $paymentTransaction->transaction_customer_name = $payment->customer_name;
        $paymentTransaction->transaction_customer_email = $payment->customer_email;
        $paymentTransaction->transaction_customer_phone = $payment->customer_phone;
        $paymentTransaction->transaction_customer_address = $payment->customer_address;
        $paymentTransaction->transaction_customer_postcode = $payment->customer_postcode;
        $paymentTransaction->transaction_customer_address = $payment->customer_address;
        $paymentTransaction->transaction_customer_district = $payment->customer_district;
        $paymentTransaction->transaction_customer_city = $payment->customer_city;
        $paymentTransaction->transaction_customer_country = $payment->customer_country;
        $paymentTransaction->payment_provider = $payment->payment_provider_name;
        $paymentTransaction->payment_method = $payment->payment_method_name;
        $paymentTransaction->payment_bank_code = $payment->payment_bank_code;
        $paymentTransaction->coupon_code = $payment->coupon_code;
        $paymentTransaction->used_xu = $payment->use_xu;
        $paymentTransaction->bulk_point = $payment->bulk_point;
        $paymentTransaction->total_discount_amount = $payment->total_discount_amount;
        $paymentTransaction->before_discount_amount_local = $payment->total_amount;
        $paymentTransaction->transaction_amount_local = $payment->total_amount - $payment->total_discount_amount;
        $paymentTransaction->payment_type = 'top_up';
        if($paymentTransaction->save(false)){
            $wallet = new WalletService([
                'transaction_code' => $paymentTransaction->transaction_code,
                'total_amount' => $paymentTransaction->transaction_amount_local,
                'payment_provider' => $paymentTransaction->payment_provider,
                'payment_method' => $paymentTransaction->payment_method,
                'bank_code' => $paymentTransaction->payment_bank_code,
            ]);
            $results = $wallet->topUpTransaction();
            \Yii::info($results,'Wallet_SERVICE_63');
            if ($results['success'] === true && isset($results['data']) && isset($results['data']['data']['code'])) {
                $topUpCode = $results['data']['data']['code'];
                $paymentTransaction->updateAttributes([
                    'topup_transaction_code' => $topUpCode
                ]);
                $payment->transaction_code = $topUpCode;
            }else{
                return ['success' => false, 'message' => 'Không tạo được yêu cầu nạp tiền'];
            }
        }
        $res = $payment->processPayment();
        if ($res['success'] === false) {
            return ['success' => false, 'message' => $res['message']];
        }
        $paymentTransaction->third_party_transaction_code = $res['data']['token'];
        $paymentTransaction->third_party_transaction_status = $res['data']['code'];
        $paymentTransaction->third_party_transaction_link = $res['data']['checkoutUrl'];
        $paymentTransaction->save(false);
        WalletTransaction::updateAll(['payment_transaction' => $res['data']['token'],['wallet_transaction_code' => $payment->transaction_code]]);
        return ['success' => true, 'message' => 'Create TopUp success', 'data' => $res['data']];
    }
    public function actionReturn($merchant=0){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $res = Payment::checkPayment((int)$merchant, Yii::$app->request);
        if (!isset($res) || $res['success'] === false || !isset($res['data'])) {
            return Yii::$app->response->redirect(Url::toRoute("/account/wallet/index", true));
        }
        return Yii::$app->response->redirect(Url::toRoute("/account/wallet/index", true));
    }
}