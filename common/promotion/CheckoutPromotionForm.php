<?php


namespace common\promotion;


use common\components\cart\CartManager;
use common\products\BaseProduct;
use yii\di\Instance;

/**
 * checkout, check form cart
 * Class CheckoutPromotionForm
 * @package common\promotion
 */
class CheckoutPromotionForm extends PromotionForm
{

    /**
     * @var array
     */
    public $cartIds;

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
     * @var string|CartManager
     */
    protected $cartManager = 'cart';

    public function init()
    {
        parent::init();
        $this->cartManager = Instance::ensure($this->cartManager, CartManager::className());
    }

    protected function createRequest()
    {
        $items = [];
        foreach ($this->cartIds as $key) {
            /** @var $product BaseProduct */
            if (($data = $this->cartManager->getItem($key)) !== false || !($product = $data['response']) instanceof BaseProduct) {
                $this->addError('cartIds', "can not create request from cart `$key`");
                return false;
            }
            $additionalFees = [];
            foreach ($product->getAdditionalFees()->toArray() as $key => $array){
                $additionalFees[$key] = $array['local_amount'];
            }
            $items[] = [
                'itemType' => $product->getItemType(),
                'customCategory' => $product->getCustomCategory()->id,
                'shippingQuantity' => $product->getShippingQuantity(),
                'shippingWeight' => $product->getShippingWeight(),
                'additionalFees' => $additionalFees,
            ];

        }
        return new PromotionRequest([
            'customer' => $this->customerId,
            'items' => $items,
            'paymentService' => $this->paymentService
        ]);
    }
}