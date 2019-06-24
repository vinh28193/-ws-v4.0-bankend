<?php
/**
 * Created by PhpStorm.
 * User: Thaim
 * Date: 9/11/2017
 * Time: 2:15 PM
 */

namespace common\lib;


use common\components\amazon\AmazonApiV2Client;
use common\components\Product;
use common\models\amazon\AmazonProduct;
use common\models\amazon\AmazonSearchForm;
use linslin\yii2\curl\Curl;
use Yii;
use yii\caching\Cache;
use yii\helpers\ArrayHelper;

class AmazonProductGate
{
    static public function parse(AmazonSearchForm $searchForm)
    {
        $attempts = 0;
        do {
            $attempts++;
            $data = AmazonApiV2Client::postData($searchForm, false);
            if (isset($data['response']) || (count($data['response']['sell_price']) > 0 && count($data['response']['retail_price']) > 0 && count($data['response']['deal_price']) > 0 && $data['response']['title'] != null))

                break;
        } while ($attempts < 3);
        if (!isset($data['response'])) return null;
        $amazon = $data['response'];


        //if (count($amazon['sell_price']) == 0 && count($amazon['retail_price']) == 0 && count($amazon['deal_price']) == 0 && $amazon['title'] == null) return null;
        $rs = [];
        $rs['categories'] = array_unique($amazon['node_ids']);
        $rs['item_id'] = $searchForm->asin_id;
        $rs['item_sku'] = $searchForm->asin_id;
        $rs['rate_star'] = isset($amazon['rate_star']) ? $amazon['rate_star'] : 0;
        $rs['category_id'] = isset($amazon['node_ids'][count($amazon['node_ids']) - 1]) ? $amazon['node_ids'][count($amazon['node_ids']) - 1] : null;
        $rs['item_name'] = $amazon['title'];
        $rs['parent_item_id'] = $amazon['parent_asin_id'];
        $rs['retail_price'] = count($amazon['retail_price']) > 0 ? $amazon['retail_price'][0] : null;
        $rs['sell_price'] = count($amazon['sell_price']) > 0 ? $amazon['sell_price'][0] : null;
        $rs['sell_price_special'] = $amazon['sell_price'];
        $rs['product_type'] = count($amazon['sell_price']) == 0 ? 1 : 0;
        $rs['deal_price'] = count($amazon['deal_price']) > 0 ? $amazon['deal_price'][0] : null;
        $rs['deal_time'] = isset($amazon['deal_time']) ? $amazon['deal_time'] : null;
        $rs['shipping_weight'] = $amazon['shipping_weight'] / 1000;
        $rs['shipping_fee'] = $amazon['shipping_fee'];
        $rs['is_prime'] = $amazon['is_prime'];
        $rs['is_free_ship'] = $amazon['is_free_ship'];
        $rs['sort_desc'] = $amazon['description'];
        $rs['description'] = $amazon['feature_bullets'];
        $rs['best_seller'] = $amazon['best_seller'];
        $rs['load_sub_url'] = base64_encode($amazon['load_sub_url']);
        $rs['manufacturer_description'] = $amazon['manufacturer_description'];
        $rs['technical_specific'] = $amazon['specific_description'];
        $rs['start_price'] = !empty($amazon["retail_price"]) ? ($amazon["retail_price"][0]) : 0.0;
        $rs['condition'] = isset($amazon['condition']) ? $amazon['condition'] : 'new';
        $rs['type'] = $searchForm->store === Product::STORE_JP ? Product::TYPE_AMZON_JP : Product::TYPE_AMZON_US;
        $rs['primary_images'] = self::getItemImage($amazon['primary_images']);
        $rs['variation_options'] = self::getOptionGroup($amazon['sale_specifics'], $amazon['detail_images']);
        $rs['variation_mapping'] = self::getVariationMapping($amazon['sale_specifics'], $amazon['detail_images']);
        $rs['variation_diff_by'] = self::getVariationDiffBy($amazon['detail_images']['diff_by'], $amazon['sale_specifics']['options']);
        $rs['relate_products'] = self::getRelateProduct($amazon['suggest_products']);

//        $suggest_sets = $cache ? Yii::$app->cache->get('offers_' . $rs['item_sku']) : null;
        $suggest_sets = Yii::$app->cache->get('offers_' . $rs['item_sku']);
        if (!$suggest_sets) {
            foreach ($amazon['suggest_sets'] as $suggest_sets) {
                if ($suggest_sets['id'] == 'purchase' && count($suggest_sets['asins']) > 0) {
                    $asin_ids_purchase = implode(",", $suggest_sets['asins']);
                    $post_data_purchase = [
                        'store' => $searchForm->store,
                        'asin_ids' => $asin_ids_purchase
                    ];
                    $rs['suggest_set_purchase'] = AmazonApiV2Client::getAsins($post_data_purchase);
                }
                if ($suggest_sets['id'] == 'session' && count($suggest_sets['asins']) > 0) {
                    $asin_ids_session = implode(",", $suggest_sets['asins']);
                    $post_data_session = [
                        'store' => $searchForm->store,
                        'asin_ids' => $asin_ids_session
                    ];
                    $rs['suggest_set_session'] = AmazonApiV2Client::getAsins($post_data_session);
                }
            }
            $suggest_sets = [
                'suggest_set_purchase' =>isset($rs['suggest_set_purchase']) ? $rs['suggest_set_purchase'] : '',
                'suggest_set_session' => isset($rs['suggest_set_session']) ? $rs['suggest_set_session'] : '',
                ];
            Yii::$app->cache->set('suggest_set_' . $rs['item_sku'], $suggest_sets, 3600);
        }else{
            $rs['suggest_set_purchase'] = $suggest_sets['suggest_set_purchase'];
            $rs['suggest_set_session'] = $suggest_sets['suggest_set_session'];
        }
//        $offers = $cache ? Yii::$app->cache->get('offers_' . $rs['item_sku']) : null;
        if(!$searchForm->is_first_load){
            $offers = Yii::$app->cache->get('offers_' . $rs['item_sku']);
            if (!$offers) {
                $offers = AmazonApiV2Client::getOffers($rs['item_sku'], $searchForm->store);
                Yii::$app->cache->set('offers_' . $rs['item_sku'], $offers, 3600);
            }
            $rs['provider'] = [];
            if (isset($offers['response'])) {
                foreach ($offers['response'] as $item) {
                    $dat = [];
                    $dat['name'] = $item['seller']['seller_name'];
                    $dat['image'] = '';
                    $dat['website'] = '';
                    $dat['location'] = '';
                    $dat['rating_score'] = $item['seller']['rating_count'];
                    $dat['rating_star'] = $item['seller']['rate_star'];
                    $dat['positive_feedback_percent'] = $item['seller']['positive'];
                    $dat['condition'] = $item['condition'];
                    $dat['fulfillment'] = $item['fulfillment'];
                    $dat['is_free_ship'] = $item['is_free_ship'];
                    $dat['is_prime'] = $item['is_prime'];
                    $dat['price'] = $item['price'];
                    $dat['shipping_fee'] = $item['ship_fee'];
                    $dat['tax_fee'] = $item['tax_fee'];
                    $dat['priceApi'] = $item['price'];
                    $rs['provider'][] = $dat;
                }
                $rs['sell_price'] = $offers['response'][0]['price'];
                $rs['condition'] = $offers['response'][0]['condition'];
                $rs['is_free_ship'] = $offers['response'][0]['is_free_ship'];
                $rs['is_prime'] = $offers['response'][0]['is_prime'];
                $rs['sell_price'] = $offers['response'][0]['price'];
                $rs['shipping_fee'] = $offers['response'][0]['ship_fee'];
                $rs['tax_fee'] = $offers['response'][0]['tax_fee'];
            }
        }else{
            $rs['provider'] = [];
        }
        $rs['PriceApi'] = $rs['sell_price'];
        $rs['CurrencyApi'] = 'USD';
        $rs['ExRateApi'] = 1;
        if ($searchForm->store == Product::STORE_JP) {
            $rs['CurrencyApi'] = 'JPY';
            self::collectAlias($rs['categories'], 'detail', Product::STORE_JP);
            return self::getPriceJP($rs);
        }
        return $rs;
    }

