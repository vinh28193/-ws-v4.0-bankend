<?php
/**
 * Created by PhpStorm.
 * User: Thaim
 * Date: 9/11/2017
 * Time: 2:15 PM
 */

namespace common\lib;

use common\components\ebay\EbayApiV3Client;
use common\components\Product;
use Yii;

class EbayProductGate
{
    static public function parse($asin_id)
    {
        $rs = Yii::$app->cache->get('EBAY_'.$asin_id);
        if($rs==null){
            $attempts = 0;
            do {
                $attempts++;
                $data = EbayApiV3Client::GetProduct($asin_id);
                $rs = json_decode($data, true);
                if (isset($rs['data']))
                    break;
            } while ($attempts < 5);
            $rs = json_decode($data, true);
            if ($rs['success']){
                // #ToDo Ky tu dac biet: "  -> ''

                $temp_i = 0;
                foreach ($rs['data']['variation_options'] as $option){
                    $rs['data']['variation_options'][$temp_i]['name'] = str_replace("\"" , "''",$option["name"]);
                    $temp_j = 0;
                    foreach ($option['values'] as $item){
                        $rs['data']['variation_options'][$temp_i]['values'][$temp_j] = str_replace("\"" , "''",$item);
                        $temp_j ++;
                    }
                    $temp_i ++;
                }
                $temp_i = 0;
                foreach ($rs['data']['variation_mapping'] as $option){
                    $temp_j = 0;
                    foreach ($option['options_group'] as $item){
                        $rs['data']['variation_mapping'][$temp_i]['options_group'][$temp_j]['name'] = str_replace("\"" , "''",$item["name"]);
                        $rs['data']['variation_mapping'][$temp_i]['options_group'][$temp_j]['value'] = str_replace("\"" , "''",$item["value"]);
                        $temp_j ++;
                    }
                    $temp_i ++;
                }
                // #End
                Yii::$app->cache->set('EBAY_'.$asin_id,$rs['data'],60*20);
                return $rs['data'];
            }
        }
        return $rs;
    }

    static function parseObject($asin_id, $store)
    {
        $rs = self::parse($asin_id);
        if ($rs == null) return null;
        return new Product($store,$rs);
    }

    static function isAllowBuyNow($website, $cateID)
    {
        return CustomImportCategory::isAllowImportWithAmazon($website->storeId, $cateID);
    }

    private function getOptionGroup($data, $detaiImage)
    {
        if (count($data) == 0) {
            return [];
        }
        $rs = [];
        foreach ($data['NameValueList'] as $item) {
            $temp['name'] = $item['Name'];
            $temp['values'] = $item['Value'];
            $temp['images_mapping'] = [];
            foreach ($detaiImage as $imgs) {
                if (count($imgs) > 0) {
                    if ($imgs['VariationSpecificName'] == $item['Name']) {
                        foreach ($imgs['VariationSpecificPictureSet'] as $k => $v) {
                            foreach ($temp['values'] as $value) {
                                if ($value == $v['VariationSpecificValue']) {
                                    $imgTemp = [];
                                    if (isset($v['PictureURL'])) {
                                        foreach ($v['PictureURL'] as $img) {
                                            $imgTemp[] = [
                                                'thumb' => self::removeSpecialChar($img),
                                                'main' => self::removeSpecialChar($img),
                                            ];
                                        }
                                    }

                                    $temp['images_mapping'][] = [
                                        'value' => $value,
                                        'images' => $imgTemp
                                    ];
                                }
                            }


                        }
                    }
                }
            }

            $rs[] = $temp;
        }
        return $rs;
    }

    private function getVariationMapping($data)
    {
        $varian = [];
        $i = 0;

        foreach ($data['Variations']['Variation'] as $datum) {
            $dat = [];
            $dat['variation_sku'] = isset($datum['SKU']) ? self::removeSpecialChar($datum['SKU']) : 'WS_' . $data['ItemID'] . '_' . $i;
            $dat['variation_price'] = $datum['StartPrice']['value'];
            $dat['available_quantity'] = $datum['Quantity'];
            $dat['quantity_sold'] = $datum['SellingStatus']['QuantitySold'];
            $dat['options_group'] = [];
            foreach ($datum['VariationSpecifics'] as $k => $value) {
                foreach ($value['NameValueList'] as $option) {
                    $dat['options_group'][] = [
                        'name' => self::removeSpecialChar($option['Name']),
                        'value' => self::removeSpecialChar($option['Value'][0])
                    ];
                }
            }
            $i++;
            $dat['images'] = [];
            $varian[] = $dat;
        }
        return $varian;
    }

    private function getItemImage($data)
    {
        $imgs = [];
        if (count($data) > 0)
            foreach ($data as $datum) {
                $temp = [];
                $temp['thumb'] = $datum;
                $temp['main'] = $datum;
                $imgs[] = $temp;
            }
        return $imgs;
    }


    function getProvider($data)
    {
        $rs['name'] = self::removeSpecialChar($data['Seller']['UserID']);
        $rs['website'] = isset($data['Storefront']['StoreURL']) ? self::removeSpecialChar($data['Storefront']['StoreURL']) : '';
        $rs['location'] = self::removeSpecialChar($data['Location']);
        $rs['rating_score'] = self::removeSpecialChar($data['Seller']['FeedbackScore']);
        $rs['positive_feedback_percent'] = self::removeSpecialChar($data['Seller']['PositiveFeedbackPercent']);
        return $rs;
    }

    function getRelateProduct($data)
    {
        $r = [];
        foreach ($data as $datum) {
            $rs['item_id'] = self::removeSpecialChar($datum['asin_id']);
            $rs['image'] = self::removeSpecialChar($datum['images'][0]);
            $rs['is_prime'] = $datum['is_prime'];
            $rs['rate_count'] = $datum['rate_count'];
            $rs['rate_star'] = $datum['rate_star'];
            $rs['sell_price'] = $datum['sell_price'][0];
            $rs['title'] = self::removeSpecialChar($datum['title']);
            $r[] = $rs;
        }
        return $r;

    }

    function getTechnicalSpecific($specific)
    {
        $rs = [];
        foreach ($specific['NameValueList'] as $item) {
            $temp['name'] = self::removeSpecialChar($item['Name']);
            $temp['value'] = implode(',', self::removeSpecialChar($item['Value']));
            $rs[] = $temp;
        }
        return $rs;

    }

    function removeSpecialChar($str)
    {
        return preg_replace("/<script.*?\/script>/s", "", $str) ?: $str;

    }

    public function convertTime($time)
    {
        return strtotime(str_replace(['T', 'Z'], [' ', ''], $time)) + 25200;
    }

    /**
     * @param $sku
     * @param $category
     * @param null $localtion
     * @return mixed
     */
    public static function paserSugget($sku, $category, $localtion = null){
        $rs = Yii::$app->cache->get('CACHE_SUGGET_EBAY_'.$sku);
        if(empty($rs)){
            $rs = EbayApiV3Client::GetSuggetItem($sku,$category,$localtion);
            Yii::$app->cache->set('CACHE_SUGGET_EBAY_'.$sku, $rs, 60*60);
        }
        return $rs;
    }

}