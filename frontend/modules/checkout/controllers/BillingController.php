<?php


namespace frontend\modules\checkout\controllers;

use common\models\PaymentTransaction;
use frontend\modules\payment\models\Order;
use frontend\modules\payment\Payment;
use Yii;
use yii\web\NotFoundHttpException;

class BillingController extends CheckoutController
{

    public function init()
    {
        if (($code = Yii::$app->request->get('code')) !== null) {
            $this->title = Yii::t('frontend', 'Invoice {code}', ['code' => $code]);
        }
        parent::init();
    }

    public function actionIndex($code)
    {
        if (($paymentTransaction = PaymentTransaction::findOne(['order_code' => $code])) === null) {
            throw new NotFoundHttpException("not found transaction for order code $code");
        }
        $payment = new Payment([
            'type' => 'billing',
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

        $order = new Order($paymentTransaction->order->getAttributes());
        $order->getAdditionalFees()->loadFormActiveRecord($paymentTransaction->order);
        $payment->setOrders([$order]);
        return $this->render('index', [
            'paymentTransaction' => $paymentTransaction,
            'payment' => $payment,
        ]);
    }

    public function actionFail($code)
    {

        if (($paymentTransaction = PaymentTransaction::findOne(['transaction_code' => $code])) === null) {
            throw new NotFoundHttpException("not found transaction code $code");
        }
        $payment = new Payment([
            'type' => 'billing',
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
            'payment' => $payment,
        ]);

    }

    public function actionSuccess($code)
    {
        if (($paymentTransaction = PaymentTransaction::findOne(['transaction_code' => $code])) === null) {

        }
        return $this->render('success', ['code' => $code, 'paymentTransaction' => $paymentTransaction,]);
    }

}