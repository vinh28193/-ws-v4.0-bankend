<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 11:20
 */

namespace common\products\ebay;


use common\products\BaseProductGate;
use yii\web\ServerErrorHttpException;

class EbayGate extends BaseProductGate
{

    public function getProduct($keyword)
    {
        $request = new EbayDetailRequest();
        $request->keyword = $keyword;
        if (!$request->validate()) {
            throw new ServerErrorHttpException("Error when validate");
        }
        return $this->sendRequest($request, true);
    }

    public function parseResponse($response)
    {
        if ($response['success']) {
            $temp_i = 0;
            foreach ($response['data']['variation_options'] as $option) {
                $response['data']['variation_options'][$temp_i]['name'] = str_replace("\"", "''", $option["name"]);
                $temp_j = 0;
                foreach ($option['values'] as $item) {
                    $response['data']['variation_options'][$temp_i]['values'][$temp_j] = str_replace("\"", "''", $item);
                    $temp_j++;
                }
                $temp_i++;
            }
            $temp_i = 0;
            foreach ($response['data']['variation_mapping'] as $option) {
                $temp_j = 0;
                foreach ($option['options_group'] as $item) {
                    $response['data']['variation_mapping'][$temp_i]['options_group'][$temp_j]['name'] = str_replace("\"", "''", $item["name"]);
                    $response['data']['variation_mapping'][$temp_i]['options_group'][$temp_j]['value'] = str_replace("\"", "''", $item["value"]);
                    $temp_j++;
                }
                $temp_i++;
            }
            $response = $response['data'];
            if(isset($response['provider'])){
                $response['providers'] = $response['provider'];
                unset($response['provider']);
            }
            if(isset($response['usTaxRate'])){
                $response['us_tax_rate'] = $response['usTaxRate'];
                unset($response['usTaxRate']);
            }

            return new EbayProduct($response);
        }

        return $response;
    }
}