<?php


namespace console\controllers;

use common\models\logs\PaymentGatewayLogs;
use common\models\Order;
use common\modelsMongo\ChatMongoWs;
use Yii;
use yii\console\Controller;
use yii\db\Exception;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use common\models\PaymentTransaction;

class PaymentTransactionController extends Controller
{


    public $color = true;

    public function actionCreateChild()
    {
        $start = microtime(true);
        $formatter = Yii::$app->formatter;
        $formDay = 1563123600;
        $today = $formatter->asDatetime('now');
        $formDayStart = $formatter->asDatetime($formDay);
        $this->stdout("    > action started \n", Console::FG_GREEN);
        $this->stdout("    > today: $today \n", Console::FG_GREEN);
        $db = PaymentTransaction::getDb();
        $this->stdout("    > open connect to dsn {$db->dsn} \n", Console::FG_GREEN);
        $this->stdout("    > query form day $formDayStart \n", Console::FG_GREEN);
        $this->stdout("    > fetching in database \n", Console::FG_GREEN);

        $fetchQuery = new Query();
        $fetchQuery->from(['pt' => PaymentTransaction::tableName()]);
        $fetchQuery->select([
            'created_at' => new Expression("DATE_FORMAT(FROM_UNIXTIME(`pt`.`created_at`), '%Y-%m-%d %T')"),
            'total_count' => new Expression("COUNT( `pt`.`transaction_code`)"),
            'transaction_code' => 'pt.transaction_code'
        ]);
        $fetchQuery->where([
            'AND',
            ['pt.transaction_type' => PaymentTransaction::TRANSACTION_TYPE_PAYMENT],
            ['>=', 'pt.created_at', $formDay]
        ]);
        $fetchQuery->groupBy(['pt.transaction_code']);
        $fetchQuery->having(['=', 'total_count', 1]);

        $transactions = $fetchQuery->all($db);
        $totalCount = count($transactions);
        $this->stdout("    > fetched $totalCount records \n", Console::FG_GREEN);
        foreach ($transactions as $transaction) {
            $this->stdout("    > process for transaction code {$transaction['transaction_code']} \n", Console::FG_GREEN);
            if (($paymentTransaction = PaymentTransaction::findOne(['transaction_code' => $transaction['transaction_code']])) === null) {
                $this->stdout("    > not found transaction code {$transaction['transaction_code']} \n", Console::FG_RED);
                continue;
            }
            /** @var  $inLog PaymentGatewayLogs */
            $inLog = PaymentGatewayLogs::find()->where([
                'AND',
                ['transaction_code_ws' => $paymentTransaction->transaction_code],
                ['type' => 'CREATED']
            ])->one();
            if ($inLog === null) {
                $this->stdout("    > not found payment gateway log for transaction code {$transaction['transaction_code']} \n", Console::FG_RED);
                continue;
            }
            $requestContent = $inLog->request_content;
            if (is_string($requestContent)) {
                $requestContent = json_decode($requestContent, true);
            }

            $orderCodes = [];

            if (isset($requestContent['order_description']) && ($order_description = $requestContent['order_description']) !== null && $order_description !== '') {
                $order_description = explode('Thanh toan cho cac ma don:', $order_description);
                if (isset($order_description[1])) {
                    $order_description = $order_description[1];
                    $order_description = trim($order_description);
                    $orderCodes = explode(',', $order_description);
                }

            } elseif (isset($requestContent['goodsNm']) && ($goodsNm = $requestContent['goodsNm']) !== null && $goodsNm !== '') {
                $goodsNm = explode('Payment of orders', $goodsNm);
                if (isset($goodsNm[1])) {
                    $goodsNm = $goodsNm[1];
                    $goodsNm = trim($goodsNm);
                    $orderCodes = explode(',', $goodsNm);
                }
            }
            if (empty($orderCodes)) {
                $this->stdout("    > parse fail order codes for transaction code {$paymentTransaction->coupon_code} \n", Console::FG_RED);
            }
            foreach ($orderCodes as $code) {
                if (($order = Order::findOne(['ordercode' => $code])) === null) {
                    $this->stdout("    > can not create child transaction for order code $code \n", Console::FG_RED);
                    continue;
                }
                $childPayment = clone $paymentTransaction;
                $childPayment->order_code = $order->ordercode;
                $childPayment->transaction_amount_local = $order->total_final_amount_local;
                $childPayment->id = null;
                $childPayment->isNewRecord = true;
                $childPayment->carts = $order->ordercode;
                $childPayment->note = "Console: auth create payment transaction (time:$today )";
                $childPayment->save(false);

                $order->payment_transaction_code = $childPayment->transaction_code;

                $order->save(false);
                $this->stdout("    > created transaction code {$childPayment->coupon_code} applied for order {$order->ordercode}.\n", Console::FG_GREEN);
                ChatMongoWs::SendMessage('Console: add Payment transaction code: ' . $childPayment->transaction_code . '' .
                    '<br>add at: ' . $today,
                    $order->ordercode, ChatMongoWs::TYPE_GROUP_WS);
            }
        }
    }
}