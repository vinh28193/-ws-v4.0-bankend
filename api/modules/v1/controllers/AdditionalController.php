<?php


namespace api\modules\v1\controllers;


use common\models\Order;
use common\modelsMongo\AdditionalFeeLog;
use common\modelsMongo\OrderUpdateLog;
use Yii;
use api\controllers\BaseApiController;
use api\modules\v1\models\AdditionalFeeFrom;
use common\helpers\WeshopHelper;
use common\models\db\TargetAdditionalFee;
use common\models\Product;

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
            $hasChange = false;
            $additionalFees = $form->getAdditionalFees();
            foreach ($productFees as $productFee) {
                /** @var  $productFee TargetAdditionalFee */
                list($amount, $local) = $form->getAdditionalFees()->getTotalAdditionalFees($productFee->name);
                if (!WeshopHelper::compareValue($productFee->amount, $amount)) {
                    $productFee->local_amount = $local;
                    $productFee->amount = $amount;
                    $productFee->save();
                    if ($productFee->name === 'product_fee') {
                        $product->total_price_amount_local = $local;
                    }
                    $hasChange = true;
                }
            }
            $productPrice = $additionalFees->getTotalAdditionalFees('product_price');
            // Tổng tiền các phí, trừ tiền gốc sản phẩm (chỉ có các phí)
            $product->total_fee_product_local = $additionalFees->getTotalAdditionalFees(null, ['product_price'])[1];         // Tổng Phí theo sản phẩm
            // Tổng tiền local gốc sản phẩm (chỉ có tiền gốc của sản phẩm)
            list($product->price_amount_origin, $product->price_amount_local) = $productPrice;
            $product->price_amount_origin = $product->price_amount_origin / $product->quantity_customer;
            $product->price_amount_local = $product->price_amount_local / $product->quantity_customer;


            $product->total_price_amount_local = $productPrice[1];
            // Tổng tiền local tất tần tận
            $product->total_final_amount_local = $additionalFees->getTotalAdditionalFees(null)[1];
            $product->save(false);
            $order = $product->order;
            $order->on(Order::EVENT_AFTER_UPDATE, function ($event) {
                /** @var $event  \yii\db\AfterSaveEvent */
                $diffValue = [];
                /** @var $sender Order */
                $sender = $event->sender;
                $dirtyAttribute = [];
                $formatter = Yii::$app->formatter;
                foreach ($event->changedAttributes as $attribute => $value) {
                    var_dump($value);
                    $newValue = $sender->getAttribute($attribute);
                    if ($attribute !== 'updated_at') {
                        $value = (int)$value;
                        $newValue -= $value;

                    } else {
                        $newValue = $formatter->asDatetime($newValue);
                        $value = $formatter->asDatetime($value);
                    }
                    $dirtyAttribute[$attribute] = $value;
                    $diffValue[$attribute] = $newValue;
                }
                $orderChangeLog = new OrderUpdateLog();
                $orderChangeLog->action = 'update';
                $orderChangeLog->order_code = $sender->ordercode;
                $orderChangeLog->dirty_attribute = $dirtyAttribute;
                $orderChangeLog->diff_value = $diffValue;
                $orderChangeLog->create_by = Yii::$app->getUser()->getId();
                $orderChangeLog->create_at = $formatter->asDatetime('now');
                $orderChangeLog->save(false);
            });
            $orderUpdateAttribute = [];
            $totalOrderQuantity = 0;
            foreach ($order->products as $product) {
                $totalOrderQuantity += $product->quantity_customer;
                foreach ($product->productFees as $productFee) {
                    $attribute = "total_{$productFee->name}_local";
                    $attributeAmount = "total_{$productFee->name}_amount";
                    if ($productFee->name === 'product_price') {
                        // Tổng giá gốc của các sản phẩm tại nơi xuất xứ
                        $attribute = 'total_origin_fee_local';
                        $attributeAmount = 'total_price_amount_origin';
                    } elseif ($productFee->name === 'tax_fee') {
                        // Tổng phí tax của các sản phẩm tại nơi xuất xứ
                        $attribute = 'total_origin_tax_fee_local';
                        $attributeAmount = 'total_origin_tax_fee_amount';
                    } elseif ($productFee->name === 'shipping_fee') {
                        // Tổng phí tax của các sản phẩm tại nơi xuất xứ
                        $attribute = 'total_origin_shipping_fee_local';
                        $attributeAmount = 'total_origin_shipping_fee_amount';
                    } elseif ($productFee->name === 'purchase_fee') {
                        // Tổng phí tax của các sản phẩm tại nơi xuất xứ
                        $attribute = 'total_weshop_fee_local';
                        $attributeAmount = 'total_weshop_fee_amount';
                    } elseif ($productFee->name === 'international_shipping_fee') {
                        // Tổng vat của các sản phẩm
                        $attribute = 'total_intl_shipping_fee_local';
                        $attributeAmount = 'total_intl_shipping_fee_amount';
                    } elseif ($productFee->name === 'vat_fee') {
                        // Tổng vận chuyển tại local của các sản phẩm
                        $attribute = 'total_vat_amount_local';
                        $attributeAmount = 'total_vat_amount_amount';
                    }
                    if (!isset($orderUpdateAttribute[$attribute])) {
                        $orderUpdateAttribute[$attribute] = 0;
                    }
                    if (!isset($orderUpdateAttribute[$attributeAmount])) {
                        $orderUpdateAttribute[$attributeAmount] = 0;
                    }
                    if ($order->hasAttribute($attribute)) {
                        $value = $orderUpdateAttribute[$attribute];
                        $value += (int)$productFee->local_amount;
                        $orderUpdateAttribute[$attribute] = $value;
                    }
                    if ($order->hasAttribute($attributeAmount)) {
                        $valueAmount = $orderUpdateAttribute[$attributeAmount];
                        $valueAmount += $productFee->amount;
                        $orderUpdateAttribute[$attributeAmount] = $valueAmount;
                    }
                }
                if (!isset($orderUpdateAttribute['total_fee_amount_local'])) {
                    $orderUpdateAttribute['total_fee_amount_local'] = 0;
                }
                $fee = $orderUpdateAttribute['total_fee_amount_local'];
                $fee += (int)$product->total_fee_product_local;

                $orderUpdateAttribute['total_fee_amount_local'] = $fee;
            }

            // Tổng tiền (bao gồm tiền giá gốc của các sản phẩm và các loại phí)
            $orderUpdateAttribute['total_amount_local'] = $orderUpdateAttribute['total_origin_fee_local'];
            $orderUpdateAttribute['total_final_amount_local'] = $orderUpdateAttribute['total_amount_local'] + $orderUpdateAttribute['total_fee_amount_local'];
            $orderUpdateAttribute['total_quantity'] = $totalOrderQuantity;
//            $compareAttributes = [];
//            foreach ($orderUpdateAttribute as $updateName => $updateValue) {
//                if (WeshopHelper::compareValue($order->$updateName, $updateValue)) {
//                    $compareAttributes[$updateName] = $updateValue;
//                }
//            }
            $order->setAttributes($orderUpdateAttribute);
            $order->update(false);

        }
        return $this->response(true, 'call success', []);
    }

    public function actionView($code) {
        $model = OrderUpdateLog::find()->where(['order_code' => $code])->orderBy(['create_at' => SORT_DESC])->asArray()->one();
        return $this->response(true, 'sucess', $model);
    }
}