<?php
namespace api\controllers;

use common\components\Product;
use common\components\StoreManager;
use common\lib\AmazonProductGate;
use common\lib\AmazonSearchProductGate;
use common\lib\EbayProductGate;
use common\lib\EbaySearchForm;
use common\lib\EbaySearchProductGate;
use common\models\amazon\AmazonSearchForm;
use common\models\Order;
use common\models\Seller;
use common\components\cart\CartManager;
use common\models\db\Category;

use Yii;
use api\controllers\RestController as REST;
use api\behaviours\Verbcheck;
use api\behaviours\Apiauth;
use yii\filters\AccessControl;


use yii\db\Connection;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

class ServiceEAController extends BaseApiController
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

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return $behaviors + [
                'apiauth' => [
                    'class' => Apiauth::className(),
                    'exclude' => [],
                    'callback' => []
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['index'],
                    'rules' => [
                        [
                            'actions' => [],
                            'allow' => true,
                            'roles' => ['?'],
                        ],
                        [
                            'actions' => [
                                'get-amazon'
                            ],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => [],
                            'allow' => true,
                            'roles' => ['*'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => Verbcheck::className(),
                    'actions' => [
                        'get-amazon' => ['GET', 'POST'],
                        'get-amazon-detail' => ['POST'],
                        'ebay-detail' => ['GET'],
                        'create-simple-order' => ['GET'],
                    ],
                ],

            ];
    }

    public function actionGetAmazon()
    {
        $amazonSearch = new AmazonSearchForm();
        $amazonSearch->store = \Yii::$app->request->post('domain', Product::STORE_US);
        $amazonSearch->keyword = \Yii::$app->request->post('keyword');
        $amazonSearch->page = \Yii::$app->request->post('page');
        $amazonSearch->max_price = \Yii::$app->request->post('max_price');
        $amazonSearch->min_price = \Yii::$app->request->post('min_price');
        $amazonSearch->asin_id = \Yii::$app->request->post('sku');
        $amazonSearch->item_name = \Yii::$app->request->post('item_name');
        $amazonSearch->node_ids = \Yii::$app->request->post('category_ids');
        $amazonSearch->parent_asin_id = \Yii::$app->request->post('parent_sku');
        $amazonSearch->load_sub_url = \Yii::$app->request->post('load_sub_url');
        $amazonSearch->is_first_load = \Yii::$app->request->post('is_first_load');
        $amazonSearch->rh = \Yii::$app->request->post('rh');
        $amazonSearch->sort = \Yii::$app->request->post('sort');
        $rs = AmazonSearchProductGate::parse($amazonSearch);
        Yii::$app->api->sendSuccessResponse($rs);
    }

    public function actionGetamazonDetail()
    {
        $amazonSearch = new AmazonSearchForm();
        $amazonSearch->store = \Yii::$app->request->post('domain', Product::STORE_US);
        $amazonSearch->asin_id = \Yii::$app->request->post('sku');
        $rs = AmazonProductGate::parse($amazonSearch);
        Yii::$app->api->sendSuccessResponse($rs);
    }

    public function actionGetEbay()
    {
        $form = new EbaySearchForm();
        $form->keywords = \Yii::$app->request->post("keyword");
        $form->page = \Yii::$app->request->post("page", 1);
        $form->categories = \Yii::$app->request->post("category_ids") ? explode(',', \Yii::$app->request->post("category_ids")) : null;
        $form->aspectFilters = [];
        $form->itemFilters = [];
        $form->min_price = \Yii::$app->request->post("min_price");
        $form->max_price = \Yii::$app->request->post("max_price");
        $form->order = \Yii::$app->request->post("sort");
        $form->sellers = \Yii::$app->request->post("sellers");
        $form->type = \Yii::$app->request->post("type");
        $rs = EbaySearchProductGate::parse($form);
        Yii::$app->api->sendSuccessResponse($rs);
    }

    public function actionEbayDetail()
    {
        $sku = \Yii::$app->request->get('sku');
        if ($sku) {
            $store = new StoreManager();
            $store->setStore(1);
            $product = EbayProductGate::parseObject($sku, $store);
            Yii::$app->api->sendSuccessResponse($product);
        }
    }

    public function actionCreateOrderProduct()
    {
        /*** Document Compare*****
         * IPHONE
         * https://weshop.com.vn/ebay/item/apple-iphone-4s-mobile-phone-8gb-16gb-32gb-sim-free-factory-unlocked-smartphone-312226695751.html?sid=IPhone-4S-White-16GB
        https://weshop.com.vn/api/cmsproduct/calcfee?id=312226695751&qty=1&store=vn&portal=ebay
        https://ebay-api-wshopx-v3.weshop.com.vn/v3/product?id=312226695751
         **/

        $this->cart->removeItems();
        $this->cart->addItem('IF_739F9D0E', 'cleats_blowout_sports', 1, 'ebay', 'https://i.ebayimg.com/00/s/MTYwMFgxMDY2/z/cAQAAOSwMn5bzly6/$_12.JPG?set_id=880000500F', '252888606889');
        $items = $this->cart->getItems();

        $orders = [];
        $errors = [];
        foreach ($items as $key => $simpleItem) {
            /** @var  $simpleItem \common\components\cart\item\SimpleItem */
            $item = $simpleItem->item ;
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
            list($product->price_amount_origin, $product->total_price_amount_local) = $product->getAdditionalFees()->getTotalAdditionFees();
            $product->quantity_customer = $item['quantity'];

            $order = new Order();
            $order->type_order = 'SHOP';
            $order->store_id= 1;
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
            $orders[] = $order->id;
        }
        var_dump($orders);die;
        return true;
    }
}
