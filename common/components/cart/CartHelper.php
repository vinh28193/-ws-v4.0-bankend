<?php


namespace common\components\cart;


use common\components\StoreManager;
use common\helpers\WeshopHelper;
use common\models\Order;
use common\models\Product;
use common\models\Seller;
use common\models\User;
use common\products\BaseProduct;
use common\products\Provider;
use common\products\VariationMapping;
use Yii;
use yii\helpers\ArrayHelper;

class CartHelper
{

    /**
     * @return CartManager
     */
    public static function getCartManager()
    {
        return Yii::$app->cart;
    }

    public static function mapCartKeys($items)
    {
        $keys = ArrayHelper::map($items, function ($item) {
            return (string)$item['_id'];
        }, 'key.products');
        return array_map(function ($key) {
            return array_map(function ($e) {
                return [
                    'id' => $e['id'],
                    'sku' => $e['sku']
                ];
            }, $key);
        }, $keys);

    }

    /**
     * @param $item BaseProduct
     * @param $sellerId
     * @param $currentImage
     */
    public static function createItem($item, $sellerId = null, $currentImage = null)
    {
        /** @var  $user  User */
        $user = Yii::$app->user->identity;
        /** @var  $storeManager StoreManager */
        $storeManager = Yii::$app->storeManager;
        $order = [];
        $order['type_order'] = Order::TYPE_SHOP;
        $order['ordercode'] = null;
        $order['portal'] = $item->type;
        $order['current_status'] = Order::STATUS_NEW;
        $order['new'] = Yii::$app->getFormatter()->asTimestamp('now');
        $order['mark_supporting'] = null;
        $order['supporting'] = null;
        $order['supported'] = null;
        $order['customer_type'] = 'Retail';
        $order['store_id'] = $storeManager->getId();
        $order['exchange_rate_fee'] = $storeManager->getExchangeRate();
        $order['sale_support_id'] = null;
        $order['support_email'] = null;
        $order['saleSupport'] = null;
        $order['customer_id'] = $user ? $user->id : null;
        $order['customer'] = $user ? [
            'username' => $user->username,
            'email' => $user->email,
            'phone' => $user->phone
        ] : null;
        /* @var $provider Provider */
        $provider = isset($item->providers[0]) ? $item->providers[0] : $item->providers;

        if ($sellerId !== null) {
            foreach ((array)$item->providers as $pro) {
                /* @var $pro Provider */
                if (
                    (strtoupper($item->type) === BaseProduct::TYPE_EBAY && $pro->name === $sellerId) ||
                    (strtoupper($item->type) !== BaseProduct::TYPE_EBAY && $pro->prov_id === $sellerId)
                ) {
                    $provider = $pro;
                    break;
                }
            }
        }
        $order['seller'] = [
            'seller_name' => $provider->name,
            'portal' => $item->type,
            'seller_store_rate' => $provider->rating_score,
            'seller_link_store' => $provider->website
        ];
        $product = [];
        $product['portal'] = $item->type;
        $product['sku'] = $item->item_sku;
        $product['parent_sku'] = $item->item_id;
        $product['link_img'] = $currentImage !== null ? $currentImage : ($item->current_image !== null ? $item->current_image : $product->primary_images[0]->main);
        $product['link_origin'] = $item->item_origin_url;
        $product['remove'] = 0;
        $product['condition'] = $item->condition;
        $variations = null;
        foreach ((array)$item->variation_mapping as $v) {
            /** @var $v VariationMapping */
            if ($v->variation_sku === $item->item_sku) {
                $variations = $v;
                break;
            }
        }
        $product['available_quantity'] = $item->available_quantity;
        $product['quantity_sold'] = $item->quantity_sold;
        $product['variations'] = $variations;
        $product['product_link'] = $item->ws_link;
        $product['product_name'] = $item->item_name;
        $product['quantity_customer'] = $item->getShippingQuantity();
        $product['total_weight_temporary'] = $item->getShippingWeight();
        $product['category'] = [
            'alias' => $item->category_id,
            'site' => $item->type,
            'origin_name' => ArrayHelper::getValue($item, 'category_name', 'Unknown'),
        ];
        $additionalFees = $item->getAdditionalFees();
        // 'đơn giá gốc ngoại tệ bao gồm các phí tại nơi xuất xứ (tiền us, us tax, us ship)
        $product['price_amount_origin'] = $item->getTotalOriginPrice();
        // Tổng tiền các phí, trừ tiền gốc sản phẩm (chỉ có các phí)
        $product['total_fee_product_local'] = $additionalFees->getTotalAdditionFees(null, ['product_price_origin'])[1];         // Tổng Phí theo sản phẩm
        // Tổng tiền local gốc sản phẩm (chỉ có tiền gốc của sản phẩm)
        $product['price_amount_local'] = $additionalFees->getTotalAdditionFees('product_price_origin')[1];  // đơn giá local = giá gốc ngoại tệ * tỉ giá Local
        // Tổng tiền local tất tần tận
        $product['total_price_amount_local'] = $additionalFees->getTotalAdditionFees()[1];
        $productFees = [];
        foreach ($additionalFees->keys() as $feeName) {
            $fee = [];
            list($fee['amount'], $fee['local_amount']) = $additionalFees->getTotalAdditionFees($feeName);
            $fee['discount_amount'] = 0;
            $fee['name'] = $additionalFees->getStoreAdditionalFeeByKey($feeName)->label;
            $fee['currency'] = $additionalFees->getStoreAdditionalFeeByKey($feeName)->currency;
            $orderAttribute = "total_{$feeName}_local";
            if ($feeName === 'product_price_origin') {
                // Tổng giá gốc của các sản phẩm tại nơi xuất xứ
                $orderAttribute = 'total_origin_fee_local';
            } elseif ($feeName === 'tax_fee_origin') {
                // Tổng phí tax của các sản phẩm tại nơi xuất xứ
                $orderAttribute = 'total_origin_tax_fee_local';
            } elseif ($feeName === 'custom_fee') {
                // Tổng phí tax của các sản phẩm tại nơi xuất xứ
                $orderAttribute = 'total_custom_fee_amount_local';
            } elseif ($feeName === 'vat_fee') {
                // Tổng vat của các sản phẩm
                $orderAttribute = 'total_vat_amount_local';
            } elseif ($feeName === 'delivery_fee_local') {
                // Tổng vận chuyển tại local của các sản phẩm
                $orderAttribute = 'total_delivery_fee_local';
            }
            // Tiền Phí
            $order[$orderAttribute] = $fee['local_amount'];
            if ($orderAttribute === 'total_origin_fee_local') {
                $order['total_price_amount_origin'] = $fee['amount'];
            }
            $productFees[$feeName] = $fee;
        }
        $product['fees'] = $productFees;

        // Tổng tiền Discount
        $order['total_promotion_amount_local'] = 0;
        // Tổng tiền paid
        $order['total_paid_amount_local'] = 0;
        // Tổng các phí các sản phẩm (trừ giá gốc tại nơi xuất xứ)
        $order['total_fee_amount_local'] = $product['total_fee_product_local'];
        // Tổng tiền (bao gồm tiền giá gốc của các sản phẩm và các loại phí)
        $order['total_amount_local'] = $product['total_price_amount_local'];
        $order['total_final_amount_local'] = $order['total_amount_local'];
        $order['total_weight_temporary'] = $product['total_weight_temporary'];
        $order['total_quantity'] = $product['quantity_customer'];
        $order['products'] = [$product];
        return $order;
    }


