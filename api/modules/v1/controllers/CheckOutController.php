<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-08
 * Time: 13:07
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\components\cart\CartManager;
use common\models\db\Category;
use common\models\Order;
use common\models\Product;
use common\models\ProductFee;
use common\models\Seller;
use Yii;
use yii\db\Connection;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

class CheckOutController extends BaseApiController
{

    /**
     * @var string|Connection
     */
    protected $db = 'db';

    /**
     * @var string|CartManager
     */
    protected $cart = 'cart';

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());
        $this->cart = Instance::ensure($this->cart, CartManager::className());
    }

    public function verbs()
    {
        return [
            'index' => ['GET'],
            'create' => ['POST']
        ];
    }

    public function actionIndex()
    {

    }

    public function actionCreate()
    {
        $this->cart->removeItems();
        $this->getCart()->removeItems();

        $this->getCart()->addItem('ebay', 'cleats_blowout_sports', 1, 'test', '252888606889');
        $this->getCart()->addItem('ebay', 'cleats_blowout_sports', 1, 'test', '254113708379');
        $this->getCart()->addItem('ebay', 'mygiftstop', 1, 'test', '332800694983');
        $this->getCart()->addItem('ebay', 'mygiftstop', 10, 'test', '332800694983');
        $this->getCart()->addItem('amazon', 'QVVESU9MQUIgLSBTaW5jZSAxOTU4LU5ldy0yNzk=', 10, 'test', 'B07C49F2LD');
        $this->getCart()->addItem('amazon', 'QW1hem9uLmNvbS1OZXctMjc5', 10, 'test', 'B07C49F2LD');
        $items = $this->getCart()->getItems();

        $items = $this->cart->getItems();

        $orders = [];
        $errors = [];

        foreach ($items as $seller => $arrays) {
            list($portal, $seller) = explode(':', $seller);
            $sellerId = str_replace(['ebay:', 'amazon:'], '', $seller);
            $seller = null;
            $products = [];

            foreach ($arrays as $key => $simpleItem) {
                /** @var  $item \common\products\BaseProduct */
                $item = $simpleItem->item;
                $rs['item'] = $item;
                if (empty($item->providers)) {
                    $errors[$seller][$key][] = "can not create form null seller";
                }
                foreach ($item->providers as $provider) {
                    /** @var $provider \common\products\Provider */
                    if ($seller === null && $provider->prov_id === $sellerId) {
                        $seller = $provider;
                        break;
                    }
                }
                if (($categoryId = ArrayHelper::getValue($item, 'category_id')) === null) {
                    $errors[$seller][$key][] = "can not create form null category";
                    continue;
                }
                if (($category = Category::findOne(['AND', ['alias' => $categoryId], ['site' => $item->type]])) === null) {
                    $category = new Category();
                    $category->alias = $categoryId;
                    $category->site = $item->type;
                    $category->origin_name = ArrayHelper::getValue($item, 'category_name', 'Unknown');
                    $category->save();
                }
                $rs['category'] = $category;
            }

            if($seller === null){
                continue;
            }
            $order = new Order();
            $order->type_order = 'SHOP';
            $order->ordercode = 'WSVN' . @rand(10, 100000);
            $order->store_id = 1;

            $order->portal = $portal;
            $order->quotation_status = 0;
            $order->is_quotation = 0;
            $order->customer_id = Yii::$app->getUser()->getId();
            $order->current_status = 'NEW';
            $order->quotation_note = null;
            $order->receiver_country_id = 1;
            $order->receiver_country_name = 'Việt Nam';
            $order->receiver_province_id = 1;
            $order->receiver_province_name = 'Hồ Chí Minh';
            $order->receiver_district_id = 2;
            $order->receiver_district_name = 'Đà Nẵng';
            $order->receiver_post_code = '24800-8633';
            $order->receiver_address_id = 2;


            $rs = [];
            /** @var  $simpleItem \common\components\cart\item\SimpleItem */
            $item = $simpleItem->item;
            $rs['item'] = $item;

            // Seller
            if (($providers = ArrayHelper::getValue($item, 'providers')) === null) {
                $errors[$key][] = "can not create form null seller";
                continue;
            }

            /**
             * todo get current seller
             */
            $sellers = [];
            foreach ($providers as $provider) {
                /** @var $provider \common\products\Provider */
                if ($seller === null && $provider->prov_id) {
                    $seller = new Seller();
                    $seller->seller_name = $provider['name'];
                    $seller->seller_link_store = $provider['website'];
                }
                $seller->seller_store_rate = $provider['rating_score'];
                $seller->save(false);
                $sellers[] = $seller;
            }
            $rs['sellers'] = $sellers;
            // Category
            if (($categoryId = ArrayHelper::getValue($item, 'category_id')) === null) {
                $errors[$key][] = "can not create form null category";
                continue;
            }
            if (($category = Category::findOne(['AND', ['alias' => $categoryId], ['site' => $item->type]])) === null) {
                $category = new Category();
                $category->alias = $categoryId;
                $category->site = $item->type;
                $category->origin_name = ArrayHelper::getValue($item, 'category_name', 'Unknown');
                $category->save();
            }
            $rs['category'] = $category;
            $product = new Product;
            $product->sku = $item->item_sku;
            $product->parent_sku = $item->item_id;
            $product->link_origin = $item->item_origin_url ? $item->item_origin_url : '';
            $product->category_id = $category->id;
            list($product->price_amount_origin, $product->total_price_amount_local) = $product->getAdditionalFees()->getTotalAdditionFees();
            $product->quantity_customer = $item->quantity;
            $product->seller_id = $sellers[0]->id;
            // Order

            // Todo with ProductFee

            $order->save(false);
            $order->link('products', $product);
            $rs['products'] = $product;
            $orderUpdateFeeAttribute = [];
            $data_key = [];
            $productFee = [];
            foreach ($item->additionalFees->keys() as $key) {

                list($amount, $local) = $item->getAdditionalFees()->getTotalAdditionFees($key);
                $orderAttribute = "total_{$key}_local";
                if ($key === 'product_price_origin') {
                    $orderAttribute = 'total_origin_fee_local';
                } elseif ($key === 'tax_fee_origin') {
                    $orderAttribute = 'total_origin_tax_fee_local';
                } elseif ($key === 'delivery_fee_local') {
                    $orderAttribute = 'total_delivery_fee_local';
                }

                $data_key[$key] = $key;

                $orderFee = new ProductFee();
                $orderFee->type = $key;
                $orderFee->name = $item->getAdditionalFees()->getStoreAdditionalFeeByKey($key)->label;
                $orderFee->order_id = $order->id;
                $orderFee->product_id = $product->id;
                $orderFee->amount = $amount;
                $orderFee->local_amount = $local;
                $orderFee->currency = $item->getAdditionalFees()->getStoreAdditionalFeeByKey($key)->currency;
                if ($orderFee->save() && $order->hasAttribute($orderAttribute)) {
                    $orderUpdateFeeAttribute[$orderAttribute] = $local;
                }
                $productFee[$key] = $orderFee;
            }

            $orderUpdateFeeAttribute['total_fee_amount_local'] = $item->getAdditionalFees()->getTotalAdditionFees()[1];
            $order->updateAttributes($orderUpdateFeeAttribute);
            $rs['key'] = $data_key;
            $rs['productFee'] = $productFee;
            $rs['orderUpdateFee'] = $orderUpdateFeeAttribute;
            $rs['order'] = $order;
            $orders[$order->id] = $rs;
        }

        Yii::$app->api->sendSuccessResponse($orders);

    }
}
