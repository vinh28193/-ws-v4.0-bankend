<?php


namespace console\controllers;

use common\components\ExchangeRate;
use common\components\InternationalShippingCalculator;
use common\components\StoreManager;
use common\components\ThirdPartyLogs;
use common\helpers\ChatHelper;
use common\models\User;
use common\helpers\WeshopHelper;
use common\models\db\TargetAdditionalFee;
use common\models\Order;
use common\modelsMongo\ActiveRecordUpdateLog;
use Yii;
use yii\console\Controller;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class AdditionalController extends Controller
{

    public $orderCode;

    public $color = true;

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), ['orderCode']);
    }

    public function optionAliases()
    {
        return array_merge(parent::optionAliases(), [
            'bin' => 'orderCode',
            'c' => 'color'
        ]);
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if ($action->id === 'update-purchase-fee' || $action->id === 'international-shipping-fee') {
                if ($this->orderCode === null) {
                    $this->stdout("    > action `{$action->id}` required parameter --orderCode (-bin).\n", Console::FG_RED);
                    return false;
                }
                if (WeshopHelper::isSubText($this->orderCode, ',')) {
                    $this->orderCode = explode(',', $this->orderCode);
                }
            }
            return true;
        }
        return false;
    }

    /** @var StoreManager */
    private $_storeManager;

    /**
     * @return StoreManager|mixed
     */
    public function getStoreManager()
    {
        if (!is_object($this->_storeManager)) {
            $this->_storeManager = Yii::$app->storeManager;
        }
        return $this->_storeManager;
    }

    public function actionUpdatePurchaseFee()
    {
        $start = microtime(true);
        $this->stdout("    > action start.\n", Console::FG_RED);
        $codes = $this->orderCode;
        if (is_string($codes)) {
            $codes = [$codes];
        }

        foreach ($codes as $code) {
            $this->stdout("    > filter order code `$code`.\n", Console::FG_GREEN);
            /** @var  $order Order */
            if (($order = $this->findOrder($code)) === null) {
                $this->stdout("    > not found order code `$code`.\n", Console::FG_RED);
                $this->stdout("    > aborted order code `$code`.\n", Console::FG_RED);
                continue;
            }
            $for = "guest";
            $purchasePercent = 0.12;
            if (ArrayHelper::isIn($order->ordercode, $this->getBlackOrderCodeLists())) {
                $this->stdout("    > old order detected .\n", Console::FG_GREEN);
                $for = "old order (before change policy)";
                $purchasePercent = 0.1;
            } else if ($order->customer_id === null && ($customer = $order->customer) !== null) {
                /** @var  $customer User */
                $useLevel = $customer->getUserLevel();
                $this->stdout("    > customer `$customer->id`  level `$useLevel` detected.\n", Console::FG_GREEN);
                $purchasePercent = 0.12;
                if ($useLevel === User::LEVEL_SLIVER) {
                    $purchasePercent = 0.08;
                } elseif ($useLevel === User::LEVEL_GOLD) {
                    $purchasePercent = 0.05;
                }
                $for = "customer `$useLevel`";
            }
            $storeManager = $this->getStoreManager();

            $storeManager->setStore($order->store_id);
            $storeManager->setExchangeRate($order->exchange_rate_fee);
            ActiveRecordUpdateLog::register('console update Purchase Fee', $order, 15);
            $this->stdout("    > $purchasePercent will be apply to order `$code` .\n", Console::FG_GREEN);
            $products = $order->products;
            $countProduct = count($products);
            $this->stdout("    > order `$code` have $countProduct product .\n", Console::FG_GREEN);
            $this->stdout("    > transaction begin .\n", Console::FG_GREEN);
            $transaction = Order::getDb()->beginTransaction();
            try {
                $orderPurchaseAmount = 0;
                $orderPurchaseLocal = 0;
                foreach ($products as $product) {
                    ActiveRecordUpdateLog::register('console update Purchase Fee', $product, 15);
                    /** @var  $purchaseFee TargetAdditionalFee */
                    $purchaseFee = TargetAdditionalFee::find()->where([
                        'AND',
                        ['name' => 'purchase_fee'],
                        ['target' => 'product'],
                        ['target_id' => $product->id]
                    ])->one();
                    if ($purchaseFee === null) {
                        $this->stdout("    > product `{$product->id}` in `$code` not have purchase, roll back transaction .\n", Console::FG_RED);
                        $this->stdout("    > transaction roll back.\n", Console::FG_RED);
                        $transaction->rollBack();
                    }
                    $usAmount = $product->total_final_amount_origin;
                    $newPurchaseFee = $usAmount * $purchasePercent;
                    $localAmount = $storeManager->roundMoney($newPurchaseFee * $storeManager->getExchangeRate());

                    $this->stdout("    > product `$product->id` update purchase fee ({$purchaseFee->id}) form  {$storeManager->showMoney($purchaseFee->local_amount)} to `{$storeManager->showMoney($localAmount)}`.\n", Console::FG_GREEN);
                    $purchaseFee->amount = $newPurchaseFee;
                    $purchaseFee->local_amount = $localAmount;
                    $orderPurchaseAmount += $newPurchaseFee;
                    $orderPurchaseLocal += $localAmount;
                    $purchaseFee->save();
                }
                $oldAmountValue = $order->total_weshop_fee_amount;
                $oldLocalValue = $order->total_weshop_fee_local;
                if ($oldAmountValue === null) {
                    $oldAmountValue = 0;
                }
                if ($oldLocalValue === null) {
                    $oldLocalValue = 0;
                }
                /** @var  $orderPurFee TargetAdditionalFee */
                $orderPurFee = TargetAdditionalFee::find()->where([
                    'AND',
                    ['name' => 'purchase_fee'],
                    ['target' => 'order'],
                    ['target_id' => $order->id]
                ])->one();
                if ($orderPurFee !== null) {
                    $orderPurFee->amount = $orderPurchaseAmount;
                    $orderPurFee->local_amount = $orderPurchaseLocal;
                    $orderPurFee->save(false);
                }
                $order->total_weshop_fee_amount = $orderPurchaseAmount;
                $order->total_weshop_fee_local = $orderPurchaseLocal;
                $order->total_fee_amount_local = ($order->total_fee_amount_local - $oldLocalValue) + $orderPurchaseLocal;
                $order->total_final_amount_local = ($order->total_final_amount_local - $oldLocalValue) + $orderPurchaseLocal;

                $now = Yii::$app->formatter->asDatetime('now');
                $orderNote = $order->note;
                $convert = $purchasePercent * 100;
                $note = "purchase fee, applied rate {$convert}% for $for, changed from {$oldAmountValue}$ -> {$orderPurchaseAmount}$ ({$storeManager->showMoney($oldLocalValue)} -> {$storeManager->showMoney($orderPurchaseLocal)}) at:{$now}";

                if ($orderNote === null) {
                    $orderNote = "Console: updated $note";
                } else {
                    $orderNote .= ", Console: updated $note";
                }
                $order->note = $orderNote;
                $order->save(false);
                $this->stdout("    > order changed $note.\n", Console::FG_GREEN);
                $transaction->commit();
                ChatHelper::push("Console: updated $note", $order->ordercode, 'GROUP_WS', 'SYSTEM', null);
                $this->stdout("    > transaction committed.\n", Console::FG_GREEN);
            } catch (Exception $exception) {
                $this->stdout("    > {$exception->getMessage()} \n", Console::FG_RED);
                $this->stdout("    > transaction roll back.\n", Console::FG_RED);
                $transaction->rollBack();
            }
        }
        $this->stdout("    > action end.\n", Console::FG_RED);
    }

    public function actionInternationalShippingFee()
    {
        $this->stdout("    > action start.\n", Console::FG_RED);
        $codes = $this->orderCode;
        if (is_string($codes)) {
            $codes = [$codes];
        }

        foreach ($codes as $code) {
            $code = trim($code);
            $this->stdout("    > filter order code `$code`.\n", Console::FG_GREEN);
            /** @var  $order Order */
            if (($order = $this->findOrder($code)) === null) {
                $this->stdout("    > not found order code `$code`.\n", Console::FG_RED);
                continue;
            }
            $storeManager = $this->getStoreManager();

            $storeManager->setStore($order->store_id);
            /** @var  $exRate ExchangeRate */
            $exRate = Yii::$app->exRate;
            if ($order->customer !== null) {
                $exRate->usedIdentity = false;
                $exRate->setUser($order->customer);
                $rate = $exRate->load('USD', $storeManager->store->currency);
                $storeManager->setExchangeRate($rate);
            }
            ActiveRecordUpdateLog::register('console update International Shipping Fee', $order, 15);
            $pickUpWareHouse = ArrayHelper::getValue(Yii::$app->params, 'pickupUSWHGlobal');
            $current = $order->store_id === 1 ? 'ws_vn' : 'ws_id';
            $pickUpWareHouse = ArrayHelper::getValue($pickUpWareHouse, "warehouses.$current", false);

            $pickUpId = ArrayHelper::getValue($pickUpWareHouse, 'ref_pickup_id');
            $userId = ArrayHelper::getValue($pickUpWareHouse, 'ref_user_id');

            $weight = 0;
            $totalAmount = $order->total_amount_local;
            $items = [];
            foreach ($order->products as $product) {
                $itemWeight = (int)$product->total_weight_temporary * 1000;
                if ($itemWeight <= 0) {
                    $itemWeight = $order->store_id === 1 ? 500 : 1000;
                }
                $weight += $itemWeight;
                $items[] = [
                    'sku' => implode('|', [$product->parent_sku, $product->sku]),
                    'label_code' => '',
                    'origin_country' => '',
                    'name' => $product->product_name,
                    'desciption' => '',
                    'weight' => WeshopHelper::roundNumber($itemWeight / $product->quantity_customer),
                    'amount' => WeshopHelper::roundNumber($product->total_price_amount_local),
                    'quantity' => $product->quantity_customer,
                ];
            }

            $params = [
                'config' => [
                    'include_special_goods' => $order->is_special ? 'Y' : 'N'
                ],
                'ship_from' => [
                    'country' => 'US',
                    'pickup_id' => $pickUpId,
                ],
                'ship_to' => [
                    'contact_name' => $order->receiver_name ? $order->receiver_name : $order->buyer_name,
                    'company_name' => '',
                    'email' => '',
                    'address' => $order->receiver_address ? $order->receiver_address : $order->buyer_address,
                    'address2' => '',
                    'phone' => $order->receiver_phone ? $order->receiver_phone : $order->buyer_phone,
                    'phone2' => '',
                    'province' => ($order->receiver_province_id === null || $order->receiver_district_id === null) ? $order->buyer_province_id : $order->receiver_province_id,
                    'district' => ($order->receiver_province_id === null || $order->receiver_district_id === null) ? $order->buyer_district_id : $order->receiver_district_id,
                    'country' => $order->store_id === 1 ? 'VN' : 'ID',
                    'zipcode' => $order->store_id === 1 ? '' : trim(($order->receiver_post_code === null ? $order->buyer_post_code : $order->receiver_post_code)),
                ],
                'shipments' => [
                    'content' => '',
                    'total_parcel' => 1,
                    'total_amount' => $totalAmount,
                    'description' => '',
                    'amz_shipment_id' => '',
                    'chargeable_weight' => $weight,
                    'parcels' => [
                        [
                            'weight' => $weight,
                            'amount' => $totalAmount,
                            'description' => $order->seller ? "order of seller `{$order->seller->seller_name}`" : "",
                            'items' => $items
                        ]
                    ]
                ],
            ];
            /** @var  $internationalShipping TargetAdditionalFee */
            $internationalShipping = TargetAdditionalFee::find()->where([
                'AND',
                ['name' => 'international_shipping_fee'],
                ['target' => 'order'],
                ['target_id' => $order->id]
            ])->one();

            $calculator = new InternationalShippingCalculator();
            list($ok, $couriers) = $calculator->CalculateFee($params, $userId, $order->store_id === 1 ? 'VN' : 'ID');
            ThirdPartyLogs::setLog('Console', 'update', $code, $params, $couriers);
            if ($ok && is_array($couriers) && count($couriers) > 0) {
                $firstCourier = $couriers[0];
                if ($internationalShipping === null) {
                    $internationalShipping = new TargetAdditionalFee();
                    $internationalShipping->name = 'international_shipping_fee';
                    $internationalShipping->type = 'local';
                    $internationalShipping->discount_amount = 0;
                    $internationalShipping->currency = $storeManager->getCurrencyName();
                    $internationalShipping->label = $order->store_id === 1 ? 'Phí vận chuyển quốc tế' : 'International Shipping Fee';
                    $internationalShipping->created_at = time();
                    $internationalShipping->remove = 0;
                    $internationalShipping->target = 'order';
                    $internationalShipping->target_id = $order->id;
                }
                $internationalShipping->currency = $storeManager->getCurrencyName();
                $internationalShipping->amount = $firstCourier['total_fee'];
                $internationalShipping->local_amount = $storeManager->roundMoney($firstCourier['total_fee']);
                $internationalShipping->save(false);

                $oldValue = $order->total_intl_shipping_fee_local;
                if ($oldValue === null) {
                    $oldValue = 0;
                }

                $order->total_intl_shipping_fee_local = $internationalShipping->local_amount;
                $order->total_fee_amount_local = ($order->total_fee_amount_local - $oldValue) + $internationalShipping->local_amount;
                $order->total_final_amount_local = ($order->total_final_amount_local - $oldValue) + $internationalShipping->local_amount;

                $order->courier_name = implode(' ', [$firstCourier['courier_name'], $firstCourier['service_name']]);
                $order->courier_service = $firstCourier['service_code'];
                $order->courier_delivery_time = implode(' ', [$firstCourier['min_delivery_time'], $firstCourier['max_delivery_time']]);

                $orderNote = $order->note;
                $now = Yii::$app->formatter->asDatetime('now');
                $a = $storeManager->showMoney($internationalShipping->local_amount);
                $note = "Console:updated international shipping information for courier `{$order->courier_name}($order->courier_service)` shipping fee:$a (rate:{$storeManager->getExchangeRate()})  at:{$now}";
                if ($orderNote === null) {
                    $orderNote = $note;
                } else {
                    $orderNote .= ', ';
                    $orderNote .= $note;
                }
                $order->note = $orderNote;
                $order->save(false);
            } else {
                $this->stdout("    > $couriers.\n", Console::FG_RED);
            }
        }
    }

    /**
     * @param $code
     * @return Order|null
     */
    protected function findOrder($code)
    {
        return Order::findOne(['ordercode' => $code]);
    }

    public function getBlackOrderCodeLists()
    {
        return [
            '171867', '170745', '149158', '126376', '117934', '900655', '865421', '835038',
            '830340', '821982', '808948', '763754', '762007', '754847', '740648', '738552',
            '736381', '735582', '734207', 'VN4867B4', 'ID4815B5', 'VN4767B2', 'VN4730B6',
            'VN4693B5',
        ];

    }

}