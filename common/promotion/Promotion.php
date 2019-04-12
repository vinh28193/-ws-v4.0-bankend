<?php

namespace common\promotion;

use common\models\db\Promotion as DbPromotion;

/**
 * Class Promotion
 * @package common\promotion
 *
 * @property PromotionCondition[] $conditions
 * @property PromotionCondition[] $promotionCondition
 * @property PromotionConditionConfig[] $promotionConditionConfig
 */
class Promotion extends DbPromotion
{

    /**
     * 1:Coupon/2:MultiCoupon/3:CouponRefund/4:Promotion/5:MultiProduct/6:MarketingCampaign/7:Others
     */
    const TYPE_COUPON = 1;
    const TYPE_MULTI_COUPON = 2;
    const TYPE_COUPON_REFUND = 3;
    const TYPE_PROMOTION = 4;
    const TYPE_MULTI_PRODUCT = 5;
    const TYPE_MARKETING_CAMPAIGN = 6;
    const TYPE_OTHERS = 7;

    /**
     * 1:%,2:fix value
     */
    const DISCOUNT_CALCULATOR_FIXED = 2;
    const DISCOUNT_CALCULATOR_PERCENT = 1;

    /**
     * 1:normal(price),2 weight,3 quantity
     */
    const DISCOUNT_TYPE_NORMAL = 1;
    const DISCOUNT_TYPE_OVER_WEIGHT = 2;
    const DISCOUNT_TYPE_OVER_QUANTITY = 3;

    /**
     * @param PromotionRequest $request
     * @return array|bool
     */
    public function checkCondition(PromotionRequest $request)
    {
        $conditions = $this->conditions;
        if (empty($condition)) {
            return true;
        }
        foreach ($conditions as $condition) {
            if (($passed = $condition->checkConditionRecursive($request)) === false) {
                return [$passed, $condition->promotionConditionConfig->description];
            }
        }
        return true;
    }

    /**
     * @param PromotionRequest $request
     */
    public function calculatorDiscount(PromotionRequest $request)
    {

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConditions()
    {
        return $this->getPromotionConditions()->with('promotionConditionConfig');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotionConditions()
    {
        return $this->hasMany(PromotionCondition::className(), ['promotion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotionConditionConfig()
    {
        return $this->hasMany(PromotionConditionConfig::className(), ['name' => 'name'])->via('promotionConditions');
    }
}