    public static function mergeItem($source, $target)
    {
        $start = microtime(true);
        $orders = func_get_args();
        $order = array_shift($orders);
        while (!empty($orders)) {
            foreach (array_shift($orders) as $key => $value) {
                if (strpos($key, 'total_') !== false) {
                    $oldValue = (int)$order[$key];
                    $newValue = (int)$value;
                    $oldValue += $newValue;
                    $order[$key] = $oldValue;
                } elseif ($key === 'products') {
                    $products = $order['products'];
                    $products[] = reset($value);
                    $order['products'] = $products;
                }
            }
        }
        $time = sprintf('%.3f', microtime(true) - $start);
        Yii::info("time: $time s", __METHOD__);
        return $order;
    }


    public static function createOrderParams($type, $keys, $safeOnly = true)
    {
        $start = microtime(true);
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        $orders = [];
        $totalFinalAmount = 0;
        $items = self::getCartManager()->getItems($type, $keys);
        foreach ($items as $item) {
            $order = $item['value'];
            $totalFinalAmount += (int)$order['total_amount_local'];
            $orders[] = $order;
        }
        $time = sprintf('%.3f', microtime(true) - $start);
        Yii::info("time: $time s", __METHOD__);

        return [
            'countKey' => count($keys),
            'countItem' => count($items),
            'orders' => $orders,
            'totalAmount' => $totalFinalAmount
        ];
    }
}