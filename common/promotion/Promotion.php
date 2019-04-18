<?php

namespace common\promotion;

use common\helpers\ObjectHelper;
use common\helpers\WeshopHelper;
use common\models\db\Promotion as DbPromotion;
use yii\helpers\ArrayHelper;

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
     * NOTE:
     * normal:
     *      điều kiện : mixed
     *      tính toán:
     *          phần trăm hoặc số tiền được giảm tại phí đó
     *      ví dụ:
     *          - giảm 10% phí weshop (max 500k) => $feeWeshop * 10% (nếu lơn hơn max thì rả về max)
     *          - giảm 100k phí weshop => $feeWeshop - 100;
     *          - giảm 200k cho toàn đơn hàng :D => chưa rõ
     * over weight : tính cho phí vận chuyển quốc tế, vượt từ x kg thì giảm với độ tương ứng
     *      ví dụ : vượt quá 3 kg thì tặng phí vận chuyển của 2kg tiếp theo (không vượt quá 2kg),
     *      điều kiện: cân nặng trên 3kg, max 2kg
     *      tính toán: số tiền được giảm cho số kg vượt quá, nhưng không vượt quá max
     *          - trên 3kg dưới 5kg (3kg đầu và 2kg tiếp theo) => giảm cho 2 cân tiếp theo
     *          - trên 5kg cũng vẫn chỉ giảm 2kg (max 2kg), vẫn tính số cân nậng còn lại
     * over quantity : chưa áp dụng bao giờ
     *
     */
    const DISCOUNT_TYPE_NORMAL = 1;
    const DISCOUNT_TYPE_OVER_WEIGHT = 2;
    const DISCOUNT_TYPE_OVER_QUANTITY = 3;

    /**
     * @param PromotionItem $item
     * @return array|bool
     */
    public function checkCondition(PromotionItem $item)
    {
        $conditions = $this->conditions;
        if (empty($conditions)) {
            return true;
        }
        foreach ($conditions as $condition) {
            if (($passed = $condition->checkConditionRecursive($item)) === false) {
                return [$passed, $condition->promotionConditionConfig->description];
            }
        }
        return true;
    }

    protected function preRequest(PromotionRequest &$request)
    {
        $request->totalValidAmount = 0;
        foreach ($request->discountOrders as $index => $order) {
            /** @var $order Order */
            if ($this->discount_fee !== null) {
                $totalFeeAmount = 0;
                foreach ($order->getProducts() as $key => $product) {
                    /** @var $product Product */
//                    if(!$this->allow_order){
//                        $passedFee = $this->checkCondition($product);
//                    }
                    if (isset($product->additionalFees[$this->discount_fee]) && ($fee = $product->additionalFees[$this->discount_fee]) > 0) {
                        $totalFeeAmount += $fee;
                    }
                }
                $request->totalValidAmount += $totalFeeAmount;
            } else {
                $request->totalValidAmount += $order->totalAmount;
            }

        }
    }

    /**
     * @param PromotionRequest $request
     * @param PromotionResponse $response
     */
    public function calculatorDiscount(PromotionRequest $request, PromotionResponse &$response)
    {
        if (empty($request->discountOrders)) {
            return;
        }
        $this->preRequest($request);
        $discount = $this->discount_amount;
        // nếu là % thì tính phần trăm
        if ($this->discount_calculator === self::DISCOUNT_CALCULATOR_PERCENT) {
            $discount = ($discount / 100) * $request->totalValidAmount;
            // nếu là % mà giá trị discount lơn hơn max thì để giá trị max
            if ($this->discount_max_amount !== null && $discount > $this->discount_max_amount) {
                $discount = $this->discount_max_amount;
            }
        }

        foreach ($request->discountOrders as $index => $order) {
            /** @var $order Order */
            if ($this->discount_fee !== null) {
                foreach ($order->getProducts() as $key => $product) {
                    if (isset($product->additionalFees[$this->discount_fee]) && ($fee = $product->additionalFees[$this->discount_fee]) > 0) {
                        $feeDiscount = WeshopHelper::discountAmountPercent($fee, $request->totalValidAmount, $discount);
                        $product->discountFees[$this->discount_fee] = $feeDiscount;
                        if ($this->allow_multiple) {
                            $product->totalDiscountAmount += $feeDiscount;
                            $order->totalDiscountAmount += $feeDiscount;
                        } else {
                            $product->totalDiscountAmount = $feeDiscount;
                            $order->totalDiscountAmount = $feeDiscount;
                        }
                    }

                    $order->updateProduct($key, $product);
                }
            } else {
                $discountAmount = WeshopHelper::discountAmountPercent($order->totalAmount, $request->totalValidAmount, $discount);
                if ($this->allow_multiple) {
                    $order->totalDiscountAmount += $discountAmount;
                } else {
                    $order->totalDiscountAmount = $discountAmount;
                }
            }
        }
        if ($this->allow_multiple) {
            $request->totalDiscountAmount += $discount;
        } else {
            $request->totalDiscountAmount = $discount;
        }
        var_dump($request->orders);
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