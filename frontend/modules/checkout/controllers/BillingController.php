<?php


namespace frontend\modules\checkout\controllers;

use common\models\PaymentTransaction;
use frontend\modules\payment\models\Order;
use frontend\modules\payment\Payment;
use Yii;

class BillingController extends CheckoutController
{

    public function init()
    {
        if (($code = Yii::$app->request->get('code')) !== null) {
            $this->title = Yii::t('frontend', 'Payment success for invoice {code}', ['code' => $code]);
        }
        parent::init();
    }

    public function actionIndex($code)
    {

    }

    public function actionSuccess($code)
    {
        return $this->render('success', ['code' => $code]);
    }

    public function actionFail($code)
    {
        $paymentTransaction = PaymentTransaction::findOne(['transaction_code' => $code]);
        $payment = new Payment([
            'type' => 'billing',
            'page' => Payment::PAGE_BILLING,
            'customer_name' => $paymentTransaction->transaction_customer_name,
            'customer_email' => $paymentTransaction->transaction_customer_email,
            'customer_phone' => $paymentTransaction->transaction_customer_phone,
            'customer_address' => $paymentTransaction->transaction_customer_address,
            'customer_city' => $paymentTransaction->transaction_customer_city,
            'customer_postcode' => $paymentTransaction->transaction_customer_postcode,
            'customer_district' => $paymentTransaction->transaction_customer_district,
            'customer_country' => $paymentTransaction->transaction_customer_country,
        ]);
        $orders = [];
        foreach ($paymentTransaction->childPaymentTransaction as $childPaymentTransaction) {
            if (($orderParam = $childPaymentTransaction->order) !== null) {
                $order = new Order($orderParam->getAttributes());
                $order->getAdditionalFees()->loadFormActiveRecord($orderParam);
                $orders[] = $order;
            }
        }
        $payment->setOrders($orders);
        return $this->render('fail', [
            'payment' => $payment,
            'code' => $code
        ]);
    }
}