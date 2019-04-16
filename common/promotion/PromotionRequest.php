<?php


namespace common\promotion;


use common\models\Customer;

class PromotionRequest extends \yii\base\BaseObject
{

    public $paymentService;

    /**
     * @var integer|float
     */
    public $totalAmount;


    /**
     * @var string|Customer
     */
    private $_customer;

    /**
     * @return Customer|string|null
     */
    public function getCustomer(){
        if(!is_object($this->_customer)){
            $this->_customer = Customer::findOne($this->_customer);
        }
        return $this->_customer;
    }

    /**
     * @param $customer
     */
    public function setCustomer($customer){
        $this->_customer = $customer;
    }
    /**
     * @var PromotionItem[]
     */
    private $_items;

    /**
     * @return PromotionItem[]
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * @param array $items
     */
    public function setItems($items = [])
    {
        foreach ($items as $item) {
            $this->_items[] = new PromotionItem($item);
        }
    }

}