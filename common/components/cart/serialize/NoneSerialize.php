<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-27
 * Time: 15:49
 */

namespace common\components\cart\serialize;


use common\products\amazon\AmazonProduct;
use common\products\BaseProduct;
use common\products\ebay\EbayProduct;

class NoneSerialize extends BaseCartSerialize
{
    /**
     * @param $value
     * @return mixed|string
     */
    public function serializer($value)
    {
        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function unserialize($value)
    {
        $type = isset($value['response']['type']) ? $value['response']['type'] : (isset($value['request']['type']) ? strtoupper($value['request']['type']) : false);
        switch ($type){
            case BaseProduct::TYPE_EBAY:
                $params = $value['response'];
                unset($params['additionalFees']);
                $params['isInitialized'] = false;
                $value['response'] = new EbayProduct($params);
                return $value;
            case BaseProduct::TYPE_AMAZON_US:
                $params = $value['response'];
                unset($params['additionalFees']);
                $params['isInitialized'] = false;
                $value['response'] = new AmazonProduct($params);
                return $value;
            default:
                return false;
        }
    }
}