<?php


namespace common\additional;

use Yii;
use common\models\Category;
use common\models\User;
use yii\base\BaseObject;

class AdditionalFeeCalculator extends BaseObject implements AdditionalFeeInterface
{

    public $user_id;

    public $store_id;

    public $province;

    public $district;

    public $country;

    public $item_type;
    public $item_id;
    public $item_sku;
    public $item_seller;
    public $item_weight;
    public $item_quantity;
    public $item_category;

    private $_additionalFees;

    public function getAdditionalFees()
    {
        if ($this->_additionalFees === null) {
            $this->_additionalFees = new AdditionalFeeCollectionGuest();
            $this->_additionalFees->storeId = $this->store_id;
            $this->_additionalFees->userId = $this->user_id;
        }
        return $this->_additionalFees;
    }

    /**
     * @return string
     */
    public function getUniqueCode()
    {
        return $this->item_type;
    }

    /**
     * ebay/amazon
     * @return null|string
     */
    public function getType()
    {
        return $this->item_type;
    }

    /**
     * @return integer
     */
    public function getTotalOrigin()
    {
        return $this->getAdditionalFees()->getTotalOrigin();
    }

    /**
     * @return null|Category
     */
    public function getCategory()
    {
        return Category::findOne($this->item_category);
    }

    public function getUser()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getUserLevel()
    {
        return User::LEVEL_NORMAL;
    }

    /**
     * @return boolean
     */
    public function getIsNew()
    {
        return false;
    }

    /**
     * @return boolean
     */
    public function getIsSpecial()
    {
        return false;
    }

    /**
     * @return integer
     */
    public function getShippingWeight()
    {
        return $this->item_weight;
    }

    /**
     * @return integer
     */
    public function getShippingQuantity()
    {
        return $this->item_quantity;
    }

}