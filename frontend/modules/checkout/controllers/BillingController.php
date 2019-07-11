<?php


namespace frontend\modules\checkout\controllers;

use common\models\PaymentTransaction;
use frontend\modules\payment\models\Order;
use frontend\modules\payment\Payment;
use frontend\modules\payment\PaymentService;
use Yii;
use yii\web\NotFoundHttpException;

class BillingController extends CheckoutController
{
    public function actionFail($code)
    {
        $this->title = Yii::t('frontend', 'Invoice failed {code}', ['code' => $code]);
        if (($paymentTransaction = PaymentService::findParentTransaction($code)) === null) {
            throw new NotFoundHttpException("not found transaction code $code");
        }

        $payment = new Payment([
            'type' => 'invoice',
            'page' => Payment::PAGE_BILLING,
            'transaction_code' => $paymentTransaction->transaction_code,
            'customer_name' => $paymentTransaction->transaction_customer_name,
            'customer_email' => $paymentTransaction->transaction_customer_email,
            'customer_phone' => $paymentTransaction->transaction_customer_phone,
            'customer_address' => $paymentTransaction->transaction_customer_address,
            'customer_city' => $paymentTransaction->transaction_customer_city,
            'customer_postcode' => $paymentTransaction->transaction_customer_postcode,
            'customer_district' => $paymentTransaction->transaction_customer_district,
            'customer_country' => $paymentTransaction->transaction_customer_country,
            'payment_provider' => (int)$paymentTransaction->payment_provider,
            'payment_method' => (int)$paymentTransaction->payment_method,
            'payment_bank_code' => $paymentTransaction->payment_bank_code,
        ]);
        $this->title = Yii::t('frontend', 'Payment failed {code}', ['code' => $code]) . ' | ' . Yii::t('frontend', 'Payment method {method}', ['method' => implode(', ', [$payment->payment_method_name, $payment->payment_provider_name])]);
        $orders = [];
        foreach ($paymentTransaction->childPaymentTransaction as $childPaymentTransaction) {
            if (($orderParam = $childPaymentTransaction->order) !== null) {
                $order = new Order($orderParam->getAttributes());
                $order->getAdditionalFees()->loadFormActiveRecord($orderParam);
                $orders[$order->ordercode] = $order;
            }
        }
        $payment->setOrders($orders);

        return $this->render('fail', [
            'code' => $code,
            'payment' => $payment->initPaymentView(),
        ]);

    }

    public function actionSuccess($code)
    {
        $this->title = Yii::t('frontend', 'Invoice success {code}', ['code' => $code]);
        if (($paymentTransaction = PaymentService::findParentTransaction($code)) === null) {
            throw new NotFoundHttpException("not found transaction code $code");
        }
        try {
            /** @var  $mailer yii\mail\BaseMailer */
            $mailer = Yii::$app->mandrillMailer;
            $mailer->viewPath = '@common/views/mail';
            $mail = $mailer->compose(['html' => 'paymentSuccess-html'], [
                'paymentTransaction' => $paymentTransaction,
                'storeManager' => $this->storeManager
            ]);
            $mail->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot']);
            $mail->setTo($paymentTransaction->transaction_customer_email);
            $mail->setSubject('Thank for payment');
            $mail->send();
        } catch (\Exception $exception) {
            Yii::error($exception);
        }

        return $this->render('success', ['code' => $code, 'paymentTransaction' => $paymentTransaction,]);
    }

}