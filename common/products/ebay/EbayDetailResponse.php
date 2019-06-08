<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-29
 * Time: 17:44
 */

namespace common\products\ebay;


use common\products\BaseGate;
use common\products\BaseResponse;

/**
 * Class EbayDetailResponse
 * @package common\products\ebay
 * Chỉ một mục tiêu parser($response) dữ liệu trả về cho EbayProduct
 */
class EbayDetailResponse extends BaseResponse
{
    /**
     * EbayDetailResponse constructor.
     * @param EbayGate $gate
     * @param array $config
     */
    public function __construct(EbayGate $gate, array $config = [])
    {
        parent::__construct($gate, $config);
    }

    /**
     * @param $response
     * @return \common\products\BaseProduct|EbayProduct
     */
    public function parser($response)
    {
        if ($response['success']) {
//            $temp_i = 0;
//            foreach ($response['data']['variation_options'] as $option) {
//                $response['data']['variation_options'][$temp_i]['name'] = str_replace("\"", "''", $option["name"]);
//                $temp_j = 0;
//                foreach ($option['values'] as $item) {
//                    $response['data']['variation_options'][$temp_i]['values'][$temp_j] = str_replace("\"", "''", $item);
//                    $temp_j++;
//                }
//                $temp_i++;
//            }
//            $temp_i = 0;
//            foreach ($response['data']['variation_mapping'] as $option) {
//                $temp_j = 0;
//                foreach ($option['options_group'] as $item) {
//                    $response['data']['variation_mapping'][$temp_i]['options_group'][$temp_j]['name'] = str_replace("\"", "''", $item["name"]);
//                    $response['data']['variation_mapping'][$temp_i]['options_group'][$temp_j]['value'] = str_replace("\"", "''", $item["value"]);
//                    $temp_j++;
//                }
//                $temp_i++;
//            }\

            $response = $response['data'];
            if (isset($response['provider'])) {
                $response['providers'][] = $response['provider'];
                unset($response['provider']);
            }
            if (isset($response['usTaxRate'])) {
                $response['us_tax_rate'] = $response['usTaxRate'];
                unset($response['usTaxRate']);
            }

            return new EbayProduct($response);
        }

        return $response['message'];
    }
}
