<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-18
 * Time: 10:19
 */

namespace common\components;

/**
 * Interface AdditionalFeeInterface
 *
 */

interface AdditionalFeeInterface
{

    /**
     * @return \common\components\StoreManager
     */
    public function getStoreManager();

    /**
     * @return string
     */
    public function getItemType();

    /**
     * @return integer
     */
    public function getTotalOriginPrice();

    /**
     * @return mixed
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
     * @return boolean
     */
    public function getIsForWholeSale();

    /**
     * @return integer
     */
    public function getExchangeRate();

    /**
     * @return mixed
     */
    public function getStoreAdditionalFee();

    /**
     * @param null $names
     * @param bool $formSource
     * @return array
     */
    public function getAdditionalFees($names = null, $formSource = true);

    /**
     * @param $values
     * @param bool $withCondition
     * @return mixed
     */
    public function setAdditionalFees($values, $withCondition = false);

    public function getTotalAdditionFees($names = null);
}