    static function getPriceJP($rs)
    {
        $rs['ExRateApi'] = Website::JPYtoUSD(1);
        $rs['sell_price'] = Website::JPYtoUSD($rs['sell_price']);
        $rs['shipping_fee'] = Website::JPYtoUSD($rs['shipping_fee']);
        $rs['retail_price'] = Website::JPYtoUSD($rs['retail_price']);
        $rs['tax_fee'] = Website::JPYtoUSD($rs['tax_fee']);
        $rs['deal_price'] = Website::JPYtoUSD($rs['deal_price']);
        $rs['start_price'] = Website::JPYtoUSD($rs['start_price']);
        foreach ($rs['sell_price_special'] as $k => $v) {
            $rs['sell_price_special'][$k] = Website::JPYtoUSD($v);
        }
        foreach ($rs['relate_products'] as $k => $v) {
            $rs['relate_products'][$k]['sell_price'] = Website::JPYtoUSD($v['sell_price']);
        }
        foreach ($rs['suggest_set_purchase'] as $k => $v) {
            foreach ($v['sell_price'] as $key => $val) {
                $rs['suggest_set_purchase'][$k]['sell_price'][$key] = Website::JPYtoUSD($val);
            }
        }
        foreach ($rs['suggest_set_session'] as $k => $v) {
            foreach ($v['sell_price'] as $key => $val) {
                $rs['suggest_set_session'][$k]['sell_price'][$key] = Website::JPYtoUSD($val);
            }
        }
        foreach ($rs['provider'] as $k => $v) {
            $rs['provider'][$k]['price'] = Website::JPYtoUSD($v['price']);
            $rs['provider'][$k]['shipping_fee'] = Website::JPYtoUSD($v['shipping_fee']);
            $rs['provider'][$k]['tax_fee'] = Website::JPYtoUSD($v['tax_fee']);
        }
        return $rs;
    }

