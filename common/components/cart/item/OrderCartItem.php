<?php


namespace common\components\cart\item;

use common\components\cart\CartHelper;
use Yii;
use yii\base\BaseObject;
use common\products\forms\ProductDetailFrom;
use yii\helpers\ArrayHelper;

class OrderCartItem extends BaseObject
{

    public $id;
    public $source;
    public $seller;
    public $quantity = 1;
    public $image;
    public $sku = null;

    public function createOrderFormKey($key)
    {
        $tempKey = $key;
        $products = ArrayHelper::remove($tempKey, 'products', []);
        $orders = [];
        foreach ($products as $index => $param) {
            $param = ArrayHelper::merge($param, $tempKey);
            $new = new self($param);
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
        return [true, $order];
    }

    public function filterProduct()
    {
        $params = [
            'type' => $this->source,
            'id' => $this->id,
            'seller' => $this->seller,
            'quantity' => $this->quantity,
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

    public function mergeProduct($parent)
    {

    }
}