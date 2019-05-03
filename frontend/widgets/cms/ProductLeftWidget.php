<?php


namespace frontend\widgets\cms;


class ProductLeftWidget extends WeshopBlockWidget
{

    public $product;

    public function run()
    {
        parent::run();
        echo $this->render('product-left',['product' => $this->product]);
    }
}