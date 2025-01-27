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
    public $link_payment;
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
            $key_arr = explode('-',$tempKey['sellerId']);
            $key_id = '';
            foreach ($key_arr as $k => $value){
                if(($k + 1) < (count($key_arr) - 2))
                    $key_id = $k == 0 ? $value : $key_id.'-'.$value;
            }
            $param = ArrayHelper::merge($param, [
                'source' => $tempKey['source'],
                'sellerId' => $key_id ? $key_id : $tempKey['sellerId']
            ]);
            $new = new self($this->cartManager, $param);
            list($ok, $newOrder) = $new->filterProduct($tempKey['sellerId']);
            if (!$ok) {
                return [false, Yii::t('common', '"item {id} is invalid please remove this form cart list', [
                    'id' => $param['id']
                ])];
            }
            $orders[] = $newOrder;
        }

        if (count($orders) === 0) {
            return [false, Yii::t('common', 'Can not add an invalid item into cart')];
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
            $supportAssign = $this->cartManager->getSupportAssign();
            $key['supportAssign'] = $supportAssign;
            $key['supportId'] = $supportAssign['id'];
            $order['sale_support_id'] = $supportAssign['id'];
            $order['support_email'] = $supportAssign['email'];
            $order['support_name'] = $supportAssign['username'];
            $order['saleSupport'] = $supportAssign;
        } else {
            $order['ordercode'] = $tempKey['orderCode'];
            $order['current_status'] = $tempKey['current_status'];
            if (!empty($tempKey['times'])) {
                foreach ($tempKey['times'] as $k => $time) {
                    $order[$k] = $time;
                }
            }
            if (isset($key['supportAssign']) && !empty($key['supportAssign'])) {
                $supportAssign = $key['supportAssign'];
                $order['sale_support_id'] = $supportAssign['id'];
                $order['support_email'] = $supportAssign['email'];
                $order['support_name'] = $supportAssign['username'];
                $order['saleSupport'] = $supportAssign;
            }
        }

        return [true, $order];
    }

    public function updateItemBuyKey($key)
    {
        // Todo update gía trị của key vào trường value


    }

    public function filterProduct($seller_id = '')
    {
        $params = [
            'type' => $this->source,
            'id' => $this->id,
            'seller' => $seller_id,
            'quantity' => (int)$this->quantity,
            'sku' => $this->sku,
        ];
        $form = new ProductDetailFrom($params);
        /** @var $product false | \common\products\BaseProduct BaseProduct */
        if (($product = $form->detail()) === false) {
            Yii::info($form->getFirstErrors(), "add_to_cart");
            return [false, $form->getFirstErrors()];
        }
        if ($seller_id){
            $product->updateBySeller($seller_id);
        }
        $product->current_image = $this->image;
        $params['image'] = $this->image;
        $order = CartHelper::createItem($product,$this->sellerId,$this->image);
        return [true, $order];
    }

    public static function defaultKey()
    {



    }
}