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
        return [$params, $product];
    }

    public function mergeProduct($parent)
    {

    }
}