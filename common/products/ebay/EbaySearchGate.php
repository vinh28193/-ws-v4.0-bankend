<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 11:39
 */

namespace common\products\ebay;


class EbaySearchGate extends EbayGate
{

    public function search($params){

    }
    public function parseResponse($response)
    {
        parent::parseResponse($response);
    }
}