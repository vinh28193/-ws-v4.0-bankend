<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-29
 * Time: 18:14
 */

namespace common\products\amazon;


use common\products\BaseGate;
use common\products\BaseResponse;

class AmazonDetailResponse extends BaseResponse
{

    /**
     * AmazonDetailResponse constructor.
     * @param AmazonGate $gate
     * @param array $config
     */
    public function __construct(AmazonGate $gate, array $config = [])
    {
        parent::__construct($gate, $config);
    }

    /**
     * @param $response
     * @return AmazonProduct
     */
    public function parser($response)
    {
        return new AmazonProduct($response);
    }
}