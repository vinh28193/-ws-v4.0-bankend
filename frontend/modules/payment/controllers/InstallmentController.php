<?php


namespace frontend\modules\payment\controllers;

use frontend\modules\payment\models\Order;
use frontend\modules\payment\PaymentService;
use Yii;
use frontend\modules\payment\Payment;
use frontend\modules\payment\providers\alepay\AlepayClient;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class InstallmentController extends BasePaymentController
{

    public function actionCalculator($provider)
    {
        $provider = (int)$provider;
        $params = $this->request->bodyParams;
        $orderParams = ArrayHelper::remove($params, 'orders', []);
        if (empty($orderParams)) {
            return $this->response(false, 'empty');
        }
        $payment = new Payment($params);
        $orders = [];
        foreach ($orderParams as $orderParam) {
            if (isset($orderParam['totalFinalAmount'])) {
                unset($orderParam['totalFinalAmount']);
            }
            if (isset($orderParam['totalAmountLocal'])) {
                unset($orderParam['totalAmountLocal']);
            }
            $order = new Order($orderParam);
            if ($order->cartId !== null && $order->createOrderFromCart() !== false) {
                $orders[$order->cartId] = $order;
            } else if ($order->ordercode !== null && ($order = Order::findOne(['ordercode' => $order->ordercode])) !== null) {
                $orders[$order->ordercode] = $order;
            }
        }
        $payment->setOrders($orders);
        Yii::info($payment->getOrders(), $payment->page);
        $success = false;
        $message = 'no found';
        $data = [
            'calculator' => 'installment',
            'origin' => $this->storeManager->showMoney($payment->getTotalAmountDisplay()),
            'methods' => []
        ];
        if ($provider === 44) {
            $success = true;
            $message = 'success';
            $data = ArrayHelper::merge($data, [
                'calculator' => 'alepay',
                'methods' => $this->getAlapayCalculator($payment)
            ]);
        }
        return $this->response($success, $message, $data);
    }

    /**
     * @param $payment Payment
     * @return array
     */
    private function getAlapayCalculator($payment)
    {
        $alepay = new AlepayClient();
        $storeManager = $this->storeManager;

        $rs = $alepay->getInstallmentInfo($payment->getTotalAmountDisplay(), 'VND');
        if ($rs['success']) {
            $data = Json::decode($rs['data']);
            $banks = [];
            foreach ($data as $key => $item) {
                $periods = [];
                foreach ($item['paymentMethods'] as $method) {
                    if ($method['paymentMethod'] !== 'VISA') {
                        continue;
                    }
                    $periods = $method['periods'];
                }
                $banks[] = [
                    'code' => $item['bankCode'],
                    'name' => $item['bankName'],
                    'icon' => PaymentService::getInstallmentBankIcon($item['bankCode']),
                    'method' => 'VISA',
                    'periods' => $periods
                ];
            }
            return $banks;
        }
        return [];
    }
}