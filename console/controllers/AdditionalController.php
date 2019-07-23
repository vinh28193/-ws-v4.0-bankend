<?php


namespace console\controllers;

use common\components\ExchangeRate;
use common\components\InternationalShippingCalculator;
use common\components\StoreManager;
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

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), ['orderCode']);
    }

    public function optionAliases()
    {
        return array_merge(parent::optionAliases(), [
            'bin' => 'orderCode',
        ]);
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if ($action->id === 'update-purchase-fee' || $action->id === 'international-shipping-fee') {
                if ($this->orderCode === null) {
                    $this->stdout("    > action `{$action->id}` required parameter --orderCode (-bin).\n", Console::FG_RED);
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
        $this->stdout("    > action start.\n", Console::FG_RED);
        $codes = $this->orderCode;
        if (is_string($codes)) {
            $codes = [$codes];
        }

        foreach ($codes as $code) {
            $this->stdout("    > filter order code `$code`.\n", Console::FG_GREEN);
            /** @var  $order Order */
            if ($order = $this->findOrder($code) === null) {
                $this->stdout("    > not found order code `$code`.\n", Console::FG_RED);
                continue;
            }
            if ($order->customer_id === null) {
                $this->stdout("    > order `$code` not valid, cause customer guest .\n", Console::FG_RED);
                continue;
            }
            /** @var  $customer User */
            $customer = $order->customer;
            $useLevel = $customer->getUserLevel();
            $this->stdout("    > customer `$customer->id`  level `$useLevel` detected.\n", Console::FG_GREEN);
            $purchasePercent = 0.12;
            if ($useLevel === User::LEVEL_SLIVER) {
                $purchasePercent = 0.08;
            } elseif ($useLevel === User::LEVEL_GOLD) {
                $purchasePercent = 0.05;
            }

            $storeManager = $this->getStoreManager();

            $storeManager->setStore($order->store_id);
            /** @var  $exRate ExchangeRate */
            $exRate = Yii::$app->exRate;
            $exRate->usedIdentity = false;
            $exRate->setUser($customer);
            $rate = $exRate->load('USD', $storeManager->store->currency);
            $storeManager->setExchangeRate($rate);

            $this->stdout("    > $purchasePercent will be apply to order `$code` .\n", Console::FG_GREEN);
            $products = $order->products;
            $countProduct = count($products);
            $this->stdout("    > order `$code` have $countProduct product .\n", Console::FG_RED);
            $this->stdout("    > transaction begin .\n", Console::FG_RED);
            $transaction = Order::getDb()->beginTransaction();
            try {
                $orderFees = [];
                foreach ($products as $product) {
                    /** @var  $purchaseFee TargetAdditionalFee */
                    $purchaseFee = TargetAdditionalFee::find()->where([
                        'AND',
                        ['name' => 'purchase_fee'],
                        ['target' => 'product'],
                        ['target_id' => $product->id]
                    ])->one();
                    if ($purchaseFee === null) {
                        $this->stdout("    > product `{$product->id}` in `$code` not have purchase, roll back transaction .\n", Console::FG_RED);
                        $transaction->rollBack();
                    }
                    $usAmount = $product->total_final_amount_origin;
                    $newPurchaseFee = $usAmount * $purchasePercent;
                    $purchaseFee->amount = $newPurchaseFee;
                    $localAmount = 0;
                    $this->stdout("    > product `$product->id` update purchase fee ({$purchaseFee->id}) form  {$purchaseFee->amount} to `$newPurchaseFee`.\n", Console::FG_GREEN);

                }
            } catch (Exception $exception) {

            }


        }
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

            $weight = $order->total_weight_temporary * 1000;
            $totalAmount = $order->total_amount_local;
            $items = [];
            foreach ($order->products as $product) {
                $items[] = [
                    'sku' => implode('|', [$product->parent_sku, $product->sku]),
                    'label_code' => '',
                    'origin_country' => '',
                    'name' => $product->product_name,
                    'desciption' => '',
                    'weight' => WeshopHelper::roundNumber(($weight / $product->quantity_customer)),
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
                    'contact_name' => 'ws calculator',
                    'company_name' => '',
                    'email' => '',
                    'address' => 'ws auto',
                    'address2' => '',
                    'phone' => '0987654321',
                    'phone2' => '',
                    'province' => ($order->receiver_province_id !== null || $order->receiver_district_id === null) ? $order->buyer_province_id : $order->receiver_province_id,
                    'district' => ($order->receiver_province_id !== null || $order->receiver_district_id === null) ? $order->buyer_district_id : $order->receiver_district_id,
                    'country' => $order->store_id === 1 ? 'VN' : 'ID',
                    'zipcode' => $order->store_id === 1 ? '' : ($order->receiver_post_code === null ? $order->buyer_post_code : $order->receiver_post_code),
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
            if ($ok && is_array($couriers) && count($couriers) > 0) {
                $firstCourier = $couriers[0];
                if ($internationalShipping === null) {
                    $internationalShipping = new TargetAdditionalFee();
                    $internationalShipping->name = 'international_shipping_fee';
                    $internationalShipping->type = 'local';
                    $internationalShipping->discount_amount = 0;
                    $internationalShipping->currency = $order->store_id === 1 ? 'VND' : 'IDR';
                    $internationalShipping->label = $order->store_id === 1 ? 'Phí vận chuyển quốc tế' : 'International Shipping Fee';
                    $internationalShipping->created_at = time();
                    $internationalShipping->remove = 0;
                    $internationalShipping->target = 'order';
                    $internationalShipping->target_id = $order->id;
                }
                $internationalShipping->amount = $firstCourier['total_fee'];
                $internationalShipping->local_amount = $storeManager->roundMoney($firstCourier['total_fee']);
                $internationalShipping->save(false);

                $oldValue = $order->total_intl_shipping_fee_amount;
                if ($oldValue === null) {
                    $oldValue = 0;
                }
                $order->total_intl_shipping_fee_amount = $internationalShipping->local_amount;
                $order->total_fee_amount_local = ($order->total_fee_amount_local - $oldValue) + $internationalShipping->local_amount;
                $order->total_final_amount_local = ($order->total_final_amount_local - $oldValue) + $internationalShipping->local_amount;
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
}