    /**
     * @param $searchForm AmazonSearchForm
     * @param $website \common\models\weshop\Website
     * @return AmazonProduct|null
     */
    public static function parseToObject($searchForm, $website)
    {
        $nocache = false;
        if (Yii::$app instanceof \yii\web\Application) {
            $get = Yii::$app->request->get();
            $nocache = isset($get['clear']) && $get['clear'] == 'yess';
        }

        if (is_string($searchForm)) {
            $searchForm = new AmazonSearchForm();
            $searchForm->asin_id = $searchForm;
        }
        $rs = false;
        if (!$nocache) {
            $rs = Yii::$app->cache->get($searchForm->asin_id);
        }
        if (!$rs) {
            $rs = self::parse($searchForm);
            if (!$rs) return null;
            Yii::$app->cache->set($searchForm->asin_id, $rs, 60 * 60);
        }
        return new AmazonProduct($website, $rs);
    }


    static function parseObject($searchForm, $website)
    {
        if (is_a(Yii::$app, 'yii\web\Application')) {
            $get = Yii::$app->request->get();
            $nocache = (isset($get['clear']) && $get['clear'] == 'yess') || (isset($get['clearProd']) && $get['clearProd'] == 'yes');
        } else {
            $nocache = false;
        }
        if (!is_object($searchForm)) {
            //Truong hop truyen vao la sku sp
            $id = $searchForm;
            $searchForm = new AmazonSearchForm();
            $searchForm->asin_id = $id;
        }
//        if($nocache){
//            Yii::info('Running without cache',__CLASS__);
//        }
        re_run:
        // Truong hop load san pham con
        if ($searchForm->parent_asin_id != '' && $searchForm->parent_asin_id != $searchForm->asin_id) {
            if (!$nocache) {
                $rs = Yii::$app->cache->get($searchForm->parent_asin_id . "-" . $searchForm->store);
                if ($rs) {
                    if (count($rs['variation_mapping'])) {
                        $check = false;
                        foreach ($rs['variation_mapping'] as $data_te) {
                            if ($data_te['variation_sku'] == $searchForm->asin_id) {
                                $check = true;
                                break;
                            }
                        }
                        if (!$check) {
                            $searchForm->asin_id = $searchForm->parent_asin_id;
                            $searchForm->parent_asin_id = '';
                            goto re_run;
                        }
                    } else {
                        $searchForm->asin_id = $searchForm->parent_asin_id;
                        $searchForm->parent_asin_id = '';
                        goto re_run;
                    }
                }
                $temp = Yii::$app->cache->get($searchForm->asin_id . "-" . $searchForm->store);
            } else {
                $rs = null;
                $temp = null;
            }
            if ($rs == null) {
                //Search ra san pham cha truoc de lay load sub url params, parent sku
                $mainSearch = new AmazonSearchForm();
                $mainSearch->asin_id = $searchForm->parent_asin_id;
                $mainSearch->store = $searchForm->store;
                $rs = self::parse($mainSearch);
                if ($rs == null) return null;
                Yii::$app->cache->set($searchForm->parent_asin_id . "-" . $searchForm->store, $rs, 60 * 60);
            }
            $amz = new AmazonProduct($website, $rs);
            $amz->type = $searchForm->store === AmazonProduct::STORE_US ? AmazonProduct::TYPE_AMZON_US : AmazonProduct::TYPE_AMZON_JP;
            $amz->item_origin_url = str_replace('amazon.com',$searchForm->store,$amz->item_origin_url);
            //new sku cua san pham cha khac voi sku can tim kiem
            if ($amz->item_id != $searchForm->asin_id && !$searchForm->is_first_load) {
                if ($searchForm->parent_asin_id != $amz->parent_item_id) {
                    //gan lai gia tri parent_asin_id  neu khac form truyen len
                    $searchForm->parent_asin_id = $amz->parent_item_id;
                }
                //Gan gia tri load_sub_url
                $searchForm->load_sub_url = $amz->load_sub_url;
                if (!$temp) {
                    $temp = self::parse($searchForm);
                    if ($temp == null) return null;
                    Yii::$app->cache->set($searchForm->asin_id . "-" . $searchForm->store, $temp);
                }
                $temp = (array)$temp;
                foreach ($amz->variation_mapping as $item) {
                    if ($item->variation_sku == $searchForm->asin_id) {
                        $item->variation_start_price = $temp['retail_price'] ? $temp['retail_price'] : 0;
                        $item->variation_price = $temp['sell_price'];
                        break;
                    }
                }
//
                $amz->provider = self::getProviders($temp['provider']);
//                $amz->categories = count($temp['categories']) > 0 ? $temp['categories'] : $amz->categories;
                $amz->deal_price = $temp['deal_price'];
                $amz->retail_price = $temp['retail_price'];
                $amz->start_price = $temp['start_price'];
                $amz->sell_price = $temp['sell_price'];
                $amz->condition = $temp['condition'];
                $amz->sell_price_special = $temp['sell_price_special'];
                $amz->shipping_fee = $amz->shipping_fee ? $amz->shipping_fee : $temp['shipping_fee'];
                $amz->shipping_weight = $amz->shipping_weight ? $amz->shipping_weight : ($temp['shipping_weight'] ? $temp['shipping_weight'] : 0.50);
                $amz->item_name = $temp['item_name'] ? $temp['item_name'] : $amz->item_name;
            }

            return $amz;
        } else {
            //Truong hop hop load sp lan dau
            $searchForm->parent_asin_id = null;
            $searchForm->load_sub_url = null;
            if (!$nocache) {
                $rs = Yii::$app->cache->get($searchForm->asin_id . "-" . $searchForm->store);
            } else {
                $rs = null;
            }
            if ($rs == null) {
                $rs = self::parse($searchForm);

                if ($rs == null) return null;
                Yii::$app->cache->set($searchForm->asin_id . "-" . $searchForm->store, $rs, 60 * 60);
            }
            $amz = new AmazonProduct($website, $rs);
            $amz->item_origin_url = str_replace('amazon.com',$searchForm->store,$amz->item_origin_url);
            $amz->type = $searchForm->store == 'amazon.com' ? AmazonProduct::TYPE_US : AmazonProduct::TYPE_JP;
            if ((($amz->item_id != $searchForm->asin_id) || (isset($amz->parent_item_id) && $amz->parent_item_id != $amz->item_id)) && !$searchForm->is_first_load) {
                $tempSearch = new AmazonSearchForm();
                $tempSearch->store = $searchForm->store;
                $tempSearch->asin_id = $searchForm->asin_id;
                $tempSearch->parent_asin_id = $amz->parent_item_id;
                $tempSearch->load_sub_url = $amz->load_sub_url;
                $tempSearch->is_first_load = $searchForm->is_first_load;
                if (!$nocache) {
                    $temp = Yii::$app->cache->get($tempSearch->asin_id . '-' . $tempSearch->parent_asin_id . "-" . $searchForm->store);
                } else {
                    $temp = null;
                }
                if (!$temp) {
                    $temp = self::parse($tempSearch);
                    if ($temp == null) return null;
                    Yii::$app->cache->set($tempSearch->asin_id . '-' . $tempSearch->parent_asin_id . "-" . $searchForm->store, $temp, 60 * 60);
                }
                $temp = (array)$temp;
                foreach ($amz->variation_mapping as $item) {
                    if ($item->variation_sku == $tempSearch->asin_id) {
                        $item->variation_start_price = $temp['retail_price'] ? $temp['retail_price'] : 0;
                        $item->variation_price = $temp['sell_price'];
                        break;
                    }
                }
                $amz->provider = self::getProviders($temp['provider']);
//                $amz->categories = count($temp['categories']) > 0 ? $temp['categories'] : $amz->categories;
                $amz->deal_price = $temp['deal_price'];
                $amz->retail_price = $temp['retail_price'];
                $amz->start_price = $temp['start_price'];
                $amz->sell_price = $temp['sell_price'];
                $amz->sell_price_special = $temp['sell_price_special'];
                $amz->shipping_fee = $amz->shipping_fee ? $amz->shipping_fee : $temp['shipping_fee'];
                $amz->shipping_weight = $amz->shipping_weight ? $amz->shipping_weight : ($temp['shipping_weight'] ? $temp['shipping_weight'] : 0.50);
                $amz->item_name = $temp['item_name'] ? $temp['item_name'] : $amz->item_name;
            }
            return $amz;
        }
    }

