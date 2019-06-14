<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-18
 * Time: 10:19
 */

namespace common\components;

use common\models\User;
use common\models\Category;

/**
 * interface để đảm bảo có thể tính toán giá cho bất khì 1 đồi tượng nào khi được implements
 * Interface AdditionalFeeInterface
 * @package common\components
 *
 * @property-read string $type
 * @property-read null|string $portal
 * @property-read null|Category $category
 * @property-read null|User $user
 * @property-read boolean $isNew
 * @property-read boolean $isSpecial
 * @property-read integer $shippingWeight
 * @property-read integer $shippingQuantity
 * @property-read null|array|mixed $shippingFrom
 * @property-read null|array|mixed $shippingTo
 * @property-read null|array|mixed $shippingParcel
 */
interface AdditionalFeeInterface
{
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
     * @return User|null
     */
    public function getUser();

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

    /**
     * @return null|array|mixed
     */
    public function getShippingFrom();

    /**
     * @return null|array|mixed
     */
    public function getShippingTo();

    /**
     * @return null|array|mixed
     */
    public function getShippingParcel();
}
