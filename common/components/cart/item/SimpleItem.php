<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-27
 * Time: 14:17
 */

namespace common\components\cart\item;

use common\products\BaseProduct;
use common\products\forms\ProductDetailFrom;

class SimpleItem extends BaseCartItem
{

    public $source;
    public $seller;
    public $quantity = 1;
    public $image;
    public $sku;
    public $parentSku = null;


    public function process()
    {
        $params = [
            'type' => $this->source,
            'sku' => $this->parentSku,
            'id' => $this->sku,
            'seller' => $this->seller,
            'quantity' => $this->quantity,
            'with_detail' => false,
        ];
        if ($this->parentSku !== null && $this->parentSku !== '') {
            $params['id'] = $this->parentSku;
            $params['sku'] = $this->sku;
        }
        $form = new ProductDetailFrom();
        $form->load($params, '');
        /** @var $product false | \common\products\BaseProduct BaseProduct */
        if (($product = $form->detail(false)) === false) {
            \Yii::info($form->getFirstErrors(), "add_to_cart");
            return [false, $form->getFirstErrors()];

        }
        $product->current_image = $this->image;
        return [true, ['request' => $params, 'response' => $product]];
    }

}