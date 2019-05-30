<?php


namespace common\components\cart\item;

use common\components\cart\CartHelper;
use common\components\cart\CartManager;
use common\components\StoreManager;
use common\helpers\WeshopHelper;
use common\models\User;
use Yii;
use yii\base\BaseObject;
use common\products\forms\ProductDetailFrom;
use yii\helpers\ArrayHelper;

class OrderCartItem extends BaseObject
{

    public $id;
    public $source;
    public $sellerId;
    public $quantity = 1;
    public $image;
    public $sku = null;

    /**
     * @var CartManager
     */
    public $cartManager;

    public function __construct(CartManager $cartManager, $config = [])
    {
        parent::__construct($config);
        $this->cartManager = $cartManager;
    }

    /**
     * @var StoreManager
     */
    private $_storeManager;

    /**
     * @return StoreManager
     */
    public function getStoreManager()
    {
        if (!is_object($this->_storeManager)) {
            $this->_storeManager = Yii::$app->storeManager;
        }
        return $this->_storeManager;
    }

    public function createOrderFormKey(&$key, $refresh = false)
    {
        $tempKey = $key;
        $products = ArrayHelper::getValue($tempKey, 'products', []);
        $orders = [];
        foreach ($products as $index => $param) {
            $param = ArrayHelper::merge($param, [
                'source' => $tempKey['source'],
                'sellerId' => $tempKey['sellerId']
            ]);
            $new = new self($this->cartManager, $param);
            list($ok, $newOrder) = $new->filterProduct();
            if (!$ok) {
                return [false, "item {$param['id']} is invalid please remove this form cart list"];
            }
            $orders[] = $newOrder;
        }

        if (count($orders) === 0) {
            return [false, 'Can not add an invalid item into cart'];
        }

        $order = array_shift($orders);

        while (!empty($orders)) {
            $order = CartHelper::mergeItem($order, array_shift($orders));
        }

        if (!$refresh) {
            if (empty($key['seller'])) {
                $key['seller'] = $order['seller'];
            }
            if ($order['ordercode'] === null) {
                $order['ordercode'] = $tempKey['orderCode'];
            }

//            if (($supporters = $this->cartManager->getStorage()->calculateSupported()) > 0) {
//                /** @var  $supporter null|User */
//                $supporter = User::find()->select(['id', 'mail'])->where(['id' => $supporters[0]['_id']])->one();
//                $key['supportAssign'] = [
//                    'id' => $supporter->id,
//                    'email' => $supporter->email
//                ];
//
//            }
        } else {
            $order['ordercode'] = $tempKey['orderCode'];
            $order['current_status'] = $tempKey['current_status'];
            $order['sale_support_id'] = $tempKey['supportId'];
            $order['saleSupport'] = $order['sale_support_id'] !== null ? User::find()->select(['id', 'email'])->where(['id' => $order['sale_support_id']])->asArray()->one() : null;
            if (!empty($tempKey['times'])) {
                foreach ($tempKey['times'] as $k => $time) {
                    $order[$k] = $time;
                }
            }
        }

        return [true, $order];
    }

    public function updateItemBuyKey($key)
    {
        // Todo update gía trị của key vào trường value

    }

    public function filterProduct()
    {
        $params = [
            'type' => $this->source,
            'id' => $this->id,
            'seller' => $this->sellerId,
            'quantity' => (int)$this->quantity,
            'sku' => $this->sku,
        ];
        $form = new ProductDetailFrom($params);
        /** @var $product false | \common\products\BaseProduct BaseProduct */
        if (($product = $form->detail()) === false) {
            Yii::info($form->getFirstErrors(), "add_to_cart");
            return [false, $form->getFirstErrors()];

        }
        $product->current_image = $this->image;
        $params['image'] = $this->image;
        $order = CartHelper::createItem($product);
        return [true, $order];
    }

    public static function defaultKey()
    {

        $store = Yii::$app->storeManager;
        return [
            'source' => '',
            'sellerId' => '',
            'seller' => [],
            'current_status' => 'NEW',
            'times' => [
                'new' => Yii::$app->getFormatter()->asTimestamp('now')
            ],
            'supportId' => '',
            'supportAssign' => [],
            'store_id' => $store->id,
            'currency' => $store->store->currency,
            'orderCode' => WeshopHelper::generateTag(Yii::$app->formatter->asTimestamp('now'), 'WSC'),
            'products' => []
        ];
    }
}