<?php


namespace frontend\modules\account\controllers\api;


use common\helpers\WeshopHelper;
use common\models\db\WalletClient;
use common\models\PaymentBank;
use common\models\User;
use common\models\PaymentTransaction;
use common\models\WalletTransaction;
use frontend\models\LoginForm;
use frontend\modules\payment\models\OtpVerifyForm;
use frontend\modules\payment\Payment;
use frontend\modules\payment\providers\wallet\WalletService;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\Response;

class WalletServiceController extends Controller
{
    public function actionLoginWallet()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $password = \Yii::$app->request->post('password');
        if ($password) {
            return (new WalletService())->login($password);
        }
        return ['success' => false, 'message' => Yii::t('frontend', 'Incorrect password.')];
    }

    public function actionTopup()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $bodyParams = \Yii::$app->request->post();
        $payment = new Payment($bodyParams['payment']);
        /** @var User $user */
        $user = \Yii::$app->user->getIdentity();
        $payment->customer_email = $user->email;
        $payment->customer_phone = $user->phone;
        $payment->customer_name = $user->last_name . ' ' . $user->first_name;

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
        $paymentTransaction->shipping = 0;
        if ($paymentTransaction->save(false)) {
            $wallet = new WalletService([
                'transaction_code' => $paymentTransaction->transaction_code,
                'total_amount' => $paymentTransaction->transaction_amount_local,
                'payment_provider' => $paymentTransaction->payment_provider,
                'payment_method' => $paymentTransaction->payment_method,
                'bank_code' => $paymentTransaction->payment_bank_code,
            ]);
            $results = $wallet->topUpTransaction();
            \Yii::info($results, 'Wallet_SERVICE_63');
            if ($results['success'] === true && isset($results['data']) && isset($results['data']['data']['code'])) {
                $topUpCode = $results['data']['data']['code'];
                $paymentTransaction->updateAttributes([
                    'topup_transaction_code' => $topUpCode
                ]);
                $payment->transaction_code = $topUpCode;
            } else {
                return ['success' => false, 'message' => Yii::t('frontend', 'Can not create transaction top up.')];
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
        WalletTransaction::updateAll(['payment_transaction' => $res['data']['token']], ['wallet_transaction_code' => $payment->transaction_code]);
        return ['success' => true, 'message' => Yii::t('frontend', 'Create top up success'), 'data' => $res['data']];
    }

    public function actionReturn($merchant)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $res = Payment::checkPayment((int)$merchant, Yii::$app->request);
        $order = Yii::$app->request->post('order_code');
        if ($order) {
            return Yii::$app->response->redirect('/my-weshop/wallet/transaction/' . $order . '/detail.html');
        }
        return Yii::$app->response->redirect(Url::toRoute("/account/wallet/history", true));
    }

    public function actionWithdraw()
    {
        $request = Yii::$app->request->post();
        /** @var User $user */
        $user = Yii::$app->user->getIdentity();
        if (!($pass = ArrayHelper::getValue($request, 'password'))) {
            return $this->response(false, Yii::t('frontend', 'Please confirm your password '));
        }

        if (!$user->validatePassword($pass)) {
            return $this->response(false, Yii::t('frontend', 'Incorrect password. Please check again'));
        }

        $walletS = new WalletService();

        if (!($walletS->payment_method = ArrayHelper::getValue($request, 'method'))) {
            return $this->response(false, Yii::t('frontend', 'Please choose the withdrawal method'));
        }

        if ($walletS->payment_method == 'NL') {
            if (!($walletS->cardnumber = ArrayHelper::getValue($request, 'email'))) {
                return $this->response(false, Yii::t('frontend', 'Please fill your Ngan Luong account'));
            }
            $walletS->bank_code = 'NL';
        } elseif ($walletS->payment_method == 'BANK') {
            $bank = PaymentBank::findOne(ArrayHelper::getValue($request, 'bank_id'));
            if (!$bank) {
                return $this->response(false, Yii::t('frontend', 'Please try another bank'));
            }

            if (!($walletS->cardholderName = Yii::$app->request->post('bank_account_name')) || !($walletS->cardnumber = Yii::$app->request->post('bank_account_number'))) {
                return $this->response(false, Yii::t('frontend', 'Please complete the information of the account holder receiving the money'));
            }
            $walletS->bank_code = $bank->code;
        } else {
            return $this->response(false, Yii::t('frontend', 'Please choose another withdrawal method'));
        }

        if (!($walletS->total_amount = ArrayHelper::getValue($request, 'total_amount')) || $walletS->total_amount < 100000) {
            return $this->response(false, Yii::t('frontend', 'Please enter the amount you want to withdraw greater than {amount}', [
                'amount' => '100,000'
            ]));
        }

        $walletS->amount = ArrayHelper::getValue($request, 'amount');
        $walletS->fee = ArrayHelper::getValue($request, 'fee');
        /** @var WalletClient $wallet */
        $wallet = WalletClient::find()->where(['customer_id' => $user->id, 'status' => 1])->one();
        if (!$wallet || $wallet->withdrawable_balance < $walletS->total_amount) {
            return $this->response(false, Yii::t('frontend', 'Your maximum withdrawal amount is only allowed {withdrawable_balance}', [
                'withdrawable_balance' => WeshopHelper::showMoney($wallet->withdrawable_balance)
            ]));
        }
        try {
            $rs = $walletS->createWithdraw();
            $trancode = $rs['data']['wallet_transaction_code'];
            return $this->response(true, $trancode['message'], $trancode['data']);
        } catch (\Exception $exception) {
            Yii::debug($exception);
            return $this->response(false, Yii::t('frontend', 'There was an error creating the withdrawal request. Please try again.'));
        }

    }

    public function response($success = false, $mess = null, $data = [])
    {
        if ($mess === null) {
            $mess = Yii::t('frontend', 'Failed');
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['success' => $success, 'message' => $mess, 'data' => $data];
    }

    public function actionSentOtp()
    {
        $walletS = new WalletService();
        $walletS->transaction_code = Yii::$app->request->post('transaction_code');
        $walletS->otp_type = Yii::$app->request->post('type', 1);
        $walletS->refreshOtp();
        return $this->response(true, 'sent otp success!', ['code' => $walletS->transaction_code]);
    }

    public function actionVerifyOtp()
    {
        $request = Yii::$app->request;
        $otpForm = new OtpVerifyForm();
        if ($request->isPost && $otpForm->load($request->post()) && $otpForm->verify()) {
            $service = new WalletService();
            $service->transaction_code = $otpForm->transactionCode;
            $service->otp_code = $otpForm->otpCode;
            $service->validateOtp();
            return Yii::$app->response->redirect('/my-weshop/wallet/withdraw/' . $service->transaction_code . '.html');
        }
        return $this->response(false, Yii::t('frontend', 'Verify fail'));
    }

    public function actionCancelWithdraw()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $walletS = new WalletService();
        $walletS->transaction_code = Yii::$app->request->post('transaction_code');
        if (!$walletS->transaction_code) {
            return $this->response(false, Yii::t('frontend', 'Not found'));
        }
        return $walletS->cancelWithdraw();
    }
}