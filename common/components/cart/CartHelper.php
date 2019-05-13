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

    public static function group($items)
    {
        return ArrayHelper::index($items, 'key', function ($item) {
            $request = $item['request'];
            return $request['type'] . ':' . $request['seller'];
        });
    }

    public static function createOrderParams($items)
    {
        if (empty($items)) {
            return [];
        }
        $orders = [];
        $totalAmount = 0;
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
                $product['link_img'] = isset($request['image']) ? $request['image'] : $item->current_image;
                $product['link_origin'] = $item->item_origin_url;
                $product['product_link'] = 'https://weshop.com.vn/link/sanpham.html';
                $product['product_name'] = $item->item_name;
                $product['quantity_customer'] = $item->quantity;
                $product['total_weight_temporary'] = $item->shipping_weight;     //"cân nặng  trong lượng tạm tính"
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
                $totalAmount += $product['total_price_amount_local'];

                $productFees = [];
                foreach ($additionalFees->keys() as $key) {
                    $productFees[$key] = array_combine(['amount', 'amount_local'], $item->getAdditionalFees()->getTotalAdditionFees($key));
                }
                $product['fees'] = $productFees;
                $products[$id] = $product;
            }

            $order['products'] = $products;
            $orders[] = $order;
        }
        return [
            'orders' => $orders,
            'totalAmount' => $totalAmount
        ];
    }
}