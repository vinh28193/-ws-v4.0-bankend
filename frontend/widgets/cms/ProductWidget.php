<?php


namespace frontend\widgets\cms;

use common\components\StoreManager;
use Yii;

class ProductWidget extends WeshopBlockWidget
{

    const TYPE_RIGHT = 'product-right';
    const TYPE_LEFT = 'product-left';
    const TYPE_CENTER = 'product-center';
    const TYPE_MOBILE = 'product-mobile';
    const TYPE_ALIAS = 'product-alias';

    /**
     * @var array
     */
    public $product;

    /**
     * @var string
     */
    public $type = self::TYPE_CENTER;

    public $iphoneOld = false;

    /**
     * @return string|void
     */
    public function run()
    {
        parent::run();
        $price = $this->getStoreManager()->roundMoney($this->product['calculated_sell_price'] * $this->getStoreManager()->getExchangeRate());
        $oldPrice = '';
        $saleTag = 0;
        if (($oldPrice = $this->product['calculated_start_price']) !== null) {
            $oldPrice = $this->getStoreManager()->roundMoney($oldPrice * $this->getStoreManager()->getExchangeRate());
            $saleTag = round((($oldPrice - $price) / $oldPrice) * 100);
            $oldPrice = $this->getStoreManager()->showMoney($oldPrice);
        }
        $price = $this->getStoreManager()->showMoney($price);
        echo $this->render("product/{$this->type}", [
            'url' => $this->product['item_url'],
            'name' => $this->product['name'],
            'image' => $this->product['image'],
            'sellPrice' => $price,
            'oldPrice' => $oldPrice,
            'saleTag' => $saleTag
        ]);
    }

    /**
     * @return StoreManager
     */

    public function getStoreManager()
    {
        return Yii::$app->storeManager;
    }
}