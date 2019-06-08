<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-18
 * Time: 10:19
 */

namespace common\components;

use common\models\User;

/**
 * interface để đảm bảo có thể tính toán giá cho bất khì 1 đồi tượng nào khi được implements
 * Interface AdditionalFeeInterface
 * @package common\components
 */
interface AdditionalFeeInterface
{

    /**
     * @return string
     */
    public function getItemType();

    /**
     * @return integer
     */
    public function getTotalOriginPrice();

    /**
     * @return \common\models\Category
     */
    public function getCustomCategory();

    /**
     * @return integer
     */
    public function getShippingWeight();

    /**
     * @return integer
     */
    public function getShippingQuantity();


    /**
     * @return User|null
     */
    public function getUser();

    /**
     * @return boolean
     */
    public function getIsNew();

    /**
     * @return integer
     */
    public function getExchangeRate();


}
