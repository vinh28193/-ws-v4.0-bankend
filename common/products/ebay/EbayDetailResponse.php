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
use yii\helpers\ArrayHelper;

/**
 * Class EbayDetailResponse
 * @package common\products\ebay
 * Chỉ một mục tiêu parser($response) dữ liệu trả về cho EbayProduct
 */
class EbayDetailResponse extends BaseResponse
{
    /**
     * EbayDetailResponse constructor.
     * @param EbayGate|EbayGateV4 $gate
     * @param array $config
     */
    public function __construct($gate, array $config = [])
    {
        parent::__construct($gate, $config);
    }

    /**
     * @param $response
     * @return \common\products\BaseProduct|EbayProduct
     */
    public function parser($response)
    {
        if (!empty($response) ) {
            if($response['success']) {
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
//            }
                $response = $response['data'];
                if (isset($response['provider'])) {
                    $response['providers'][] = $response['provider'];
                    unset($response['provider']);
                }
                if (isset($response['usTaxRate'])) {
                    $response['us_tax_rate'] = $response['usTaxRate'];
                    unset($response['usTaxRate']);
                }
                $data = [];
                $converted_current_price = ArrayHelper::getValue($response,'converted_current_price',[]);
                $currency = '';
                $price = 0;
                if($converted_current_price){
                    $currency = ArrayHelper::getValue($converted_current_price,'currency_id','USD');
                    $price = $currency == ArrayHelper::getValue($response,'sell_price_currency')? ArrayHelper::getValue($response,'sell_price',0):  ArrayHelper::getValue($converted_current_price,'value',0);
                }
                $data['shipping_details'] = ArrayHelper::getValue($response,'shipping_details',[]);
                $data['categories'] = ArrayHelper::getValue($response,'categories',[]);
                $data['item_id'] = ArrayHelper::getValue($response,'item_id');
                $data['parent_category_id'] = ArrayHelper::getValue($response,'parent_category_id');
                $data['parent_category_name'] = ArrayHelper::getValue($response,'parent_category_name');
                $data['condition'] = ArrayHelper::getValue($response,'condition');
                $data['available_quantity'] = ArrayHelper::getValue($response,'available_quantity');
                $data['quantity_sold'] = ArrayHelper::getValue($response,'quantity_sold');
                $data['item_name'] = ArrayHelper::getValue($response,'item_name');
                $data['category_id'] = ArrayHelper::getValue($response,'category_id');
                $data['category_name'] = ArrayHelper::getValue($response,'category_name');
                $data['sell_price'] = $price > 0 && $currency == 'USD' ? $price : ArrayHelper::getValue($response,'sell_price');
                $data['shipping_fee'] = ArrayHelper::getValue($response,'shipping_fee');
                $data['item_sku'] = ArrayHelper::getValue($response,'item_sku');
                $data['description'] = ArrayHelper::getValue($response,'description');
                $data['primary_images'] = ArrayHelper::getValue($response,'primary_images');
                $data['technical_specific'] = ArrayHelper::getValue($response,'technical_specific');
                $data['variation_options'] = ArrayHelper::getValue($response,'variation_options');
                $data['variation_mapping'] = ArrayHelper::getValue($response,'variation_mapping');
                $data['start_time'] = ArrayHelper::getValue($response,'start_time');
                $data['end_time'] = ArrayHelper::getValue($response,'end_time');
                $data['item_origin_url'] = ArrayHelper::getValue($response,'item_origin_url');
                $data['product_type'] = ArrayHelper::getValue($response,'product_type');
                $data['start_price'] = ArrayHelper::getValue($response,'start_price');
                $data['bid'] = ArrayHelper::getValue($response,'bid');
                $data['providers'] = ArrayHelper::getValue($response,'providers');
                return new EbayProduct($data);
            }
        }

        return $response['message'];
    }
}
