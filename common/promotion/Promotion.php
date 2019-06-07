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


    const TYPE_COUPON = 1;
    const TYPE_COUPON_REFUND = 2;
    const TYPE_XU = 3;
    const TYPE_PROMOTION = 4;
    const TYPE_MARKETING_CAMPAIGN = 5;
    const TYPE_OTHERS = 6;

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
     * @param PromotionForm $form
     * @return array|bool
     */
    public function checkCondition(PromotionForm $form)
    {
        $conditions = $this->conditions;
        if (empty($conditions)) {
            return true;
        }
        foreach ($conditions as $condition) {
            if (($passed = $condition->checkConditionRecursive($form)) === false) {
                $value = $condition->value;
                $value = is_array($value) ? implode(', ', $value) : $value;
                $message = "{$condition->name} {$condition->promotionConditionConfig->operator} $value";
                return [$passed, $message];
            }
        }
        return true;
    }

    /**
     * @param PromotionForm $form
     * @param PromotionResponse $response
     */
    public function calculatorDiscount(PromotionForm $form, PromotionResponse $response)
    {
//
        $discount = $this->discount_amount;
        // nếu là % thì tính phần trăm
        if ($this->discount_calculator === self::DISCOUNT_CALCULATOR_PERCENT) {
            $calculatorAmount = $form->totalAmount;

            if ($this->discount_fee !== null && isset($form->additionalFees[$this->discount_fee]) && ($fee = $form->additionalFees[$this->discount_fee]) > 0) {
                $calculatorAmount = $fee;
            }

            $discount = ($discount / 100) * $calculatorAmount;

            // nếu là % mà giá trị discount lơn hơn max thì để giá trị max
            if ($this->discount_max_amount !== null && $discount > $this->discount_max_amount) {
                $discount = $this->discount_max_amount;
            }
        }

        if ($this->allow_multiple) {
            $response->discount += $discount;
            if ($this->discount_fee !== null) {
                $response->discountFees[$this->discount_fee][implode('-', [self::getType($this->type), $this->code])] = $discount;
            }
            $response->details[] = [
                'id' => $this->id,
                'code' => $this->code,
                'type' => self::getType($this->type),
                'message' => $this->message,
                'value' => $discount
            ];

        } else {
            $response->discount = $discount;
            if ($this->discount_fee !== null) {
                $response->discountFees = [];
                $response->discountFees[$this->discount_fee][implode('-', [self::getType($this->type), $this->code])] = $discount;
            }
            $response->details = [];
            $response->details[] = [
                'id' => $this->id,
                'code' => $this->code,
                'type' => self::getType($this->type),
                'message' => $this->message,
                'value' => $discount
            ];
        }

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

    public static function getType($type = null)
    {
        $types = [
            self::TYPE_COUPON => 'Coupon',
            self::TYPE_PROMOTION => 'Promotion',
            self::TYPE_XU => 'Xu',
            self::TYPE_COUPON_REFUND => 'Coupon Refund',
            self::TYPE_MARKETING_CAMPAIGN => 'Marketing Campaign',
            self::TYPE_OTHERS => 'Others',
        ];
        return $type !== null ? (isset($types[$type]) ? $types[$type] : 'Unknown') : $types;
    }
}