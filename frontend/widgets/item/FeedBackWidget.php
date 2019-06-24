<?php


namespace frontend\widgets\item;


use common\components\StoreManager;
use Yii;
use yii\jui\Widget;

class FeedBackWidget extends Widget
{
    public $portal;
    public $array;
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
        return $this->render('item/feed_back',[
            'array' => $this->array,
            'portal' => $this->portal,
            'storeManager' => $this->getStoreManager()
        ]);
    }
}