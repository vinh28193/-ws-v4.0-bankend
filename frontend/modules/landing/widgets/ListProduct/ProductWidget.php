<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 21/11/2018
 * Time: 09:42 AM
 */

namespace weshop\modules\landing\views\widgets\ListProduct;


use weshop\views\weshop\widgets\BaseWidget;

class ProductWidget extends BaseWidget
{
    public $product;
    public $oldPhone = false;
    public function run()
    {
        return $this->render("ProductView", [
            'product'=>$this->product,
            'iphoneOld'=>$this->oldPhone,
        ]);
    }
}