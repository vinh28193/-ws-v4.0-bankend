<?php


namespace frontend\widgets\cms;


class ProductCenterWidget extends WeshopBlockWidget
{

    public $product;

    public function run()
    {
        parent::run();
        echo $this->render('product-center',['product' => $this->product]);
    }
}