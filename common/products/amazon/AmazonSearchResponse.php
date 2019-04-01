<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-29
 * Time: 20:30
 */

namespace common\products\amazon;


use common\products\BaseResponse;

class AmazonSearchResponse extends BaseResponse
{

    public function parser($response)
    {
        if ($this->isEmpty($response)) {
            return $response;
        }
        foreach ($response['products'] as $key => $value) {
            if ($this->isBanned($value['item_id'])) {
                unset($response['products'][$key]);
            } else {
                $amazon = new AmazonProduct();
                $amazon->sell_price = $value['sell_price'];
                $amazon->start_price = $value['retail_price'];
                $amazon->init();
                $response['products'][$key]['sell_price'] = $amazon->sell_price;
                if ($value['prices_range'] !== null && count($value['prices_range']) === 2) {
                    list($start, $end) = $value['prices_range'];
                    $amazon->sell_price = $start;
                    $amazon->init();
                    $sell_from = $amazon->getLocalizeTotalPrice();
                    $amazon->sell_price = $end;
                    $amazon->init();
                    $sell_to = $amazon->getLocalizeTotalPrice();
                    $result['products'][$key]['sale_price_local'] = $sell_from . ' - ' . $sell_to;
                } else {
                    $result['products'][$key]['sale_price_local'] = $amazon->getLocalizeTotalPrice();
                }
                $result['products'][$key]['start_price_local'] = $amazon->getLocalizeTotalStartPrice();
            }
        }
        return $response;
    }
}