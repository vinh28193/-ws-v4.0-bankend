<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 21/11/2018
 * Time: 09:42 AM
 */

namespace landing\widgets\ListProduct;


use landing\LandingWidget;

class ProductWidget extends LandingWidget
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