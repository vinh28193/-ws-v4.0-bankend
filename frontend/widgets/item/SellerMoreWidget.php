<?php


namespace frontend\widgets\item;


use common\components\StoreManager;
use common\products\BaseProduct;
use common\products\Provider;
use Yii;
use yii\base\Widget;

class SellerMoreWidget extends Widget
{

    /**
     * @var BaseProduct
     */
    public $item;
    /**
     * @var Provider
     */
    public $provider;

    /**
     * @var StoreManager
     */
    public $storeManager;

    /**
     * @return StoreManager
     */
    public function getStoreManager()
    {
        if (!is_object($this->storeManager)) {
            $this->storeManager = Yii::$app->storeManager;
        }
        return $this->storeManager;
    }
    public function run()
    {
        return $this->render('item/_item_seller',[
            'item' => $this->item,
            'provider' => $this->provider,
            'storeManager' => $this->getStoreManager()
        ]);
    }
}