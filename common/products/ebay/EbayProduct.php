<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 13:55
 */

namespace common\products\ebay;


class EbayProduct extends \common\products\BaseProduct
{

    public $type = self::TYPE_EBAY;


    public $shipping_details;

    public function init()
    {
        parent::init();
    }

    protected function generateOriginLink()
    {
        $this->item_origin_url = "http://rover.ebay.com/rover/1/711-53200-19255-0/1?icep_ff3=2&pub=5575037825&toolid=10001&campid=5337238823&customid=&icep_item=" . $this->item_id . "&ipn=psmain&icep_vectorid=229466&kwid=902099&mtid=824&kw=lg";
        return $this->item_origin_url;
    }

    public function getShippingWeight()
    {
        if (($category = $this->getCategory()) !== null && ($weight = $category->weight) !== null && $weight > 0) {
            $this->shipping_weight = $weight;
        }
        return parent::getShippingWeight(); // TODO: Change the autogenerated stub
    }
}