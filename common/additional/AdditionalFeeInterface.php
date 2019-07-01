<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-18
 * Time: 10:19
 */

namespace common\additional;

use common\models\User;
use common\models\Category;
use common\models\Warehouse;

/**
 * interface để đảm bảo có thể tính toán giá cho bất khì 1 đồi tượng nào khi được implements
 * Interface AdditionalFeeInterface
 * @package common\components
 *
 * @property-read string $uniqueCode
 * @property-read string $type
 * @property-read null|string $portal
 * @property-read null|Category $category
 * @property-read string $userLevel
 * @property-read boolean $isNew
 * @property-read boolean $isSpecial
 * @property-read integer $shippingWeight
 * @property-read integer $shippingQuantity
 */
interface AdditionalFeeInterface
{

    /**
     * @return string
     */
    public function getUniqueCode();

    /**
     * ebay/amazon
     * @return null|string
     */
    public function getType();

    /**
     * @return integer
     */
    public function getTotalOrigin();

    /**
     * @return null|Category
     */
    public function getCategory();

    /**
     * @return string
     */
    public function getUserLevel();

    /**
     * @return boolean
     */
    public function getIsNew();

    /**
     * @return boolean
     */
    public function getIsSpecial();

    /**
     * @return integer
     */
    public function getShippingWeight();

    /**
     * @return integer
     */
    public function getShippingQuantity();

}
