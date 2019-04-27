<?php


namespace api\modules\v1\models;


use Yii;
use Exception;
use yii\base\Model;
use yii\db\Connection;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use common\helpers\WeshopHelper;
use common\models\db\Category;
use common\models\Order;
use common\models\Product;
use common\models\ProductFee;
use common\models\Seller;
use common\products\BaseProduct;
use common\products\Provider;
use common\components\StoreManager;
use common\components\cart\CartManager;

class CheckOutForm extends Model
{

    /**
     * list cart id form checkout
     * @var string|array
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
     * @var string|Connection
     */
    protected $db = 'db';
    /**
     * @var string|CartManager
     */
    protected $cartManager = 'cart';
    /**
     * @var string | StoreManager
     */
    protected $storeManager = 'storeManager';

    /**
     * @var \yii\web\User
     */
    protected $user;

    /**
     * @inheritDoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());
        $this->cartManager = Instance::ensure($this->cartManager, CartManager::className());
        $this->storeManager = Instance::ensure($this->storeManager, StoreManager::className());
        $this->user = Yii::$app->getUser();
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
            [['cartIds', 'paymentProvider', 'paymentMethod'], 'required'],
            [['paymentProvider', 'paymentMethod'], 'integer'],
            [['bankCode', 'couponCode'], 'string'],
            ['cartIds', 'filter', 'filter' => function ($value) {
                if (is_string($value) && stripos($value, ',')) {
                    $value = explode(',', $value);
                }
                if (!is_array($value)) {
                    $value = [$value];
                }
                return $value;
            }]
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
        $results = [];
        $transaction = $this->db->beginTransaction();
        try {
            // step 1: get all item by params
            // todo get form cartIds
            $items = [];
            foreach ($this->cartIds as $id) {
                if (($item = $this->cartManager->getItem($id)) === false) {
                    continue;
                }
                $items[$id] = $item;
            }
            if (empty($items)) {
                $transaction->rollBack();
                $this->addError('cartIds', 'Can not get cart item from ids :' . implode(', ', $this->cartIds));
                return false;
            }
            // step 2: sort item

            $items = ArrayHelper::index($items, 'key', function ($item) {
                $request = $item['request'];
                return $request['type'] . ':' . $request['seller'];
            });
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
                $order->new = Yii::$app->getFormatter()->asTimestamp('now');
                $order->type_order = Order::TYPE_SHOP;
                $order->customer_type = 'Retail';
                $order->current_status = Order::STATUS_NEW;
                $order->store_id = $this->storeManager->getId();
                $order->exchange_rate_fee = $this->storeManager->getExchangeRate();
                $order->customer_id = $this->user->getId();
                $order->portal = $type;
                $order->receiver_email = 'vinhvv@peacesoft.net';
                $order->receiver_name = 'vinh dev';
                $order->receiver_phone = '0987654321';
                $order->receiver_address = 'Tang 16 VTC - 18 Tam Trinh';
                $order->receiver_country_id = 1;
                $order->receiver_province_id = 25;
                $order->receiver_district_id = 287;
                $order->receiver_country_name = "Việt Nam";
                $order->receiver_province_name = "Hà Nội";
                $order->receiver_district_name = "Quận Hoàng Mai";
                $order->receiver_post_code = '10000';
                $order->receiver_address_id = 1;
                $order->payment_type = 'online_payment';
                $order->seller_id = $seller->id;
                $order->seller_name = $seller->seller_name;
                $order->seller_store = $seller->seller_link_store;
                $order->total_paid_amount_local = 0;
                $order->save(false);
                $order->updateAttributes([
                    'ordercode' => WeshopHelper::generateTag($order->id, 'WSVN', 16),
                ]);
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
//                  $product->custom_category_id = $category->id;

                    $additionalFees = $item->getAdditionalFees();

                    // 'đơn giá gốc ngoại tệ bao gồm các phí tại nơi xuất xứ (tiền us, us tax, us ship)
                    $product->price_amount_origin = $item->getTotalOriginPrice();
                    // Tổng tiền các phí, trừ tiền gốc sản phẩm (chỉ có các phí)
                    $product->total_fee_product_local = $additionalFees->getTotalAdditionFees(null, ['product_price_origin'])[1];         // Tổng Phí theo sản phẩm
                    // Tổng tiền local gốc sản phẩm (chỉ có tiền gốc của sản phẩm)
                    $product->price_amount_local = $additionalFees->getTotalAdditionFees('product_price_origin')[1];  // đơn giá local = giá gốc ngoại tệ * tỉ giá Local
                    // Tổng tiền local tất tần tận
                    $product->total_price_amount_local = $additionalFees->getTotalAdditionFees()[1];
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
                    $product->total_weight_temporary = $item->shipping_weight;     //"cân nặng  trong lượng tạm tính"
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
                        $orderAttribute = '';
                        if ($key === 'product_price_origin') {
                            // Tổng giá gốc của các sản phẩm tại nơi xuất xứ
                            $orderAttribute = 'total_origin_fee_local';
                        }
                        if ($key === 'tax_fee_origin') {
                            // Tổng phí tax của các sản phẩm tại nơi xuất xứ
                            $orderAttribute = 'total_origin_tax_fee_local';
                        }
                        if ($key === 'origin_shipping_fee') {
                            // Tổng phí ship của các sản phẩm tại nơi xuất xứ
                            $orderAttribute = 'total_origin_shipping_fee_local';
                        }
                        if ($key === 'weshop_fee') {
                            // Tổng phí phí dịch vụ wéhop fee của các sản phẩm
                            $orderAttribute = 'total_weshop_fee_local';
                        }
                        if ($key === 'intl_shipping_fee') {
                            // Tổng phí phí vận chuyển quốc tế của các sản phẩm
                            $orderAttribute = 'total_intl_shipping_fee_local';
                        }
                        if ($key === 'custom_fee') {
                            // Tổng phí phụ thu của các sản phẩm
                            $orderAttribute = 'total_custom_fee_amount_local';
                        }
                        if ($key === 'packing_fee') {
                            // Tổng phí đóng gói của các sản phẩm
                            $orderAttribute = 'total_packing_fee_local';
                        }
                        if ($key === 'inspection_fee') {
                            // Tổng đóng hàng của các sản phẩm
                            $orderAttribute = 'total_inspection_fee_local';
                        }
                        if ($key === 'insurance_fee') {
                            // Tổng bảo hiểm của các sản phẩm
                            $orderAttribute = 'total_insurance_fee_local';
                        }
                        if ($key === 'vat_fee') {
                            // Tổng vat của các sản phẩm
                            $orderAttribute = 'total_vat_amount_local';
                        }
                        if ($key === 'delivery_fee_local') {
                            // Tổng vận chuyển tại local của các sản phẩm
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
                        if ($productFee->save() && $orderAttribute !== '') {
                            if ($orderAttribute === 'total_origin_fee_local') {
                                // Tổng giá gốc của các sản phẩm tại nơi xuất xứ (giá tại nơi xuất xứ)
                                $oldAmount = isset($updateOrderAttributes['total_price_amount_origin']) ? $updateOrderAttributes['total_price_amount_origin'] : 0;
                                $oldAmount += $amount;
                                $updateOrderAttributes['total_price_amount_origin'] = $oldAmount;
                            }
                            $value = isset($updateOrderAttributes[$orderAttribute]) ? $updateOrderAttributes[$orderAttribute] : 0;
                            $value += $local;
                            $updateOrderAttributes[$orderAttribute] = $value;
                        }
                        $productFees[$product->id][$key] = $productFee;
                    }

                    // Tổng các phí các sản phẩm (trừ giá gốc tại nơi xuất xứ)
                    $oldAmount = isset($updateOrderAttributes['total_fee_amount_local']) ? $updateOrderAttributes['total_fee_amount_local'] : 0;
                    $oldAmount += $additionalFees->getTotalAdditionFees(null, ['product_price_origin'])[1];
                    $updateOrderAttributes['total_fee_amount_local'] = $oldAmount;

                    // Tổng tiền (bao gồm tiền giá gốc của các sản phẩm và các loại phí)
                    $oldAmount = isset($updateOrderAttributes['total_amount_local']) ? $updateOrderAttributes['total_amount_local'] : 0;
                    $oldAmount += $additionalFees->getTotalAdditionFees()[1];
                    $updateOrderAttributes['total_amount_local'] = $oldAmount;
                    $updateOrderAttributes['total_final_amount_local'] = $oldAmount;

                    $products[] = $product;
                }

                $order->updateAttributes($updateOrderAttributes);
                $results[$order->ordercode] = [
                    'seller' => $seller,
                    'order' => $order,
                    'products' => $products,
                    'productFees' => $productFees
                ];
            }
            $transaction->commit();
        } catch (Exception $exception) {
            Yii::info($exception);
            $this->addError('cartIds', $exception->getMessage());
            $transaction->rollBack();
            return false;
        }

        return $results;
    }
}