    private static function getOptionGroup($data, $detaiImage)
    {
        if (count($data) == 0 || count($data['options']) == 0) {
            return [];
        }
        $rs = [];
        foreach ($data['options'] as $item) {
            $temp['name'] = $item['name'];
            $temp['values'] = str_replace('"','\'\'',$item['values']);
            $temp['images_mapping'] = [];
            if (count($detaiImage) > 0) {
                if (isset($detaiImage['diff_by'])) {
                    foreach ($detaiImage['diff_by'] as $diffby) {
                        if ($diffby == $item['id']) {
                            foreach ($item['values'] as $val) {
                                foreach ($detaiImage['images'] as $k => $v) {
                                    if (strpos($k, $val) !== false) {
                                        $imgTemp = [];
                                        foreach ($v as $img) {
                                            $imgTemp[] = [
                                                'thumb' => $img['thumb'],
                                                'main' => $img['large'],
                                            ];
                                        }
                                        $temp['images_mapping'][] = [
                                            'value' => $k,
                                            'images' => $imgTemp
                                        ];
                                    }
                                }

                            }
                        }
                    }
                }
                if (isset($detaiImage['diff_by'][0]) && $detaiImage['diff_by'][0] == $item['id']) {
                    foreach ($detaiImage['images'] as $k => $v) {
                        $imgTemp = [];
                        foreach ($v as $img) {
                            $imgTemp[] = [
                                'thumb' => $img['thumb'],
                                'main' => $img['large'],
                            ];
                        }
                        $temp['images_mapping'][] = [
                            'value' => $k,
                            'images' => $imgTemp
                        ];
                    }
                }
            }
            $rs[] = $temp;
        }
        return $rs;

    }

