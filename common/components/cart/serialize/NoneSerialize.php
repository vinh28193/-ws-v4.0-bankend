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
        return $value;
    }
}