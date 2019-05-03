<?php


namespace frontend\widgets\cms;


class ProductMobileWidget extends WeshopBlockWidget
{

    public $product;

    public function run()
    {
        parent::run();
        echo $this->render('product-mobile',['product' => $this->product]);
    }
}