    private static function getVariationDiffBy($diff_by, $options)
    {
        $data = [];
        foreach ($diff_by as $val) {
            foreach ($options as $v) {
                if ($val == $v['id']) {
                    $data[] = $v['name'];
                }
            }
        }
        return $data;
    }

    private static function getVariationMapping($data, $detailImage)
    {
        $varian = [];

        foreach ($data['map'] as $datum) {
            $imageDiff = $detailImage['diff_by'];
            $dat = [];
            $dat['variation_sku'] = $datum['ASIN'];
            $dat['options_group'] = [];
            foreach ($datum as $k => $value) {
                foreach ($data['options'] as $option) {
                    if ($k == $option['id']) {
                        $dat['options_group'][] = [
                            'name' => $option['name'],
                            'value' => str_replace('"','\'\'',$option['values'][$value])
                        ];
                        foreach ($imageDiff as $key => $diff) {
                            if ($diff == $option['id']) {
                                $imageDiff[$key] = str_replace('"','\'\'',$option['values'][$value]);
                            }
                        }
                    }

                }
            }

            $imageDiff = implode(' ', $imageDiff);
            $dat['image_diff'] = str_replace('"','\'\'',$imageDiff);
            foreach ($detailImage['images'] as $key => $image) {
                if ($key == $imageDiff) {
                    $dat['images'] = self::getItemImage($image);
                    break;
                }
            }

            $varian[] = $dat;
        }
        return $varian;
    }

