<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-22
 * Time: 10:14
 */

namespace common\components\conditions;


class InternationalShippingFeeCondition extends BaseCondition
{

    // VN
    const FIXED_VN_AMOUT_InterShippingFee_Weight_In_0_500g = 10;
    /** 6$ + Units Dollar : FIXED  **/
    const FIXED_VN_AMOUT_InterShippingFee_Weight_In_500_1000g = 10;
    /** 11$ + Units Dollar : FIXED **/
    const FIXED_VN_AMOUT_InterShippingFee_Weight_Max_1000g = 10;
    /** 10$ + Units Dollar : FIXED **/

    // THAILAND
    const FIXED_THAILAND_AMOUT_InterShippingFee_Weight_In_0_500g = 20;
    /** 20$ + Units Dollar : FIXED  **/
    const FIXED_THAILAND_AMOUT_InterShippingFee_Weight_In_500_1000g = 20;
    /** 20$ + Units Dollar : FIXED **/
    const FIXED_THAILAND_AMOUT_InterShippingFee_Weight_Max_1000g = 20;
    /** 20$ + Units Dollar : FIXED **/

    // THAILAND FOR WHOLE SALE
    const FIXED_THAILAND_AMOUT_WHOLE_SALE_InterShippingFee_Weight_In_0_500g = 18;
    /** 18$ + Units Dollar : FIXED  **/
    const FIXED_THAILAND_AMOUT_WHOLE_SALE_InterShippingFee_Weight_In_500_1000g = 18;
    /** 18$ + Units Dollar : FIXED **/
    const FIXED_THAILAND_AMOUT_WHOLE_SALE_InterShippingFee_Weight_Max_1000g = 18;
    /** 18$ + Units Dollar : FIXED **/

    // MALAYSIA
    const FIXED_MALAYSIA_AMOUT_InterShippingFee_Weight_In_0_500g = 20;
    /** 20$ + Units Dollar : FIXED  **/
    const FIXED_MALAYSIA_AMOUT_InterShippingFee_Weight_In_500_1000g = 20;
    /** 20$ + Units Dollar : FIXED **/
    const FIXED_MALAYSIA_AMOUT_InterShippingFee_Weight_Max_1000g = 20;
    /** 20$ + Units Dollar : FIXED **/

    // MALAYSIA FOR WHOLE SALE
    const FIXED_MALAYSIA_AMOUT_WHOLE_SALE_InterShippingFee_Weight_In_0_500g = 20;
    /** 20$ + Units Dollar : FIXED  **/
    const FIXED_MALAYSIA_AMOUT_WHOLE_SALE_InterShippingFee_Weight_In_500_1000g = 20;
    /** 20$ + Units Dollar : FIXED **/
    const FIXED_MALAYSIA_AMOUT_WHOLE_SALE_InterShippingFee_Weight_Max_1000g = 20;
    /** 20$ + Units Dollar : FIXED **/

    // INDONESIA
    const FIXED_INDONESIA_AMOUT_InterShippingFee_Weight_In_0_500g = 12;
    /** 10$ + Units Dollar : FIXED  **/
    const FIXED_INDONESIA_AMOUT_InterShippingFee_Weight_In_500_1000g = 24;
    /** 24$ + Units Dollar : FIXED **/
    const FIXED_INDONESIA_AMOUT_InterShippingFee_Weight_Max_1000g = 24;
    /** 24$ + Units Dollar : FIXED **/

    // INDONESIA FOR WHOLE SALE
    const FIXED_INDONESIA_AMOUT_WHOLE_SALE_InterShippingFee_Weight_In_0_500g = 12;
    /** 10$ + Units Dollar : FIXED  **/
    const FIXED_INDONESIA_AMOUT_WHOLE_SALE_InterShippingFee_Weight_In_500_1000g = 24;
    /** 24$ + Units Dollar : FIXED **/
    const FIXED_INDONESIA_AMOUT_WHOLE_SALE_InterShippingFee_Weight_Max_1000g = 24;
    /** 24$ + Units Dollar : FIXED **/

    // PHILIPPINES
    const FIXED_PHILIPPINES_AMOUT_InterShippingFee_Weight_In_0_500g = 23;
    /** 20$ + Units Dollar : FIXED  **/
    const FIXED_PHILIPPINES_AMOUT_InterShippingFee_Weight_In_500_1000g = 23;
    /** 20$ + Units Dollar : FIXED **/
    const FIXED_PHILIPPINES_AMOUT_InterShippingFee_Weight_Max_1000g = 23;
    /** 20$ + Units Dollar : FIXED **/

