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

    public static function group($items)
    {
        return ArrayHelper::index($items, null, function ($item) {
            $item = $item['order'];
            return $item['portal'] . ':' . $item['seller']['seller_name'];
        });
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
        $order['portal'] = $item->type;
        $order['current_status'] = Order::STATUS_NEW;
        $order['new'] = Yii::$app->getFormatter()->asTimestamp('now');
        $order['customer_type'] = 'Retail';
        $order['store_id'] = $storeManager->getId();
        $order['exchange_rate_fee'] = $storeManager->getExchangeRate();
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
                $product['available_quantity'] = $v->available_quantity;
                $product['quantity_sold'] = $v->quantity_sold;
                $variations = $v;
                break;
            }
        }
        $product['variations'] = $variations;
        $product['product_link'] = $item->ws_link;
        $product['product_name'] = $item->item_name;
        $product['quantity_customer'] = $item->getShippingQuantity();
        $product['total_weight_temporary'] = $item->getShippingWeight();     //"cân nặng  trong lượng tạm tính"
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

    public static function createOrderParams($keys, $safeOnly = true)
    {
        $start = microtime(true);
        $noUpdateAttribute = [
            'products', 'type_order', 'portal', 'current_status', 'new', 'customer_type', 'store_id', 'exchange_rate_fee', 'customer_id', 'customer', 'seller',
        ];
        if (!is_array($keys)) {
            $keys = [$keys];
        }

        $items = [];
        foreach ($keys as $key) {
            $items[] = self::getCartManager()->getItem($key, $safeOnly);
        }
        $orders = [];
        $totalFinalAmount = 0;
        foreach (self::group($items) as $key => $arrays) {
            $first = array_shift($arrays);
            $order = $first['order'];
            $totalFinalAmount += (int)$order['total_amount_local'];
            $products = $order['products'][0];
            $order['products'] = [];
            $order['products'][$first['key']] = $products;
            if (count($arrays) > 0) {
                foreach ($arrays as $cartId => $params) {
                    if (($item = ArrayHelper::getValue($params, ['order'])) === null) {
                        continue;
                    }
                    foreach (array_keys($order) as $attribute) {
                        if (ArrayHelper::isIn($attribute, $noUpdateAttribute)) {
                            continue;
                        }
                        if (strpos($attribute, 'total_') !== false) {
                            $oldValue = (int)$order[$attribute];
                            $newValue = (int)$item[$attribute];
                            if ($attribute === 'total_amount_local') {
                                $totalFinalAmount += $newValue;
                            }
                            $oldValue += $newValue;
                            $order[$attribute] = $oldValue;

                        }
                    }
                    $products = $order['products'];
                    $products[$params['key']] = $item['products'][0];
                    $order['products'] = $products;
                }
            }
            $orders[$key] = $order;
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