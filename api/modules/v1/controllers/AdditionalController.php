<?php


namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use api\modules\v1\models\AdditionalFeeFrom;
use common\helpers\WeshopHelper;
use common\models\db\TargetAdditionalFee;
use common\models\Order;
use common\models\Product;
use common\modelsMongo\OrderUpdateLog;
use Yii;
use yii\helpers\ArrayHelper;

class AdditionalController extends BaseApiController
{


    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'view', 'create', 'update'],
                'roles' => $this->getAllRoles(true),

            ],
            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => $this->getAllRoles(true),
                'permissions' => ['canView']
            ],
        ];
    }

    protected function verbs()
    {
        return [
            'index' => ['GET', 'POST'],
            'create' => ['POST'],
            'update' => ['PATCH', 'PUT'],
            'view' => ['GET'],
            'delete' => ['DELETE']
        ];
    }

    public function actionIndex()
    {
        $form = new AdditionalFeeFrom();
        if (!$form->load(Yii::$app->getRequest()->getQueryParams(), '')) {
            return $this->response(false, 'can not resolved current request');
        }
        if ($form->target_name === 'product' && ($product = $form->getTarget()) !== null) {
            /** @var $product Product */
            if ($form->shipping_quantity !== null && $form->shipping_quantity !== '' && (int)$form->shipping_quantity > 0 && !WeshopHelper::compareValue($product->quantity_customer, $form->shipping_quantity)) {
                $product->quantity_customer = $form->shipping_quantity;
            }
            $productFees = $product->productFees;
            if (empty($productFees)) {
                return $this->response(false, "can not resolved empty fee for product {$form->target_id}");
            }
            $additionalFees = $form->getAdditionalFees();
            $keys = [];
            foreach ($productFees as $productFee) {
                /** @var  $productFee TargetAdditionalFee */
                list($amount, $local) = $form->getAdditionalFees()->getTotalAdditionalFees($productFee->name);
                $keys[] = $productFee->name;
                if (!WeshopHelper::compareValue($productFee->amount, $amount, 'float')) {
                    $productFee->local_amount = $local;
                    $productFee->amount = $amount;
                    $productFee->save();
                    if ($productFee->name === 'product_fee') {
                        $product->total_price_amount_local = $local;
                    }
                }
            }
            Yii::info($form->getAdditionalFees());
            foreach ($form->getAdditionalFees()->keys() as $feeName) {
                // continute if $key in $keys
                if (ArrayHelper::isIn($feeName, $keys)) {
                    continue;
                }
                $arrayFees = $form->getAdditionalFees()->get($feeName, [], false);

                $firstFee = $arrayFees[0];
                Yii::info($firstFee);
                $firstFee = new TargetAdditionalFee($firstFee);
                list($firstFee->amount, $firstFee->local_amount) = $form->getAdditionalFees()->getTotalAdditionalFees($feeName);
                $firstFee->target = 'order';
                $firstFee->target_id = $product->order_id;
                $firstFee->save(false);
                foreach ($arrayFees as $arrayFee) {
                    $target = new TargetAdditionalFee($arrayFee);
                    $target->target = 'product';
                    $target->target_id = $product->id;
                    $target->save(false);
                }
                $form->getAdditionalFees()->remove($feeName);
            }
            $productPrice = $additionalFees->getTotalAdditionalFees('product_price');
            $product->total_fee_product_local = $additionalFees->getTotalAdditionalFees(null,['product_price'])[1];
            $product->price_amount_origin = $product->price_amount_origin / $product->quantity_customer;
            $product->price_amount_local = $product->price_amount_local / $product->quantity_customer;
            $product->total_price_amount_local = $productPrice[1];
            $product->total_price_amount_origin = $productPrice[0];
            $policy = isset($product->price_policy) ? $product->price_policy : 0;
            list($product->total_final_amount_origin, $product->total_final_amount_local) = $additionalFees->getTotalAdditionalFees(['product_price', 'shipping_fee', 'tax_fee']);

            // Tổng tiền local tất tần tận
            $product->save(false);
            $order = $product->order;
            $check = OrderUpdateLog::find()->where(['order_code' => $form->orderCode])->one();
            if ($check > 0) {

            } else {
                $order->on(Order::EVENT_AFTER_UPDATE, function ($event) {
                    /** @var $event  \yii\db\AfterSaveEvent */
                    $diffValue = [];
                    /** @var $sender Order */
                    $sender = $event->sender;
                    $dirtyAttribute = [];
                    $formatter = Yii::$app->formatter;
                });
            }
            $orderFees = [];
            $totalOrderQuantity = 0;
            $totalOrderAmount = 0;
            $totalOrderAmountLocal = 0;

            foreach ($order->products as $product) {
                $totalOrderQuantity += $product->quantity_customer;
                $totalOrderAmount += $product->total_price_amount_origin;
                $totalOrderAmountLocal += $product->total_price_amount_local;
                $totalProductFee = 0;
                foreach ($product->productFees as $productFee) {
                    if ($productFee->name !== 'product_price' && $productFee->name !== 'purchase_fee') {
                        $totalProductFee += $productFee->local_amount;
                    }

                    if (!isset($orderFees[$productFee->name])) {
                        $orderFees[$productFee->name] = [
                            'amount' => 0,
                            'local_amount' => 0
                        ];
                    }
                    $amount = $orderFees[$productFee->name]['amount'];
                    $amount += $productFee->amount;
                    $local_amount = $orderFees[$productFee->name]['local_amount'];
                    $local_amount += $productFee->local_amount;
                    $orderFees[$productFee->name] = [
                        'amount' => $amount,
                        'local_amount' => $local_amount
                    ];
                }
                $product->total_fee_product_local = $totalProductFee;
            }
            $orderUpdateAttribute = [
                'total_amount_local' => $totalOrderAmountLocal,
                'total_origin_fee_local' => $totalOrderAmountLocal,
                'total_price_amount_origin' => $totalOrderAmount,
                'total_quantity' => $totalOrderQuantity
            ];
            $allOrderFees = $order->targetFee;
            $totalOrderFeeAmountLocal = 0;
            foreach ($allOrderFees as $orderFee) {
                /** @var $orderFee TargetAdditionalFee */
                if (isset($orderFees[$orderFee->name]) && ($tempOrderFee = $orderFees[$orderFee->name]) !== null && !WeshopHelper::compareValue($orderFee->amount, $tempOrderFee['amount'], 'float')) {
                    $orderFee->amount = $tempOrderFee['amount'];
                    $orderFee->local_amount = $tempOrderFee['local_amount'];
                    $orderFee->save(false);
                }
                $attribute = "total_{$orderFee->name}_local";
                $attributeAmount = "total_{$orderFee->name}_amount";
                if ($orderFee->name === 'product_price') {
                    continue;
                } elseif ($orderFee->name === 'tax_fee') {
                    // Tổng phí tax của các sản phẩm tại nơi xuất xứ
                    $attribute = 'total_origin_tax_fee_local';
                    $attributeAmount = 'total_origin_tax_fee_amount';
                } elseif ($orderFee->name === 'shipping_fee') {
                    // Tổng phí tax của các sản phẩm tại nơi xuất xứ
                    $attribute = 'total_origin_shipping_fee_local';
                    $attributeAmount = 'total_origin_shipping_fee_amount';
                } elseif ($orderFee->name === 'purchase_fee') {
                    // Tổng phí tax của các sản phẩm tại nơi xuất xứ
                    $attribute = 'total_weshop_fee_local';
                    $attributeAmount = 'total_weshop_fee_amount';
                } elseif ($orderFee->name === 'international_shipping_fee') {
                    // Tổng vat của các sản phẩm
                    $attribute = 'total_intl_shipping_fee_local';
                    $attributeAmount = 'total_intl_shipping_fee_amount';
                } elseif ($orderFee->name === 'vat_fee') {
                    // Tổng vận chuyển tại local của các sản phẩm
                    $attribute = 'total_vat_amount_local';
                    $attributeAmount = 'total_vat_amount_amount';
                } elseif ($orderFee->name === 'customer_fee') {
                    // Tổng vận chuyển tại local của các sản phẩm
                    $attribute = 'total_custom_fee_amount_local';
                    $attributeAmount = 'total_custom_fee_amount';
                }
                $totalOrderFeeAmountLocal += $orderFee->local_amount;
                if (!isset($orderUpdateAttribute[$attribute])) {
                    $orderUpdateAttribute[$attribute] = 0;
                }
                if (!isset($orderUpdateAttribute[$attributeAmount])) {
                    $orderUpdateAttribute[$attributeAmount] = 0;
                }
                if ($order->hasAttribute($attribute)) {
                    $value = $orderUpdateAttribute[$attribute];
                    $value += $orderFee->local_amount;
                    $orderUpdateAttribute[$attribute] = $value;
                }
                if ($order->hasAttribute($attributeAmount)) {
                    $valueAmount = $orderUpdateAttribute[$attributeAmount];
                    $valueAmount += $orderFee->amount;
                    $orderUpdateAttribute[$attributeAmount] = $valueAmount;
                }
            }
            $orderUpdateAttribute['total_fee_amount_local'] = $totalOrderFeeAmountLocal;
            // Tổng tiền (bao gồm tiền giá gốc của các sản phẩm và các loại phí)
            $orderUpdateAttribute['total_final_amount_local'] = $orderUpdateAttribute['total_amount_local'] + $orderUpdateAttribute['total_fee_amount_local'];
            $order->setAttributes($orderUpdateAttribute, false); // set all, do not check safe
            $order->update(false);

        }
        return $this->response(true, 'call success', []);
    }

    public function actionView($code)
    {
        $model = OrderUpdateLog::find()->where(['order_code' => $code])->orderBy(['create_at' => SORT_DESC])->asArray()->one();
        return $this->response(true, 'sucess', $model);
    }
}