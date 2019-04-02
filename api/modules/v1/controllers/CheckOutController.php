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
        $this->cart->addItem('IF_739F9D0E', 'cleats_blowout_sports', 1, 'ebay', 'https://i.ebayimg.com/00/s/MTYwMFgxMDY2/z/cAQAAOSwMn5bzly6/$_12.JPG?set_id=880000500F', '252888606889');
       // $this->cart->addItem('B01MQDLB83', 'ZVN1cHBsZW1lbnRzLU5ldy0xNi45NQ==', 1, 'amazon', 'https://images-na.ssl-images-amazon.com/images/I/41lv8DmLJvL.jpg');

        //$this->cart->addItem('IF_6C960C53', 'cleats_blowout_sports', 1, 'ebay', 'https://i.ebayimg.com/00/s/MTYwMFgxMDY2/z/nrsAAOSw7Spbzlyw/$_12.JPG?set_id=880000500F', '252888606889');
        //$this->cart->addItem('261671375738', 'luv4everbeauty', 1, 'ebay', 'https://i.ebayimg.com/00/s/NTk3WDU5Nw==/z/FjMAAOSwscNbK5~0/$_57.JPG');
        // Toto CheckOutForm to validate data form all

        $items = $this->cart->getItems();

        $orders = [];
        $errors = [];
        $Object_detech = [];
        $orderUpdateFeeAttribute = $productFee = []; $i=0;

        foreach ($items as $key => $simpleItem) {

            /** @var  $simpleItem \common\components\cart\item\SimpleItem */
            $item = $simpleItem->item;
            $Object_detech = $item;

            // Seller
            if (($providers = ArrayHelper::getValue($item, 'providers')) === null || ($item->type === 'EBAY' &&  $providers !== null && !isset($providers['name']))) {
                $errors[$key][] = "can not create form null seller";
                continue;
            }
            /**
             * seller ebay va amazon khac nhau, hien tai chia parse ve 1 dang, vi amzon lau theo api offfer
             */
            if (isset($providers[0]) && $item->type !== 'EBAY') {
                $s = [];
                foreach ($providers as $provider) {
                    $s['name'] = $provider->prov_id;
                    $s['website'] = null;
                    $s['rating_score'] = $provider->rating_score;
                }
                $providers = $s;
            }
            if (($seller = Seller::findOne(['seller_name' => $providers['name']])) === null) {
                $seller = new Seller();
                $seller->seller_name = $providers['name'];
                $seller->seller_link_store = $providers['website'];
                $seller->seller_store_rate = $providers['rating_score'];
                $seller->save(false);
            }
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

            $product = new Product;
            $product->sku = $item->item_sku;
            $product->parent_sku = $item->item_id;
            $product->link_origin = $item->item_origin_url ? $item->item_origin_url : '';
            $product->getAdditionalFees()->mset($item->additionalFees);
            $product->category_id = $category->id;
            list($product->price_amount_origin, $product->total_price_amount_local) = $product->getAdditionalFees()->getTotalAdditionFees();
            $product->quantity_customer = $item->quantity;
            $product->seller_id = $seller->id;
            // Order
            $order = new Order();
            $order->type_order = 'SHOP';
            $order->ordercode = 'WSVN' . @rand(10, 100000);
            $order->store_id = 1;
            $order->seller_id = $seller->id;
//            $order->portal = $item->type;
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
            // Todo with ProductFee

            $order->save(false);
            $order->link('products', $product);
            $orderUpdateFeeAttribute = [];
            $data_key = [];
            foreach ($product->getAdditionalFees()->keys() as $key) {
                list($amount, $local) = $product->getAdditionalFees()->getTotalAdditionFees($key);
                $orderAttribute = "total_{$key}_local";
                if ($key === 'product_price_origin') {
                    $orderAttribute = 'total_origin_fee_local';
                } elseif ($key === 'tax_fee_origin') {
                    $orderAttribute = 'total_origin_tax_fee_local';
                } elseif ($key === 'delivery_fee_local') {
                    $orderAttribute = 'total_delivery_fee_local';
                }

                $data_key[$key] = $key;

                // Todo with Product Fee
                $orderFee = new ProductFee();
                $orderFee->type = $key;
                $orderFee->name = $product->getAdditionalFees()->getStoreAdditionalFeeByKey($key)->label;
                $orderFee->order_id = $order->id;
                $orderFee->product_id = $product->id;
                $orderFee->amount = $amount;
                $orderFee->local_amount = $local;
                $orderFee->currency = $product->getAdditionalFees()->getStoreAdditionalFeeByKey($key)->currency;
                if ($orderFee->save() && $order->hasAttribute($orderAttribute)) {
                    $orderUpdateFeeAttribute[$orderAttribute] = $local;
                }
                $productFee[$i++] = $orderFee;
            }
            $orderUpdateFeeAttribute['total_fee_amount_local'] = $product->getAdditionalFees()->getTotalAdditionFees()[1];
            $order->updateAttributes($orderUpdateFeeAttribute);
            $orders[] = $order->id;
        }

        $_itemRes = [];
        $_itemRes['Object_detech_api_'] = $Object_detech;
        $_itemRes['seller'] = $seller;
        $_itemRes['category'] = $category;
        $_itemRes['order'] = $orders;
        $_itemRes['product'] = $product;
        $_itemRes['productFee'] = $productFee;
        $_itemRes['orderUpdateFee'] = $orderUpdateFeeAttribute;
        $_itemRes['key'] = $data_key;

        Yii::$app->api->sendSuccessResponse($_itemRes);

    }
}
