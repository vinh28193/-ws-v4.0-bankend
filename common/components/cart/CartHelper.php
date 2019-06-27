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
use DateTime;
use yii\db\Query;
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
        $order['cancelled'] = null;
        $order['customer_type'] = 'Retail';
        $order['store_id'] = $storeManager->getId();
        $order['exchange_rate_fee'] = $storeManager->getExchangeRate();
        $order['sale_support_id'] = null;
        $order['support_email'] = null;
        $order['check_insurance'] = 0;
//        $order['check_inspection'] = 0;
//        $order['check_inspection'] = 0;
//        $order['total_insurance_fee_local'] = 0; // bảo hiểm đê mặc định k chuyền
        $order['saleSupport'] = null;
        if (Yii::$app->user->getId()) {
            $order['potential'] = 1; // sale ưu tiên chăm đơn
        } else {
            $order['potential'] = 0; // khách hàng không dăng nhập
        }
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
                if ($pro->name === $sellerId) {
                    $provider = $pro;
                    break;
                }
            }
        }
        $order['seller'] = [
            'seller_name' => $provider->name,
            'portal' => $item->type,
            'seller_store_rate' => $provider->rating_score,
            'seller_link_store' => $provider->website,
            'location' => $provider->location,
            'country_code' => $provider->country_code
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
        $productPrice = $additionalFees->getTotalAdditionalFees('product_price');
        // Tổng tiền các phí, trừ tiền gốc sản phẩm (chỉ có các phí)
        $product['total_fee_product_local'] = $additionalFees->getTotalAdditionalFees(null, ['product_price'])[1];         // Tổng Phí theo sản phẩm
        // Tổng tiền local gốc sản phẩm (chỉ có tiền gốc của sản phẩm)
        list($product['price_amount_origin'], $product['price_amount_local']) = $productPrice;
        $product['price_amount_origin'] = $product['price_amount_origin'] / $item->getShippingQuantity();
        $product['price_amount_local'] = $product['price_amount_local'] / $item->getShippingQuantity();

        $product['total_price_amount_local'] = $productPrice[1];
        // Tổng tiền local tất tần tận
        $product['total_final_amount_local'] = $additionalFees->getTotalAdditionalFees(null)[1];
        $productFees = [];
        $product['additionalFees'] = $additionalFees->toArray();
        foreach ($additionalFees->keys() as $feeName) {
            $fee = [];
            list($fee['amount'], $fee['local_amount']) = $additionalFees->getTotalAdditionalFees($feeName);
            if ($feeName === 'product_price') {
                // Tổng giá gốc của các sản phẩm tại nơi xuất xứ
                $order['total_price_amount_origin'] = $fee['amount'];
                $order['total_origin_fee_local'] = $fee['local_amount'];
            } elseif ($feeName === 'tax_fee') {
                // Tổng phí tax của các sản phẩm tại nơi xuất xứ
                $order['total_origin_tax_fee_local'] = $fee['local_amount'];
            } elseif ($feeName === 'shipping_fee') {
                // Tổng phí tax của các sản phẩm tại nơi xuất xứ
                $order['total_origin_shipping_fee_local'] = $fee['local_amount'];
            } elseif ($feeName === 'purchase_fee') {
                // Tổng phí tax của các sản phẩm tại nơi xuất xứ
                $order['total_weshop_fee_local'] = $fee['local_amount'];
            } elseif ($feeName === 'international_shipping_fee') {
                // Tổng vat của các sản phẩm
                $order['total_intl_shipping_fee_local'] = $fee['local_amount'];
            } elseif ($feeName === 'vat_fee') {
                // Tổng vận chuyển tại local của các sản phẩm
                $order['total_vat_amount_local'] = $fee['local_amount'];
            } else if ($feeName === 'delivery_fee') {
                $order['total_vat_amount_local'] = $fee['local_amount'];
            }
            // Tiền Phí
            $productFees[$feeName] = $fee;
        }

        // Tổng tiền Discount
        $order['total_promotion_amount_local'] = 0;
        // Tổng tiền paid
        $order['total_paid_amount_local'] = 0;
        // Tổng các phí các sản phẩm (trừ giá gốc tại nơi xuất xứ)
        $order['total_fee_amount_local'] = $product['total_fee_product_local'];
        // Tổng tiền (bao gồm tiền giá gốc của các sản phẩm và các loại phí)
        $order['total_amount_local'] = $product['total_price_amount_local'];
        $order['total_final_amount_local'] = $order['total_amount_local'] + $order['total_fee_amount_local'];
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


    public static function createOrderParams($type, $keys, $uuid = null)
    {
        $start = microtime(true);
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        $orders = [];
        $totalFinalAmount = 0;
        $items = self::getCartManager()->getItems($type, $keys, $uuid);
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

    public static function getTimeEndOfDay($value = 'now')
    {
        $dateTime = new DateTime($value);
        $dateTime->setTime(23, 59, 59);
        return Yii::$app->formatter->asDatetime($dateTime);
    }

    public static function beginSupportDay()
    {
        return self::getTimeEndOfDay('now - 1 days');
    }

    public static function endSupportDay()
    {
        return self::getTimeEndOfDay('now');
    }

    public static function getSupportAssign()
    {
        $userManager = Yii::$app->getAuthManager();
        $idSale = $userManager->getUserIdsByRole('sale');
        $idMasterSale = $userManager->getUserIdsByRole('master_sale');
        $query = new Query();
        $query->from(['u' => User::tableName()]);
        $query->select(['id', 'mail']);
        $query->where([
            'AND',
            ['remove' => 0],
            ['OR',
                ['id' => $idSale],
                ['id' => $idMasterSale]
            ]
        ]);
        $sales = $query->all(User::getDb());
    }
}