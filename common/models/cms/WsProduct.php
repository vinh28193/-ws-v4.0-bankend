<?php

namespace common\models\cms;

use common\helpers\WeshopHelper;

/**
 * Class WsProduct
 * @package common\models\model_cms
 * @property integer $localStartPrice
 * @property integer $localSellPrice
 * @property integer $salePercent
 * @property string $type,
 * @property integer $categoryName
 */
class WsProduct extends \common\models\db_cms\WsProduct
{

    /**
     * @return \common\components\StoreManager;
     */
    private function getStoreManager()
    {
        return \Yii::$app->storeManager;
    }

    public $_local_start_price;

    public function setLocalStartPrice($local_start_price)
    {
        $this->_local_start_price = $local_start_price;
    }

    public function getLocalStartPrice()
    {
        if (!$this->_local_start_price) {
            $this->_local_start_price = WeshopHelper::roundNumber($this->calculated_start_price * $this->getStoreManager()->getExchangeRate());
        }

        return $this->_local_start_price;
    }

    public $_local_sell_price;

    public function setLocalSellPrice($local_sell_price)
    {
        
        $this->_local_sell_price = $local_sell_price;
    }

    public function getLocalSellPrice()
    {
        if (!$this->_local_sell_price) {
            $this->_local_sell_price = WeshopHelper::roundNumber($this->calculated_sell_price * $this->getStoreManager()->getExchangeRate());
        }
        return $this->_local_sell_price;
    }


    public $_sale_percent;

    public function setSalePercent($sale_percent)
    {
        $this->_sale_percent = $sale_percent;
    }

    public function getSalePercent()
    {
        if (!$this->_sale_percent) {
            $this->_sale_percent = 0;
            if ($this->start_price > $this->sell_price) {
                $this->_sale_percent = round(100 * ($this->start_price - $this->sell_price) / $this->start_price);
            }
        }
        return $this->_sale_percent;
    }

    private $_type;

    public function setType($type)
    {
        $this->_type = $type;
    }

    public function getType()
    {
        if (!$this->_type) {
            $this->_type = @strtoupper(explode('/', $this->item_url)[1]);
        }
        return $this->_type;
    }

    private $_category_name;

    public function setCategoryName($name)
    {
        $this->_category_name = $name;
    }

    public function getCategoryName()
    {
        if (!$this->_category_name) {
            $this->_category_name = $this->category_id;
        }
        return $this->_category_name;
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'type', 'localStartPrice', 'localSellPrice', 'salePercent', 'categoryName',
        ]);
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['type', 'localStartPrice', 'localSellPrice', 'salePercent', 'categoryName'], 'safe'],
        ]);
    }

}