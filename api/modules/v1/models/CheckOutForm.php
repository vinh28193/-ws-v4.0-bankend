<?php


namespace api\modules\v1\models;

use common\products\BaseProduct;
use common\products\Provider;
use Yii;
use yii\base\Model;
use common\components\cart\CartManager;
use common\models\db\Category;
use common\models\Order;
use common\models\Product;
use common\models\ProductFee;
use common\models\Seller;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

class CheckOutForm extends Model
{

    /**
     * list cart id form checkout,
     * @var string
     */
    public $cartIds;

    /**
     * @var integer payment provider
     */
    public $paymentProvider;
    /**
     * @var integer payment method
     */
    public $paymentMethod;
    /**
     * @var integer payment bank code
     */
    public $bankCode;
    /**
     * @var string coupon added on checkout
     */
    public $couponCode;

    /**
     * @var string|CartManager
     */
    protected $cartManager = 'cart';

    /**
     * @inheritDoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->cartManager = Instance::ensure($this->cartManager, CartManager::className());

    }

    /**
     * this's for validate
     * @inheritDoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'cartIds', 'paymentProvider', 'paymentMethod', 'bankCode', 'couponCode'
        ]);
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['paymentProvider', 'paymentMethod'], 'required'],
            [['paymentProvider', 'paymentMethod'], 'integer'],
            [['bankCode', 'couponCode'], 'string']
        ]);
    }

    public function formName()
    {
        return '';
    }

    public function getFirstErrors()
    {
        $firstErrors = parent::getFirstErrors();
        return reset($firstErrors);
    }

    /**
     * action too much exception
     * Todo Db Transaction
     * use protected::getDb to do.
     * @return array|bool
     */
    public function checkout()
    {
        if (!$this->validate()) {
            return false;
        }
        // step 1: get all item by params
        // todo get form cartIds
        $items = $this->cartManager->getItems();
        // step 2: sort item
        $items = ArrayHelper::index($items, 'key', function ($item) {
            $request = $item['request'];
            return $request['type'] . ':' . $request['seller'];
        });
        $results = [];
        foreach ($items as $key => $arrays) {
            list($type, $sellerId) = explode(':', $key);
            /** @var  $provider null |Provider */
            $provider = null;
            $providers = $arrays;
            $providers = reset($providers)['response']->providers;
            foreach ($providers as $p) {
                if (
                    (strtoupper($type) === BaseProduct::TYPE_EBAY && $p->name === $sellerId) ||
                    (strtoupper($type) !== BaseProduct::TYPE_EBAY && $p->prov_id === $sellerId)
                ) {
                    $provider = $p;
                    break;
                }
            }
            if (($seller = Seller::findOne(['AND', ['seller_name' => $provider->name], ['portal' => $type]])) === null) {
                $seller = new Seller();
                $seller->seller_name = $sellerId;
                $seller->portal = $type;
                $seller->seller_store_rate = $provider->rating_score;
                $seller->portal = $type;
                $seller->seller_link_store = $provider->website;
                $seller->save(false);
            }
            // step 3: create order
            $order = new Order();
            $order->setScenario(Order::SCENARIO_DEFAULT);
            $order->type_order = Order::TYPE_SHOP;
            $order->store_id = Yii::$app->storeManager->getId();
            $order->customer_id = Yii::$app->getUser()->getId();
            $order->portal = $type;
            $order->receiver_email = 'vinhvv@peacesoft.net';
            $order->receiver_name = 'vinh dev';
            $order->receiver_phone = '0987654321';
            $order->receiver_address = '18 tam trinh';
            $order->receiver_country_id = 1;
            $order->receiver_province_id = 1;
            $order->receiver_district_id = 1;
            $order->receiver_post_code = '10000';
            $order->receiver_address_id = 1;
            $order->payment_type = 'online_payment';
            $order->seller_id = $seller->id;
            $order->seller_name = $seller->seller_name;
            $order->seller_store = $seller->seller_link_store;
            $order->save(false);
            $products = [];
            $productFees = [];
            $updateOrderAttributes = [];
            // step 4: create product
            foreach ($arrays as $id => $array) {
                /** @var $item BaseProduct */
                if (!isset($array['response']) || !($item = $array['response']) instanceof BaseProduct) {
                    continue;
                }

                $request = isset($array['request']) ? $array['request'] : [];
                $product = new Product();
                $product->order_id = $order->id;
                $product->portal = $item->type;
                $product->sku = $item->item_sku;
                $product->parent_sku = $item->item_id;
                $product->link_img = isset($request['image']) ? $request['image'] : $item->current_image;
//                $product->link_origin = $item->item_origin_url;
                $product->link_origin = 'test'; // Todo BaseProduct get link origin
                // step 4: create category for each item
                if (($category = Category::findOne(['AND', ['alias' => $item->category_id], ['site' => $type]])) === null) {
                    $category = new Category();
                    $category->alias = $item->category_id;
                    $category->site = $type;
                    $category->origin_name = ArrayHelper::getValue($item, 'category_name', 'Unknown');
                    $category->save(false);
                }
                $product->category_id = $category->id;
//                $product->custom_category_id = $category->id;

                $additionalFees = $item->getAdditionalFees();

                // 'đơn giá gốc ngoại tệ'
                $product->price_amount_origin = $item->getTotalOriginPrice();
                $product->total_fee_product_local = $additionalFees->getTotalAdditionFees(null, ['product_price_origin'])[1];         // Tổng Phí theo sản phẩm
                $product->price_amount_local = $additionalFees->getTotalAdditionFees('product_price_origin')[1];  // đơn giá local = giá gốc ngoại tệ * tỉ giá Local
                $product->quantity_customer = $item->quantity;
                $product->quantity_purchase = null;
                /** Todo */
                $product->quantity_inspect = null;
                /** Todo */
                $product->variations = null;
                /** Todo */
                $product->variation_id = null;
                /** Todo */
                $product->note_by_customer = 'Note By Customer';
                $product->total_weight_temporary = 0;     //"cân nặng  trong lượng tạm tính"
                $product->remove = 0;
                $product->product_name = $item->item_name;
                /** Todo */
                $product->product_link = 'https://weshop.com.vn/link/sanpham.html';
                /** Todo Add on Purchase */
                $product->version = '4.0';
                $product->condition = null;
                /** Todo */

                $product->seller_id = $seller->id;

                $product->save(false);

                // step 5: create product fee for each item
                foreach ($additionalFees->keys() as $key) {
                    list($amount, $local) = $item->getAdditionalFees()->getTotalAdditionFees($key);
                    $orderAttribute = "total_{$key}_local";
                    if ($key === 'product_price_origin') {
                        $orderAttribute = 'total_origin_fee_local';
                    } elseif ($key === 'tax_fee_origin') {
                        $orderAttribute = 'total_origin_tax_fee_local';
                    } elseif ($key === 'delivery_fee_local') {
                        $orderAttribute = 'total_delivery_fee_local';
                    }

                    $productFee = new ProductFee();
                    $productFee->type = $key;
                    $productFee->name = $item->getAdditionalFees()->getStoreAdditionalFeeByKey($key)->label;
                    $productFee->order_id = $order->id;
                    $productFee->product_id = $product->id;
                    $productFee->amount = $amount;
                    $productFee->local_amount = $local;
                    $productFee->currency = $item->getAdditionalFees()->getStoreAdditionalFeeByKey($key)->currency;
                    if ($productFee->save() && $order->hasAttribute($orderAttribute)) {
                        $value = isset($updateOrderAttributes[$orderAttribute]) ? $updateOrderAttributes[$orderAttribute] : 0;
                        $value += $local;
                        $updateOrderAttributes[$orderAttribute] = $value;
                    }
                    $productFees[$product->id][$key] = $productFee;
                }
                $updateOrderAttributes['total_fee_amount_local'] = $additionalFees->getTotalAdditionFees()[1];
                $products[] = $product;
            }
            if ($provider !== null) {
                $updateOrderAttributes['seller_store'] = $seller->seller_link_store;
            }
            $order->updateAttributes($updateOrderAttributes);
            $results[$order->id] = [
                'seller' => $seller,
                'order' => $order,
                'products' => $products,
                'productFees' => $productFees
            ];
        }
        return $results;
    }

    /**
     * @return \yii\db\Connection
     */
    protected function getDb()
    {
        return Order::getDb();
    }
}