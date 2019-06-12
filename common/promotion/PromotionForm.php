<?php


namespace common\promotion;

use common\components\GetUserIdentityTrait;
use common\models\User;
use yii\base\Model;

/**
 * Class PromotionForm
 * @package common\promotion
 */
class PromotionForm extends Model
{
    use GetUserIdentityTrait;
    /**
     * @var string mã coupon
     */
    public $couponCode;

    /**
     * @var integer so luong xu su dung
     */
    public $xu;
    /**
     * @var integer
     */
    public $customerId;

    /**
     * @var string payment service
     * template "PaymentMethodId_PaymentBankCode"
     */
    public $paymentService;

    /**
     * @var integer|float
     */
    public $refundOrderId;


    /**
     * @var integer
     */
    public $totalAmount;

    /**
     * @var array
     */
    public $_orders = [];

    /**
     * @param $orders
     */
    public function setOrders($orders)
    {
        foreach ($orders as $key => $order) {
            $this->_orders[$key] = new Order($order);
        }
    }

    public function getOrders(){
        return $this->_orders;
    }
    /**
     * @inheritDoc
     */
    public function getFirstErrors()
    {
        return parent::getFirstErrors();
    }

    public function attributes()
    {
        return [

        ];
    }

    public function rules()
    {
        return [
            [['couponCode', 'customerId', 'paymentService', 'totalAmount','orders'], 'safe']
        ];
    }


    public function checkPromotion()
    {
        if (!$this->validate()) {
            $result['message'] = $this->getFirstErrors();
            return $result;
        }

        $response = new PromotionResponse();
        foreach ($this->findPromotion() as $key => $promotion) {
            /** @var $promotion Promotion */
            $passed = $promotion->checkCondition($this);
            if ($passed !== true && is_array($passed) && count($passed) === 2) {
                $response->errors[$promotion->code] = $passed[1];
                continue;
            }
            $request = new PromotionRequest();
            $request->discountOrders = $this->_orders;
            $promotion->calculatorDiscount($request, $response);
            if (isset($response->errors[$promotion->code]) && count($response->errors[$promotion->code]) > 0) {
                unset($response->errors[$promotion->code]);
            }
            $response->message = 'app dụng trương chình thành công';
        }
        if ($this->couponCode !== null && $this->couponCode !== '') {
            if (($coupon = $this->findCoupon()) === null) {
                $response->errors[$this->couponCode] = "Not Found Coupon `{$this->couponCode}` ";
                $response->success = count($response->details) > 0 && $response->discount > 0;
                return $response;
            }

            $passed = $coupon->checkCondition($this);
            if ($passed !== true && is_array($passed) && count($passed) === 2) {
                $response->errors[$coupon->code] = $passed[1];
            } else {
                $request = new PromotionRequest();
                $request->discountOrders = $this->_orders;
                $coupon->calculatorDiscount($request, $response);
                if (isset($response->errors[$coupon->code]) && count($response->errors[$coupon->code]) > 0) {
                    unset($response->errors[$coupon->code]);
                }
                $response->message = $coupon->message;
            }
        }
        if ($this->xu !== null && $this->xu > 0) {
            if (($xuPromotion = $this->findXu()) !== null) {
                $passed = $xuPromotion->checkCondition($this);
                if ($passed === true) {
                    $discount = $this->xu * 1000;
                    $response->discount += $discount;
                    $response->details[] = [
                        'id' => $xuPromotion->id,
                        'code' => $xuPromotion->code,
                        'type' => Promotion::getType($xuPromotion->type),
                        'message' => $xuPromotion->message,
                        'value' => $discount
                    ];
                }

            }


        }
        $response->success = count($response->details) > 0 && $response->discount > 0;
        return $response;
    }

    /**
     * @return Promotion|null
     */
    protected function findCoupon()
    {
        return Promotion::find()->where([
            'AND',
            ['code' => $this->couponCode],
            ['type' => Promotion::TYPE_COUPON],
            ['status' => 1]
        ])->one();
    }

    /**
     * @return Promotion[]|array
     */
    protected function findPromotion()
    {
        return Promotion::find()->where([
            'AND',
            ['type' => Promotion::TYPE_PROMOTION],
            ['status' => 1]
        ])->all();
    }

    /**
     * @return Promotion|null
     */
    protected function findXu()
    {
        return Promotion::find()->where([
            'AND',
            ['type' => Promotion::TYPE_XU],
            ['status' => 1]
        ])->one();
    }
}