    private static function getItemImage($data)
    {
        $imgs = [];
        if (count($data) > 0)
            foreach ($data as $datum) {
                $temp = [];
                $temp['thumb'] = $datum['thumb'];
                $temp['main'] = $datum['large'];
                $imgs[] = $temp;
            }
        return $imgs;
    }

    public function getProviders($data)
    {
        $rs = [];
        foreach ($data as $datum) {
            $rs[] = new Provider($datum);
        }
        return $rs;
    }

    private static function getProvider($data)
    {
        $rs['name'] = $data['brand'];
        $rs['image'] = count($data['brand_logo']) > 0 ? $data['brand_logo'][0] : '';
        return $rs;
    }

    private static function getRelateProduct($data)
    {

        $r = [];
        foreach ($data as $datum) {
            $rs['item_id'] = $datum['asin_id'];
            $rs['image'] = $datum['images'][0];
            $rs['is_prime'] = $datum['is_prime'];
            $rs['rate_count'] = $datum['rate_count'];
            $rs['rate_star'] = $datum['rate_star'];
            $rs['sell_price'] = isset($datum['sell_price'][0]) ? $datum['sell_price'][0] : 0;
            $rs['title'] = $datum['title'];
            $r[] = $rs;
        }
        return $r;

    }

    static function parseObjectUKRequest($searchForm, $website)
    {


        //Truong hop hop load sp lan dau
        $searchForm->parent_asin_id = null;
        $searchForm->load_sub_url = null;
        $rs = self::parse($searchForm);
        if ($rs == null) return null;
        $amz = new AmazonUkProduct($website, $rs);
        $amz->item_origin_url = str_replace('amazon.com', $searchForm->store, $amz->item_origin_url);
        $amz->type = 'AMAZON-UK';
        if (($amz->item_id != $searchForm->asin_id) || (isset($amz->parent_item_id) && $amz->parent_item_id != $amz->item_id)) {
            $tempSearch = new AmazonSearchForm();
            $tempSearch->asin_id = $searchForm->asin_id;
            $tempSearch->parent_asin_id = $amz->parent_item_id;
            $tempSearch->load_sub_url = $amz->load_sub_url;
            if (!$nocache) {
                $temp = Yii::$app->cache->get($tempSearch->asin_id . '-' . $tempSearch->parent_asin_id . "-" . $searchForm->store);
            } else {
                $temp = null;
            }
            if (!$temp) {
                $temp = self::parse($tempSearch);
                if ($temp == null) return null;
                Yii::$app->cache->set($tempSearch->asin_id . '-' . $tempSearch->parent_asin_id . "-" . $searchForm->store, $temp, 60 * 60);
            }
            $temp = (array)$temp;
            foreach ($amz->variation_mapping as $item) {
                if ($item->variation_sku == $tempSearch->asin_id) {
                    $item->variation_start_price = $temp['retail_price'] ? $temp['retail_price'] : 0;
                    $item->variation_price = $temp['sell_price'];
                    break;
                }
            }
            $amz->provider = self::getProviders($temp['provider']);
            $amz->categories = count($temp['categories']) > 0 ? $temp['categories'] : $amz->categories;
            $amz->deal_price = $temp['deal_price'];
            $amz->retail_price = $temp['retail_price'];
            $amz->start_price = $temp['start_price'];
            $amz->sell_price = $temp['sell_price'];
            $amz->sell_price_special = $temp['sell_price_special'];
            $amz->shipping_fee = $temp['shipping_fee'];
            $amz->shipping_weight = $temp['shipping_weight'] ? $temp['shipping_weight'] : 0.50;
            $amz->item_name = $temp['item_name'] ? $temp['item_name'] : $amz->item_name;
        }
        return $amz;

    }

