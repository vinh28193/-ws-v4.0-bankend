<?php


namespace frontend\widgets\cms;

use Yii;

class ProductWidget extends WeshopBlockWidget
{

    const TYPE_RIGHT = 'product-right';
    const TYPE_LEFT = 'product-left';
    const TYPE_CENTER = 'product-center';
    const TYPE_MOBILE = 'product-mobile';

    /**
     * @var array
     */
    public $product;

    /**
     * @var string
     */
    public $type = self::TYPE_CENTER;

    /**
     * @return string|void
     */
    public function run()
    {
        parent::run();
        echo $this->render("product/{$this->type}", [
            'url' => '#',
            'name' => $this->product['name'],
            'image' => $this->product['image'],
            'sellPrice' => $this->product['calculated_sell_price'] * $this->getExRate(),
            'oldPrice' => ($oldPrice = $this->product['calculated_start_price']) !== null ? $oldPrice : '',
            'saleTag' => 50,
        ]);
    }

    public function getExRate(){
        return 23000;
    }
}