    // PHILIPPINES FOR WHOLE SALE
    const FIXED_PHILIPPINES_AMOUT_WHOLE_SALE_InterShippingFee_Weight_In_0_500g = 23;
    /** 20$ + Units Dollar : FIXED  **/
    const FIXED_PHILIPPINES_AMOUT_WHOLE_SALE_InterShippingFee_Weight_In_500_1000g = 23;
    /** 20$ + Units Dollar : FIXED **/
    const FIXED_PHILIPPINES_AMOUT_WHOLE_SALE_InterShippingFee_Weight_Max_1000g = 23;
    /** 20$ + Units Dollar : FIXED **/

    public $name = 'InternationalShippingFee';
    /**
     * @param integer $value
     * @param \ws\base\AdditionalFeeInterface $additionalFee
     * @param $storeAdditionalFee
     * @return int
     */
    public function execute($value, $additionalFee, $storeAdditionalFee)
    {
        $resultFee = 0.0;
        $shippingWeight = $additionalFee->getShippingWeight();
        switch ($additionalFee->getStoreManager()->getId()) {
            case 1: // vietnam with special calculation
                if ($shippingWeight <= 0.5) {
                    /** if  Shipping Weight (W) in 0-500g: 5$  ***/
                    $resultFee = $shippingWeight * @self::FIXED_VN_AMOUT_InterShippingFee_Weight_In_0_500g;
                    /** return 5$ **/
                } else if ($shippingWeight > 0.5 && $shippingWeight <= 1.0) {
                    $resultFee = $shippingWeight * @self::FIXED_VN_AMOUT_InterShippingFee_Weight_In_500_1000g;
                    /** return 10$ **/

                } else if ($shippingWeight > 1.0) {
                    $resultFee = $shippingWeight * @self::FIXED_VN_AMOUT_InterShippingFee_Weight_Max_1000g;
                    /** return 10$ **/
                }

                if ($additionalFee->getIsForWholeSale()) {
                    /**  Phí quốc tế khach buôn VIET NAM + https://docs.google.com/spreadsheets/d/1HsUBKdpbiMtZvyTcDMRnLbAOvCLqgA5KddClMQnkQL0/edit#gid=1913713481 ***/
                    //#Todo deadline 10/10/2017 have refactor code Whole Sales Viet Nam
                    // ---> now returm 0$ for VN
                    $resultFee = 0;
                    if(($category = $additionalFee->getCustomCategory()) !== null && $category->interShippingB !== null && $category->interShippingB > 0){
                        $resultFee = $shippingWeight * $category->interShippingB;
                    }
                }
                break;

            case 7: //indonesia with special calculation
                // Not For WholeSale
                if ($shippingWeight <= 0.5) {
                    $resultFee = @self::FIXED_INDONESIA_AMOUT_InterShippingFee_Weight_In_0_500g;
                    /** return 10$ **/
                } else if ($shippingWeight > 0.5) {
                    $resultFee = @self::FIXED_INDONESIA_AMOUT_InterShippingFee_Weight_In_0_500g + ($shippingWeight - 0.5) * @self::FIXED_INDONESIA_AMOUT_InterShippingFee_Weight_In_500_1000g;
                    /** return 24$ **/
                }
//                else if ($shippingWeight > 1.0) {
//                    $resultFee = $shippingWeight * @self::FIXED_INDONESIA_AMOUT_InterShippingFee_Weight_Max_1000g;
//                    /** return 24$ **/
//                }

                // For WholeSale
//                if ($additionalFee->getIsForWholeSale()) {
//                    /** Double code but it is Hook Refactor + Fixed Amount For Whole Sale of case 'thai lan'  **/
//                    if ($shippingWeight <= 0.5) {
//                        $resultFee = $shippingWeight * @self::FIXED_INDONESIA_AMOUT_InterShippingFee_Weight_In_0_500g;
//                        /** return 10$ **/
//                    } else if ($shippingWeight > 0.5 && $shippingWeight <= 1.0) {
//                        $resultFee = $shippingWeight * @self::FIXED_INDONESIA_AMOUT_InterShippingFee_Weight_In_500_1000g;
//                        /** return 24$ **/
//                    } else if ($shippingWeight > 1.0) {
//                        $resultFee = $shippingWeight * @self::FIXED_INDONESIA_AMOUT_InterShippingFee_Weight_Max_1000g;
//                        /** return 24$ **/
//                    }
//                }
                break;

            case 8: //sg
                break;

            case 9: //ph
                // Not For WholeSale
                if ($shippingWeight <= 0.5) {
                    $resultFee = $shippingWeight * @self::FIXED_PHILIPPINES_AMOUT_InterShippingFee_Weight_In_0_500g;
                    /** return 20$ **/
                } else if ($shippingWeight > 0.5 && $shippingWeight <= 1.0) {
                    $resultFee = $shippingWeight * @self::FIXED_PHILIPPINES_AMOUT_InterShippingFee_Weight_In_500_1000g;
                    /** return 20$ **/
                } else if ($shippingWeight > 1.0) {
                    $resultFee = $shippingWeight * @self::FIXED_PHILIPPINES_AMOUT_WHOLE_SALE_InterShippingFee_Weight_Max_1000g;
                    /** return 20$ **/
                }

                // For WholeSale
                if ($additionalFee->getIsForWholeSale()){
                    /** Double code but it is Hook Refactor + process for Philipin**/
                    if ($shippingWeight <= 0.5) {
                        $resultFee = $shippingWeight * @self::FIXED_PHILIPPINES_AMOUT_WHOLE_SALE_InterShippingFee_Weight_In_0_500g;
                        /** return 20$ **/
                    } else if ($shippingWeight > 0.5 && $shippingWeight <= 1.0) {
                        $resultFee = $shippingWeight * @self::FIXED_PHILIPPINES_AMOUT_InterShippingFee_Weight_In_500_1000g;
                        /** return 20$ **/
                    } else if ($shippingWeight > 1.0) {
                        $resultFee = $shippingWeight * @self::FIXED_PHILIPPINES_AMOUT_InterShippingFee_Weight_Max_1000g;
                        /** return 20$ **/
                    }
                }
                break;

            case 6: //my
                // Not For WholeSale
                if ($shippingWeight <= 0.5) {
                    $resultFee = $shippingWeight * @self::FIXED_MALAYSIA_AMOUT_InterShippingFee_Weight_In_0_500g;
                    /** return 20$ **/
                } else if ($shippingWeight > 0.5 && $shippingWeight <= 1.0) {
                    $resultFee = $shippingWeight * @self::FIXED_MALAYSIA_AMOUT_InterShippingFee_Weight_In_500_1000g;
                    /** return 20$ **/
                } else if ($shippingWeight > 1.0) {
                    $resultFee = $shippingWeight * @self::FIXED_MALAYSIA_AMOUT_InterShippingFee_Weight_Max_1000g;
                    /** return 20$ **/
                }

                // For WholeSale
                if ($additionalFee->getIsForWholeSale()){
                    /** Double code but it is Hook Refactor + process for Thai lan **/
                    if ($shippingWeight <= 0.5) {
                        $resultFee = $shippingWeight * @self::FIXED_MALAYSIA_AMOUT_WHOLE_SALE_InterShippingFee_Weight_In_0_500g;
                        /** return 18$ **/
                    } else if ($shippingWeight > 0.5 && $shippingWeight <= 1.0) {
                        $resultFee = $shippingWeight * @self::FIXED_MALAYSIA_AMOUT_WHOLE_SALE_InterShippingFee_Weight_In_500_1000g;
                        /** return 18$ **/
                    } else if ($shippingWeight > 1.0) {
                        $resultFee = $shippingWeight * @self::FIXED_MALAYSIA_AMOUT_WHOLE_SALE_InterShippingFee_Weight_Max_1000g;
                        /** return 18$ **/
                    }
                }
                break;

            case 10: //th
                // Not For WholeSale
                if ($shippingWeight <= 0.5) {
                    $resultFee = $shippingWeight * @self::FIXED_THAILAND_AMOUT_InterShippingFee_Weight_In_0_500g;
                } else if ($shippingWeight > 0.5 && $shippingWeight <= 1.0) {
                    $resultFee = $shippingWeight * @self::FIXED_THAILAND_AMOUT_InterShippingFee_Weight_In_500_1000g;
                } else if ($shippingWeight > 1.0) {
                    $resultFee = $shippingWeight * @self::FIXED_THAILAND_AMOUT_InterShippingFee_Weight_Max_1000g;
                    /** return 20$ **/
                }
                // For WholeSale
                if ($additionalFee->getIsForWholeSale()) {
                    /** Double code but it is Hook Refactor + process for Thai lan   **/
                    if ($shippingWeight <= 0.5) {
                        $resultFee = $shippingWeight * @self::FIXED_THAILAND_AMOUT_WHOLE_SALE_InterShippingFee_Weight_In_0_500g;
                        /** return 18$ **/
                    } else if ($shippingWeight > 0.5 && $shippingWeight <= 1.0) {
                        $resultFee = $shippingWeight * @self::FIXED_THAILAND_AMOUT_WHOLE_SALE_InterShippingFee_Weight_In_500_1000g;
                        /** return 18$ **/
                    } else if ($shippingWeight > 1.0) {
                        $resultFee = $shippingWeight * @self::FIXED_THAILAND_AMOUT_WHOLE_SALE_InterShippingFee_Weight_Max_1000g;
                        /** return 18$ **/
                    }
                }
                break;
        }
        return $resultFee;
    }
}