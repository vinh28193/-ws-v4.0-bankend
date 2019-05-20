<?php


namespace common\components\cart;


use common\models\Order;
use common\models\Product;
use common\models\Seller;
use common\products\BaseProduct;
use common\products\Provider;
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
        return ArrayHelper::index($items, 'key', function ($item) {
            $request = $item['request'];
            return $request['type'] . ':' . $request['seller'];
        });
    }

    public static function createOrderParams($keys, $safeOnly = true)
    {
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        $items = [];
        foreach ($keys as $key) {
            $items[$key] = self::getCartManager()->getItem($key, $safeOnly);
        }
        $orders = [];
        $totalFinalAmount = 0;
        foreach (self::group($items) as $key => $arrays) {
            list($type, $sellerId) = explode(':', $key);
            /** @var  $provider null |Provider */
            $provider = null;
            $providers = $arrays;
            $providers = reset($providers)['response']->providers;
            if ($provider === null) {
                $provider = $providers[0];
            }

            $order = [];
            $order['type_order'] = Order::TYPE_SHOP;
            $order['portal'] = $type;
            $order['seller'] = [
                'seller_name' => $sellerId,
                'portal' => $type,
                'seller_store_rate' => $provider->rating_score,
                'seller_link_store' => $provider->website
            ];
            $products = [];
            $totalOrderAmount = 0;
            $totalOrderQuantity = 0;
            $totalOrderWeightTemporary = 0;
            foreach ($arrays as $id => $array) {
                /** @var $item BaseProduct */
                if (!isset($array['response']) || !($item = $array['response']) instanceof BaseProduct) {
                    continue;
                }
                $request = isset($array['request']) ? $array['request'] : [];
                $product = [];
                $product['portal'] = $item->type;
                $product['sku'] = $item->item_sku;
                $product['parent_sku'] = $item->item_id;
                $product['link_img'] = isset($request['image']) ? $request['image'] : ($item->current_image !== null ? $item->current_image : $item->primary_images[0]->main);
                $product['link_origin'] = $item->item_origin_url;
                $product['product_link'] = $item->ws_link;
                $product['product_name'] = $item->item_name;
                $product['quantity_customer'] = $item->getShippingQuantity();
                $product['total_weight_temporary'] = $item->getShippingWeight();     //"cân nặng  trong lượng tạm tính"
                $totalOrderQuantity += $product['quantity_customer'];
                $totalOrderWeightTemporary += $product['total_weight_temporary'];
                $product['category'] = [
                    'alias' => $item->category_id,
                    'site' => $type,
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
                $totalOrderAmount += $product['total_price_amount_local'];
                $totalFinalAmount += $product['total_price_amount_local'];

                $productFees = [];
                foreach ($additionalFees->keys() as $feeName) {
                    $fee = [];
                    list($fee['amount'], $fee['local_amount']) = $item->getAdditionalFees()->getTotalAdditionFees($feeName);
                    $fee['discount_amount'] = 0;
                    $fee['name'] = $item->getAdditionalFees()->getStoreAdditionalFeeByKey($feeName)->label;
                    $fee['currency'] = $item->getAdditionalFees()->getStoreAdditionalFeeByKey($feeName)->currency;
                    $productFees[$feeName] = $fee;
                }
                $product['fees'] = $productFees;
                $products[$id] = $product;
            }
            $order['total_weight_temporary'] = $totalOrderWeightTemporary;
            $order['total_quantity'] = $totalOrderQuantity;
            $order['totalAmount'] = $totalOrderAmount;
            $order['products'] = $products;
            $orders[$key] = $order;
        }
        return [
            'countKey' => count($keys),
            'countItem' => count($items),
            'orders' => $orders,
            'totalAmount' => $totalFinalAmount
        ];
    }
}