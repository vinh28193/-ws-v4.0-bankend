<?php
namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\components\cart\CartManager;


/** Dữ liệu Order **/
use common\models\Seller;
use common\models\Order;

/** Sản Phẩm 1-1 , 1-n **/
use common\models\Product;
use common\models\OrderFee as ProductFee;

/** Tính Phụ Thu danh mục**/
use common\models\db\Category;

/** Payment + Wallet Log **/

/** Nhân Viên Support **/
/** Role :
    case 'cms':
    case 'warehouse':
    case 'operation':
    case 'sale':
    case 'master_sale':
    case 'master_operation':
    case 'superAdmin' :
 **/

/** Gói Kiện hàng về : Từ người bán gửi về Kho Mỹ **/
use common\models\Package;
use common\models\PackageItem;

/** Package Tạm + Dữ liệu Kiểm thực tế với BOXME **/


use Yii;
use yii\db\Connection;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

class DataFixedController extends BaseApiController
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

    /** Dữ liệu giả lập add Card **/
    protected function CartData($dataPost = [])
    {
        $this->cart->removeItems();
        //$this->cart->addItem('IF_739F9D0E', 'cleats_blowout_sports', 1, 'ebay', 'https://i.ebayimg.com/00/s/MTYwMFgxMDY2/z/cAQAAOSwMn5bzly6/$_12.JPG?set_id=880000500F', '252888606889');
        //$this->cart->addItem('IF_6C960C53', 'cleats_blowout_sports', 1, 'ebay', 'https://i.ebayimg.com/00/s/MTYwMFgxMDY2/z/nrsAAOSw7Spbzlyw/$_12.JPG?set_id=880000500F', '252888606889');
        //$this->cart->addItem('261671375738', 'luv4everbeauty', 1, 'ebay', 'https://i.ebayimg.com/00/s/NTk3WDU5Nw==/z/FjMAAOSwscNbK5~0/$_57.JPG');

        // $sku, $seller, $quantity, $source, $image, $parentSku
        // Thieu Mã giảm giá , Phương thức thanh toán
        $this->cart->addItem(
            $dataPost['sku'],
            $dataPost['seller'],
            $dataPost['quantity'],
            $dataPost['source'],
            $dataPost['image'],
            $dataPost['parentSku']
            );
    }

    protected function SellerData($item,$key)
    {
        if (($providers = ArrayHelper::getValue($item, 'providers')) === null || ($providers !== null && !isset($providers['name']))) {
            $errors[$key][] = "can not create form null seller";
            //continue;
            Yii::$app->api->sendFailedResponse("can not create form null seller");
        }
        if (($seller = Seller::findOne(['seller_name' => $providers['name']])) === null) {
            $seller = new Seller();
            $seller->seller_name = $providers['name'];
            $seller->seller_link_store = $providers['website'];
            $seller->seller_store_rate = $providers['rating_score'];
            $seller->save(false);
        }

        return $seller;
    }

    protected function CategoryData($item,$key,$itemType)
    {
        if (($categoryId = ArrayHelper::getValue($item, 'category_id')) === null) {
            $errors[$key][] = "can not create form null category";
            //continue;
            Yii::$app->api->sendFailedResponse("can not create form null category . Check Data Category or tables Category ");
        }
        if (($category = Category::findOne(['AND', ['alias' => $categoryId], ['site' => $itemType]])) === null) {
            $category = new Category();
            $category->alias = $categoryId;
            $category->site = $itemType;
            $category->origin_name = ArrayHelper::getValue($item, 'category_name', 'Unknown');
            $category->save();
        }

        return $category;
    }

    protected function ProductData($item,$category)
    {
        $product = new Product;
        $product->sku = $item['item_sku'];
        $product->parent_sku = $item['item_id'];
        $product->link_origin = $item['item_origin_url'];
        $product->getAdditionalFees()->mset($item['additionalFees']);
        $product->category_id = $category->id;
        list($product->price_amount_origin, $product->total_price_amount_local) = $product->getAdditionalFees()->getTotalAdditionFees();
        $product->quantity_customer = $item['quantity'];

        return $product;
    }

    protected function OrderData($itemType , $seller , $product)
    {
        $order = new Order();
        $order->type_order = 'SHOP';
        $order->ordercode = 'WSVN'. @rand(10,100000);
        $order->store_id = 1;
        $order->portal = $itemType;
        $order->quotation_status = 0;
        $order->is_quotation = 0;
        $order->customer_id = Yii::$app->getUser()->getId();
        $order->receiver_email = 'vinhvv@peacesolt.net';
        $order->receiver_name = 'Vinh';
        $order->receiver_phone = '0987654321';
        $order->receiver_address = '123 Tam Trinh';
        $order->receiver_district_id = 1;
        $order->seller_id = $seller->id;

//
        /*
        $order->new = 1195200481;
        $order->purchased = null;
        $order->seller_shipped = null;
        $order->stockin_us = null;
        $order->stockout_us = null;
        $order->stockin_local = null;
        $order->stockout_local = null;
        $order->at_customer = null;
        $order->returned = null;
        $order->cancelled = null;
        $order->lost = null;
        $order->current_status = 'NEW';
        $order->quotation_note = null;
        $order->receiver_country_id = 1;
        $order->receiver_country_name = 'Việt Nam';
        $order->receiver_province_id = 96;
        $order->receiver_province_name = 'Hồ Chí Minh';
        $order->receiver_district_id = 2;
        $order->receiver_district_name = 'Đà Nẵng';
        $order->receiver_post_code = '24800-8633';
        $order->receiver_address_id = 2;
        $order->note_by_customer = 'Canterbury; found it so quickly that the cause.';
        $order->note = 'The Cat\'s head with great curiosity; and this.';
        $order->seller_name = 'Stuart Friesen';
        $order->seller_store = 'http://www.conn.com/';
        $order->total_final_amount_local = 0;
        $order->total_amount_local = 0;
        $order->total_origin_fee_local = 0;
        $order->total_price_amount_origin = 0;
        $order->total_paid_amount_local = 0;
        $order->total_refund_amount_local = 0;
        $order->total_counpon_amount_local = 0;
        $order->total_promotion_amount_local = 0;
        $order->total_fee_amount_local = 0;
        $order->total_origin_tax_fee_local = 0;
        $order->total_origin_shipping_fee_local = 0;
        $order->total_weshop_fee_local = 0;
        $order->total_intl_shipping_fee_local = 0;
        $order->total_custom_fee_amount_local = 0;
        $order->total_delivery_fee_local = 0;
        $order->total_packing_fee_local = 0;
        $order->total_inspection_fee_local = 0;
        $order->total_insurance_fee_local = 0;
        $order->total_vat_amount_local = 0;
        $order->exchange_rate_fee = 23000;
        $order->exchange_rate_purchase = 0;
        $order->currency_purchase = 'AUD';
        $order->payment_type = 'WALLET';
        $order->sale_support_id = 5;
        $order->support_email = 'nga.cn@mac.gov.vn';
        $order->is_email_sent = 0;
        $order->is_sms_sent = 0;
        $order->difference_money = 1;
        $order->coupon_id = null;
        $order->revenue_xu = 0;
        $order->xu_count = 0;
        $order->xu_amount = 0;
        $order->xu_time = null;
        $order->xu_log = 'I said "What for?"\' \'She boxed the Queen\'s.';
        $order->promotion_id = null;
        $order->total_weight = 1;
        $order->total_weight_temporary = 1;
        $order->created_at = 295165046;
        $order->updated_at = 1221030089;
        $order->purchase_order_id = '271373394756,151124791562,40374195258071073335,2302985152';
        $order->purchase_transaction_id = '2c206ada1cc7afc5248233b13bafb157';
        $order->purchase_amount = 3;
        $order->purchase_account_id = '96108708';
        $order->purchase_account_email = 'thao17@yahoo.com';
        $order->purchase_card = '2405298681595614';
        $order->purchase_amount_buck = 8291;
        $order->purchase_amount_refund = 46;
        $order->purchase_refund_transaction_id = null;
        $order->total_quantity = 6;
        $order->total_purchase_quantity = 6;
        $order->remove = 0;
        */
        $order->save(false);
        $order->link('products', $product);

        return $order;
    }

    public function actionCreate()
    {
        if (isset($this->post) == null) {  Yii::$app->api->sendFailedResponse("Invalid Record requested"); }

        $this->CartData($this->post);
        $items = $this->cart->getItems();

        $orders = [];
        $errors = [];
        foreach ($items as $key => $simpleItem) {
            /** @var  $simpleItem \common\components\cart\item\SimpleItem */
            $item = $simpleItem->item;

            $itemType = 'ebay';
            // Seller
            $seller = SellerData($item,$key);

            // Category
            $category = $this->CategoryData($item,$key,$itemType);


            // Product
            $product =  $this->ProductData($item,$category);

            // Order
            $order = $this->OrderData($itemType ,$seller , $product );

            $orderUpdateFeeAttribute = [];
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

                // Todo with OrderFee
                $_productFee = $this->ProductsFeeData($key,$amount,$local,$product,$order , $orderAttribute);

            }
            $orderUpdateFeeAttribute['total_fee_amount_local'] = $product->getAdditionalFees()->getTotalAdditionFees()[1];
            $order->updateAttributes($orderUpdateFeeAttribute);
            $orders[] = $order->id;
        }
        var_dump($orders);
        die;
        return true;

    }

    protected function ProductsFeeData($key,$amount,$local,$product,$order , $orderAttribute)
    {
        $_productFee = new ProductFee();
        $_productFee->type = $key;
        $_productFee->name = $product->getAdditionalFees()->getStoreAdditionalFeeByKey($key)->label;
        $_productFee->order_id = $order->id;
        $_productFee->product_id = $product->id;
        $_productFee->amount = $amount;
        $_productFee->local_amount = $local;
        $_productFee->currency = $product->getAdditionalFees()->getStoreAdditionalFeeByKey($key)->currency;
        if ($_productFee->save() && $order->hasAttribute($orderAttribute)) {
            $orderUpdateFeeAttribute[$orderAttribute] = $local;
        }
        return $_productFee;
    }
}
