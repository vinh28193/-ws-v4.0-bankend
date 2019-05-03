<?php


namespace frontend\widgets\cms;


class HotDealWidget extends WeshopBlockWidget
{

    public function run()
    {
        parent::run();
        $categories = isset($this->block['categories']) ? $this->block['categories'] : [];
        $products = $this->block['products'] ? $this->block['products'] : [];
        echo $this->render('hot_deal',[
            'block' => $this->block['block'],
            'categories' => $categories,
            'products' => $products
        ]);
    }
}