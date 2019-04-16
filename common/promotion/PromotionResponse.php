<?php


namespace common\promotion;


class PromotionResponse extends \yii\base\BaseObject
{

    /**
     * @param $success
     * @param $message
     * @param null $code
     * @param bool $isCoupon
     * @param int $value
     * @return array
     */
    public static function create($success, $message, $code = null, $isCoupon = false, $value = 0)
    {
        return [
            'success' => $success,
            'message' => $message,
            'code' => $code,
            'isCoupon' => $isCoupon,
            'discountAmount' => $value
        ];
    }
}