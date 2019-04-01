<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 11:14
 */

namespace common\products\ebay;


class EbayDetailRequest extends \common\products\BaseRequest
{
    
    /**
     * Build Parameter as Array
     * @return array|mixed
     */
    public function params(){
        return $this->keyword;
    }
}