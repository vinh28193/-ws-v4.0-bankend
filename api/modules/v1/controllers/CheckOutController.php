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
use common\models\OrderFee;
use common\models\Product;
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

    public function actionIndex()
    {

    }

    public function actionCreate()
    {
        $this->cart->addItem('IF_739F9D0E', 'cleats_blowout_sports', 1, 'ebay', 'https://i.ebayimg.com/00/s/MTYwMFgxMDY2/z/cAQAAOSwMn5bzly6/$_12.JPG?set_id=880000500F', '252888606889');
        $this->cart->addItem('IF_6C960C53', 'cleats_blowout_sports', 1, 'ebay', 'https://i.ebayimg.com/00/s/MTYwMFgxMDY2/z/nrsAAOSw7Spbzlyw/$_12.JPG?set_id=880000500F', '252888606889');
        $this->cart->addItem('261671375738', 'luv4everbeauty', 1, 'ebay', 'https://i.ebayimg.com/00/s/NTk3WDU5Nw==/z/FjMAAOSwscNbK5~0/$_57.JPG');
        // Toto CheckOutForm to validate data form all
        $items = $this->cart->getItems();

        var_dump($items);die("carddddd");
        $orders = [];
        $errors = [];
        foreach ($items as $key => $simpleItem) {
            /** @var  $simpleItem \common\components\cart\item\SimpleItem */
            $item = $simpleItem->item;

            $itemType = 'ebay';
            // Seller
            if (($providers = ArrayHelper::getValue($item, 'providers')) === null || ($providers !== null && !isset($providers['name']))) {
                $errors[$key][] = "can not create form null seller";
                continue;
            }
            if (($seller = Seller::findOne(['name' => $providers['name']])) === null) {
                $seller = new Seller();
                $seller->name = $providers['name'];
                $seller->link_store = $providers['website'];
                $seller->rate = $providers['rating_score'];
                $seller->save(false);
            }
            // Category
            if (($categoryId = ArrayHelper::getValue($item, 'category_id')) === null) {
                $errors[$key][] =  "can not create form null category";
                continue;
            }

            if (($category = Category::findOne(['AND', ['alias' => $categoryId], ['site' => $itemType]])) === null) {
                $category = new Category();
                $category->alias = $categoryId;
                $category->site = $itemType;
                $category->origin_name = ArrayHelper::getValue($item, 'category_name', 'Unknown');
                $category->save();
            }

            $product = new Product;
            $product->sku = $item['item_sku'];
            $product->parent_sku = $item['item_id'];
            $product->link_origin = $item['item_origin_url'];
            $product->getAdditionalFees()->mset($item['additionalFees']);
            $product->category_id = $category->id;
            list($product->price_amount, $product->total_price_amount_local) = $product->getAdditionalFees()->getTotalAdditionFees();
            $product->quantity = $item['quantity'];

            $order = new Order();
            $order->type_order = 'SHOP';
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
            $order->save(false);
            $order->link('products',$product);
            $productFees = $product->getAdditionalFees(true)->toArray();
            $fees = [];
            foreach ($productFees as $name => $fee){
                $fees[] = $fee['id'];
            }
            OrderFee::updateAll(['order_id' => $order->id],['id' => $fees]);
            $orders[] = $order->id;
        }
        var_dump($orders);die;
        return true;

    }
}