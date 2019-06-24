<?php


namespace frontend\widgets\item;


use yii\base\Widget;

class RelateProduct extends Widget
{
    public $product;
    public $portal;
    public function run()
    {
        return $this->render('item/relate_item',[
            'product' => $this->product,
            'portal' => $this->portal,
        ]);
    }
}