    static function getImageTrackingPrice($sku,$providers = [], $store = 'us')
    {
        $html = file_get_contents('https://camelcamelcamel.com/------/product/' . $sku);
        $url = Yii::$app->cache->get('url_tracking_price_' . $sku . '_' . $store);
        if (!$url) {
            $seller = 'new';
            if(count($providers)){
                $seller = '';
                if(count($providers) == 1){
                    $seller = strpos('---'.strtolower($providers[0]->name),'amazon') ? 'amazon' : (strpos('use','---'.strtolower($providers[0]->condition)) ? 'used' : 'new');
                }else{
                    foreach ($providers as $provider){
                        /** @var Provider $provider */
                        if(strpos('---'.strtolower($provider->name),'amazon')){
                            $seller = 'amazon';
                            break;
                        }
                    }
                    foreach ($providers as $provider){
                        /** @var Provider $provider */
                        if(strpos('---'.strtolower($provider->condition),'new')){
                            $seller = $seller ? $seller.'-new' : 'new';
                            break;
                        }
                    }
                    foreach ($providers as $provider){
                        /** @var Provider $provider */
                        if(strpos('---'.strtolower($provider->condition),'use')){
                            $seller = $seller ? $seller.'-used' : 'used';
                            break;
                        }
                    }
                    $seller = $seller ? $seller : 'new';
                }
                $url = '//charts.camelcamelcamel.com/' . $store . '/' . $sku . '/'.$seller.'.png?force=1&zero=0&w=725&h=440&desired=false&legend=1&ilt=1&tp=all&fo=0&lang=en';
            }else{
                $url = '//charts.camelcamelcamel.com/' . $store . '/' . $sku . '/'.$seller.'.png?force=1&zero=0&w=725&h=440&desired=false&legend=1&ilt=1&tp=all&fo=0&lang=en';
            }
            Yii::$app->cache->set('url_tracking_price_' . $sku . '_' . $store, $url, 60 * 60);
        }
        return $url;
    }
    public static function getReviewCustomer($asin){
        try{
            $curl = new Curl();
            $rs = $curl->get('http://157.230.175.213:8000/amazon/asin/'.$asin);
            $response = json_decode($rs,true);
            $response = ArrayHelper::getValue($response,'response',[]);
            $response = ArrayHelper::getValue($response,'product_detail',[]);
            $response = ArrayHelper::getValue($response,'product_review',[]);
            return $response;
        }catch (\Exception $e){
            return null;
        }
    }
}
