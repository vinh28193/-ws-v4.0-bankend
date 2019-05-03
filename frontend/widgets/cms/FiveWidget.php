<?php


namespace frontend\widgets\cms;


class FiveWidget extends WeshopBlockWidget
{

    public function run()
    {
        parent::run();
        $products = $this->block['products'] ? $this->block['products'] : [];
        return $this->render('five', [
            'block' => $this->block['block'],
            'products' => $products,
        ]);
    }
}