<?php


namespace frontend\widgets\cms;


class ProductRightWidget  extends WeshopBlockWidget
{

    public $product;

    public function run()
    {
        parent::run();
        echo $this->render('product-right',['product' => $this->product]);
    }
}