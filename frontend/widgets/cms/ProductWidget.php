<?php


namespace frontend\widgets\cms;

use common\components\StoreManager;
use common\products\BaseProduct;
use Yii;

class ProductWidget extends WeshopBlockWidget
{

    const TYPE_RIGHT = 'product-right';
    const TYPE_LEFT = 'product-left';
    const TYPE_CENTER = 'product-center';
    const TYPE_MOBILE = 'product-mobile';
    const TYPE_ALIAS = 'product-alias';
    const TYPE_COL = 'product-col';

    /**
     * @var array
     */
    public $product;
    /**
     * @var array
     */
    public $classCustom = 'col-md-3 col-sm-6';

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

        $product_base = new BaseProduct([
            'sell_price' => $this->product['calculated_sell_price'],
            'start_price' => $this->product['calculated_start_price'],
            'isInitialized' => true,
        ]);
        $price = $this->getStoreManager()->roundMoney($product_base->getLocalizeTotalPrice());
        $oldPrice = '';
        $saleTag = 0;
        if (($oldPrice = $this->product['calculated_start_price'])) {
            $oldPrice = $this->getStoreManager()->roundMoney($product_base->getLocalizeTotalStartPrice());
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
            'classCustom' => $this->classCustom,
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