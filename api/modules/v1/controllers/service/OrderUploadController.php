<?php


namespace api\modules\v1\controllers\service;

use common\components\ThirdPartyLogs;
use common\helpers\WeshopHelper;
use common\models\Order;
use Yii;
use Exception;
use common\helpers\ExcelHelper;
use yii\helpers\ArrayHelper;

class OrderUploadController extends OrderController
{

    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['upload'],
                'roles' => $this->getAllRoles(true),
            ],
        ];
    }

    protected function verbs()
    {
        return [
            'upload' => ['POST'],
        ];
    }

    public function actionUpload()
    {
        $bodyParams = Yii::$app->request->bodyParams;
        if (($typeUpload = ArrayHelper::getValue($bodyParams, 'type')) === null) {
            $this->response(false, 'Invalid parameter `type`');
        }
//        return $this->response(true, 'This action not support now');

        $sheets = ExcelHelper::readFromFile('file');

        if (empty($sheets)) {
            $this->response(false, 'File empty');
        }
        $tokens = ["Action $typeUpload"];
        if ($typeUpload === 'orderEbay') {
            foreach ($sheets as $name => $sheet) {
                $log = [];
                $checkSheet = isset($sheet[0]) ? $sheet[0] : null;
                if ($checkSheet === null || !isset($checkSheet['Transaction ID'])) {
                    continue;
                }

                $refKey = 'Transaction ID';
                if (isset($checkSheet['BIN'])) {
                    $refKey = 'BIN';
                }

                $refs = ArrayHelper::getColumn($sheet, $refKey, false);
                $refs = array_filter($refs, function ($ref) {
                    return $ref !== null || $ref !== 'N/A' || $ref !== '#N/A' || $ref !== '';
                });

                $column = $refKey === 'BIN' ? 'ordercode' : 'purchase_transaction_id';
                $where = ['IN', $column, $refs];
                $orders = Order::find()->where($where)->all();
                $orders = ArrayHelper::index($orders,$column);
                $totalRow = count($sheet);
                $msg = "update form Sheet $name ($totalRow records)";
                $count = 0;
                foreach ($sheet as $index => $row) {
                    if (($conditions = ArrayHelper::getValue($row, $refKey)) === null) {
                        $log[$index] = "Invalid column `$refKey`";
                        continue;
                    }
                    $conditions = trim($conditions);
                    if ($refKey === 'BIN' && (!is_numeric($conditions) || WeshopHelper::isSubText($conditions, 'VN') || WeshopHelper::isSubText($conditions, 'ID'))) {
                        $log[$index] = "Unknown order code  $conditions";
                        continue;
                    }
                    /** @var $order Order */
                    if (($order = ArrayHelper::getValue($orders, $conditions)) === null) {
                        $log[$index] = "Not found order  $refKey : `$conditions`";
                        continue;
                    }
                    if(isset($order[0])){
                        $order = reset($order);
                    }

                    $transaction = Order::getDb()->beginTransaction();
                    try {
                        if ($refKey === 'BIN' && ($transactionID = ArrayHelper::getValue($row, 'Transaction ID')) !== null && !WeshopHelper::compareValue($order->purchase_transaction_id, $transactionID)) {
                            $order->purchase_transaction_id = $transactionID;
                        }
                        if (($amount = ArrayHelper::getValue($row, 'Gross')) !== null) {
                            $amount = str_replace('-', '', $amount);
                            $order->purchase_amount = $amount;
                        }
                        if(($q = ArrayHelper::getValue($row,'Quantity')) !== null){
                            $order->total_purchase_quantity = $q;
                        }
                        $order->currency_purchase = 'USD';

                        $order->save(false);
                        $count++;
                        $dirtyAttributes = $order->getDirtyAttributes();

                        $log[$index] = [
                            'attributes' => $order->getAttributes(array_keys($dirtyAttributes)),
                            'dirtyAttributes' => $dirtyAttributes
                        ];
                        $transaction->commit();

                    } catch (\yii\db\Exception $e) {
                        $transaction->rollBack();
                        $log[$index] = $e->getMessage();
                    }


                }
                ThirdPartyLogs::setLog("UPLOAD_FILE", $typeUpload, $msg, $sheet, $log);
                $tokens[] = $msg;
                $tokens[] = "success {$count} record";
            }
            return $this->response(true, implode(', ', $tokens));
        } elseif ($typeUpload === 'orderAmazon') {
            foreach ($sheets as $name => $sheet) {
                $log = [];
                $checkSheet = isset($sheet[0]) ? $sheet[0] : null;
                if ($checkSheet === null || !isset($checkSheet['Order ID'])) {
                    continue;
                }

                $refKey = 'Order ID';
                if (isset($checkSheet['BIN'])) {
                    $refKey = 'BIN';
                }

                $refs = ArrayHelper::getColumn($sheet, $refKey, false);
                $refs = array_filter($refs, function ($ref) {
                    return $ref !== null || $ref !== 'N/A' || $ref !== '#N/A' || $ref !== '';
                });

                $column = $refKey === 'BIN' ? 'ordercode' : 'purchase_transaction_id';
                $where = ['IN', $column, $refs];

                $orders = Order::find()->where($where)->all();
                $orders = ArrayHelper::index($orders,$column);

                $totalRow = count($sheet);
                $msg = "update form Sheet $name ($totalRow records)";
                $count = 0;
                foreach ($sheet as $index => $row) {

                    if (($conditions = ArrayHelper::getValue($row, $refKey)) === null) {
                        $log[$index] = "Invalid column `$refKey`";
                        continue;
                    }
                    $conditions = trim($conditions);

                    if ($refKey === 'BIN' && (!is_numeric($conditions) || WeshopHelper::isSubText($conditions, 'VN') || WeshopHelper::isSubText($conditions, 'ID'))) {
                        $log[$index] = "Unknown order code  $conditions";
                        continue;
                    }
                    /** @var $order Order */
                    if (($order = ArrayHelper::getValue($orders, $conditions)) === null) {
                        $log[$index] = "Not found order  $refKey : `$conditions`";
                        continue;
                    }
                    if(isset($order[0])){
                        $order = reset($order);
                    }
                    $transaction = Order::getDb()->beginTransaction();
                    try {
                        if ($refKey === 'BIN' && ($orderId = ArrayHelper::getValue($row, 'Order ID')) !== null && !WeshopHelper::compareValue($order->purchase_order_id, $orderId)) {
                            $order->purchase_order_id = $orderId;
                        }
                        if (($orderNetTotal = ArrayHelper::getValue($row, 'Order Net Total')) !== null) {
                            $order->purchase_amount = $orderNetTotal;
                        }
                        if (($purchaseCurrency = ArrayHelper::getValue($row, 'Currency')) !== null) {
                            $order->currency_purchase = $purchaseCurrency;
                        }

                        if (($purchaseCard = ArrayHelper::getValue($row, 'Payment Instrument Type')) !== null && trim($purchaseCard) !== 'N/A') {
                            $order->purchase_card = $purchaseCard;
                        }

                        if (($orderStatus = ArrayHelper::getValue($row, 'Order Status')) !== null) {
                            $orderStatus = strtolower($orderStatus);
                            if ($orderStatus === 'closed') {
                                $order->current_status = Order::STATUS_PURCHASED;
                            } else if ($orderStatus === 'pending' || WeshopHelper::isSubText($orderStatus, 'pending')) {
                                $order->current_status = Order::STATUS_PURCHASING;
                            }
                        }

                        if (($carrierTracking = ArrayHelper::getValue($row, 'Carrier Tracking #')) !== null && trim($carrierTracking) !== 'N/A' && trim($carrierTracking) !== '') {
                            $transactionCodes = $order->tracking_codes !== null ? explode(',', $order->tracking_codes) : [];
                            if (!in_array($carrierTracking, $transactionCodes)) {
                                $transactionCodes[] = $transactionCodes;
                            }
                            if (!empty($transactionCodes)) {
                                $order->tracking_codes = implode(',', $transactionCodes);
                            }

                        }
                        if (($shippingQuantity = ArrayHelper::getValue($row, 'Shipment Quantity')) !== null && trim($shippingQuantity) !== 'N/A') {
                            $order->total_purchase_quantity = $shippingQuantity;
                        }

                        if (($shipped = ArrayHelper::getValue($row, 'Shipment Status')) !== null && strtolower($shippingQuantity) === 'shipped') {
                            $time = ArrayHelper::getValue($row, 'Shipment Date');
                            if ($time === null || $time === 'N/A') {
                                $time = Yii::$app->formatter->asTimestamp('now');
                            } else {
                                $time = strtotime($time);
                            }
                            $order->seller_shipped = $time;

                            $order->seller_shipped = $shippingQuantity;
                        }

                        $order->save(false);
                        $count++;
                        $dirtyAttributes = $order->getDirtyAttributes();

                        $log[$index] = [
                            'attributes' => $order->getAttributes(array_keys($dirtyAttributes)),
                            'dirtyAttributes' => $dirtyAttributes
                        ];
                        $transaction->commit();
                    } catch (\yii\db\Exception $e) {
                        $transaction->rollBack();
                        $log[$index] = $e->getMessage();
                    }
                }
                ThirdPartyLogs::setLog("UPLOAD_FILE", $typeUpload, $msg, $sheet, $log);
                $tokens[] = $msg;
                $tokens[] = "success {$count} record";
            }
            return $this->response(true, implode(', ', $tokens));
        } else {
            return $this->response(false, "No handle for type $typeUpload");
        }
    }
}