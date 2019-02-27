<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-27
 * Time: 14:17
 */

namespace common\components\cart\item;


class SimpleItem extends BaseCartItem
{
    public $sku;
    public $parentSku;
    public $source;
    public $seller;
    public $quantity = 1;
    public $image;

    /**
     * @return $this|SimpleItem
     */
    public function process()
    {
        return new self([
            'sku' => $this->sku,
            'parentSku' => $this->parentSku,
            'source' => $this->source,
            'seller' => $this->seller,
            'quantity' => $this->quantity,
            'image' => $this->image
        ]);
    }

}