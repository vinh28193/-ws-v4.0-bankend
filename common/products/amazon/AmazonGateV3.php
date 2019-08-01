<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-07
 * Time: 09:06
 */


namespace common\products\amazon;

use common\helpers\WeshopHelper;
use common\products\Provider;
use linslin\yii2\curl;
use common\models\Category;
use common\products\BaseGate;
use Yii;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

class AmazonGateV3 extends BaseGate
{

    public $store = AmazonProduct::STORE_US;
    public $searchUrl = 'search';
    public $lookupUrl = 'get';
    public $offerUrl = 'get_offers';        //Get Seller
    public $asinsUrl = 'asin';

    public $sellerBlackLists = [];

    /**
     * @param $params
     * @param bool $refresh
     * @return array|mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     * Search Pages Amazon of Weshop
     */
    public function search($params, $refresh = false)
    {
        $params['store'] = $this->store;
        $request = new AmazonSearchRequest();
        $request->load($params, '');
        if (!$request->validate()) {
            return [false, $request->getFirstErrors()];
        }
//        $results = $this->searchInternalOld($request);
        $results = $this->searchInternal($request);
//        if (!($results = $this->cache->get($request->getCacheKey())) || $refresh) {
//            $results = $this->searchInternal($request);
//            $this->cache->set($request->getCacheKey(), $results, $results[0] === true ? self::MAX_CACHE_DURATION : 0);
//        }

        list($ok, $response) = $results;
        if ($ok && is_array($response)) {
//            print_r($response);
//            print_r((new AmazonSearchResponse($this))->parser($response));
//            die;
            return [$ok, (new AmazonSearchResponse($this))->parser($response)];
        }
        return [false, $response];

    }

    /**
     * @param $condition
     * @param bool $refresh
     * @return array|mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     * Pages Details Amazon Weshop
     */
    public function lookup($condition, $refresh = false)
    {

        $condition['store'] = $this->store;
        $request = new AmazonDetailRequest();
        $request->load($condition, '');
        $request->get_offer = ArrayHelper::getValue($condition, 'get_offer', true);
        if (!$request->validate()) {
            return [false, $request->getFirstErrors()];
        }

        $tokens = ["Check with `$request->store`, validated success"];
        $tokens[] = "refresh cache " . ($refresh === true ? 'true' : 'false');
        $results = $this->lookupInternal($request);
//            if (!($results = $this->cache->get($cloneRequest->getCacheKey())) || $refresh) {
//                $results = $this->lookupInternal($cloneRequest);
//                $this->cache->set($cloneRequest->getCacheKey(), $results, $results[0] === true ? self::MAX_CACHE_DURATION : 0);
//            }
        list($ok, $response) = $results;
        $tokens[] = "response return :" . ($ok ? 'true' : 'false');
        if ($ok && is_array($response)) {
            /** @var  $product \common\products\amazon\AmazonProduct */
            $product = (new AmazonDetailResponse($this))->parser($response);
            Yii::info(implode(", ", $tokens), __METHOD__);
            return [true, $product];
        }
    }

    /**
     * @param $ids
     * @param bool $refresh
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getAsins($ids)
    {
        $ids = implode(",", $ids);
        $httpClient = $this->getHttpClient();
        $httpRequest = $httpClient->createRequest();
        $httpRequest->setFormat(Client::FORMAT_RAW_URLENCODED);
        $httpRequest->setMethod('POST');
        $httpRequest->setUrl($this->asinsUrl);
        $httpRequest->setData([
            'store' => $this->store,
            'asin_ids' => $ids
        ]);
        try {
            $httpResponse = $httpClient->send($httpRequest);
            if (!$httpResponse->isOk) {
                return [];
            }
            $httpResponse = $httpResponse->getData();
            return $httpResponse['response'] ? $httpResponse['response'] : [];
        } catch (\Exception $e) {
            Yii::error($e, __METHOD__);
            return [];
        }

    }


    /**
     * @param $itemId
     * @param bool $refresh
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getOffers($itemId)
    {
        $curl = new curl\Curl();

        $response = $curl->get($this->baseUrl . '/asin_offer/' . $itemId);
        if ($curl->responseCode != 200) {
            return [];
        }
        $response = json_decode($response, true);
        return ArrayHelper::getValue($response, 'response', []);
    }

    /**
     * @param $request AmazonSearchRequest
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    private function searchInternal($request)
    {
        $url = $this->baseUrl . '/' . $this->searchUrl . '?';
        $url .= http_build_query($request->params());
        $url = trim($url);
        $curl = new curl\Curl();
//        $countCall = 0;
        $response = $curl->get($url);
        Yii::debug($curl->responseCode);
//        while ($countCall < 3 && $curl->responseCode != 200){
//            $countCall ++;
//            $response = $curl->get($url);
//            Yii::debug($curl->responseCode);
//        }
//        Yii::debug($response);
        if ($curl->responseCode !== 200) {
            $response = $curl->get($url);
            Yii::debug($curl->responseCode);
            if ($curl->responseCode !== 200) {
                return [false, 'Request Error ' . $curl->responseCode];
            }
        }
        $response = json_decode($response, true);
        Yii::debug($response);
        if (!isset($response)) {
            return [false, 'can not send request'];
        }
        if (!isset($response['response'])) {
            return [false, 'invalid response'];
        };
        $result = $response['response'];

        if (!isset($result['total_product'])) return [];
        $data['products'] = $this->getSearchProduct($result['products']);
        $data['total_product'] = $result['total_product'] > 0 ? $result['total_product'] : ($result['total_page'] > 0 ? $result['total_page'] * count($data['products']) : count($data['products']));
//        $result['categories'] = array_unique($result['categories']);
        $siteId = $request->store === AmazonProduct::STORE_US ? Category::SITE_AMAZON_US : ($request->store === AmazonProduct::STORE_JP ? Category::SITE_AMAZON_JP : null);

//        $categories = Category::find()->where([
//            'OR',
//            ['alias' => $result['categories']],
//            ['path' => $result['categories']]
//        ])->forSite($siteId)->select(['alias as category_id', 'name as category_name', 'originName as origin_name'])->asArray()->all();
        $sorts = [];
        foreach ($result['sorts'] as $value) {
            $sorts[ArrayHelper::getValue($value, 'value', '#')] = ArrayHelper::getValue($value, 'name', 'None');
        }
        $data['categories'] = array_map(function ($tag) {
            return array(
                'category_name' => $tag['name'],
                'category_id' => $tag['value']
            );
        }, $result['categories']);//$categories;
        $data['sorts'] = $sorts;
        $data['filters'] = $this->getFilterNew($result['filters']);
        $data['total_page'] = ceil($result['total_page']);
        if ($this->store === AmazonProduct::STORE_JP) {
            /** @var  $exRate \common\components\ExchangeRate */
            $exRate = Yii::$app->exRate;
            foreach ($data['products'] as $k => $v) {
                $data['products'][$k]['sell_price'] = $exRate->jpyToUsd($data['products'][$k]['sell_price']);
                $data['products'][$k]['retail_price'] = $exRate->jpyToUsd($data['products'][$k]['retail_price']);
                foreach ($data['products'][$k]['prices_range'] as $key => $value) {
                    $data['products'][$k]['prices_range'][$key] = $exRate->jpyToUsd($value);
                }
            }
        }
        return [true, $data];
    }

    /**
     * @param AmazonDetailRequest $request
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    private function lookupInternal(AmazonDetailRequest $request)
    {
        $curl = new curl\Curl();

        $response = $curl->get($this->baseUrl . '/' . $this->asinsUrl . '/' . $request->asin_id);
        $response = json_decode($response, true);
        Yii::debug($curl->responseCode);
        if ($curl->responseCode != 200) {
            return [false, 'Request error ' . $curl->responseCode];
        }
        if (!$this->isValidResponse($response)) {
            return [false, 'can not send request'];
        }

//        } while ($attempts < 3);
//        if (!isset($response)) {
//            return [false, 'can not send request'];
//        }
        $amazon = null;
        if (isset($response['response'])) {
            $response = $response['response'];
            if (isset($response['product_detail'])) {
                $amazon = $response['product_detail'];
            }
        }
        if (!$amazon || !$response) {
            return [false, 'Request Error'];
        }
        $rs = [];

        $start_price = isset($amazon['price']) && $amazon['price'] !== null ? explode('-', $amazon['price']) : [0];
        $sell_price = isset($amazon['current_price']) && !WeshopHelper::isEmpty($amazon['current_price']) ? explode('-', $amazon['current_price']) : $start_price;
        $rs['categories'] = array_unique($amazon['node_ids']);
        $rs['item_id'] = $request->asin_id;
        $rs['item_sku'] = $request->asin_id;
        $rs['rate_star'] = isset($amazon['rate_star']) ? $amazon['rate_star'] : 0;
        $rs['rate_count'] = isset($amazon['rate_count']) ? intval(trim($amazon['rate_count'])) : 0;
        $rs['category_id'] = isset($amazon['node_ids'][count($amazon['node_ids']) - 1]) ? $amazon['node_ids'][count($amazon['node_ids']) - 1] : null;
        $rs['item_name'] = trim($amazon['title']);
        $rs['parent_item_id'] = $request->parent_asin_id ? $request->parent_asin_id : '';

        $rs['retail_price'] = count($sell_price) > 0 ? floatval(str_replace(',', '', trim($sell_price[0]))) : 0;
        $rs['sell_price'] = count($sell_price) > 0 ? floatval(str_replace(',', '', trim($sell_price[0]))) : 0;

        $rs['sell_price_special'] = $sell_price;
        $rs['product_type'] = count($sell_price) == 0 ? 1 : 0;
        $rs['deal_price'] = null;
        $rs['deal_time'] = null;
        $rs['shipping_weight'] = floatval($amazon['weight']) > 0 ? round(floatval($amazon['weight']) / 1000, 2) : 0.5;
        $rs['shipping_fee'] = 0;
        $rs['is_prime'] = null;
        $rs['is_free_ship'] = null;
        $rs['sort_desc'] = is_array($amazon['product_description']) ? implode('<br>', $amazon['product_description']) : $amazon['product_description'];
        $rs['description'] = isset($amazon['description']) ? $amazon['description'] : '';
        $rs['best_seller'] = '';
        $rs['manufacturer_description'] = '';
        $rs['primary_images'] = $this->getItemImage($amazon['primary_images']);//$amazon['images'];
        $rs['technical_specific'] = $this->getTechnicalSpecific($amazon['product_information']);//$amazon['product_description'];
        $rs['variation_options'] = $this->getOptionGroup($amazon['product_option'], $rs['item_name'], $rs['item_sku']);
        $rs['variation_mapping'] = [];
        $rs['relate_products'] = null;
        $rs['start_price'] = count($start_price) > 0 ? floatval(str_replace(',', '', trim($start_price[0]))) : 0;
        $rs['condition'] = isset($amazon['condition']) ? $amazon['condition'] : 'new';
        $rs['type'] = $this->store === AmazonProduct::STORE_JP ? AmazonProduct::TYPE_AMAZON_JP : AmazonProduct::TYPE_AMAZON_US;
        $rs['tax_fee'] = 0;
        $rs['store'] = $this->store;
        $rs['customer_feedback'] = ArrayHelper::getValue($amazon, 'product_review', []);
        $offers = null;
        if ($request->get_offer) {
            $offers = $this->getOffers($rs['item_sku']);
        }
        $check = false;
        $rs['providers'] = [];
        if (($auth = ArrayHelper::getValue($amazon, 'author'))) {
            $prov = [];
            $prov['name'] = trim($auth);
            $prov['image'] = '';
            $prov['website'] = '';
            $prov['location'] = '';
            $prov['rating_score'] = ArrayHelper::getValue($amazon, 'rate_count');
            $prov['rating_star'] = ArrayHelper::getValue($amazon, 'rate_star');
            $prov['positive_feedback_percent'] = null;
            $prov['condition'] = $rs['condition'] ?: 'new';
            $prov['fulfillment'] = null;
            $prov['is_free_ship'] = $rs['is_free_ship'];
            $prov['is_prime'] = $rs['is_prime'];
            $prov['price'] = $rs['sell_price'];
            $prov['shipping_fee'] = $rs['shipping_fee'];
            $prov['tax_fee'] = $rs['tax_fee'];
            $rs['providers'][] = $prov;
            $rs['provider'] = new Provider($prov);
        }
        if (!$this->isEmpty($offers)) {
            foreach ($offers as $offer) {
                if (in_array($offer['seller']['seller_name'], $this->sellerBlackLists)) {
                    $check = true;
                    continue;
                }
                $prov = [];
                $prov['name'] = trim($offer['seller']['seller_name']);
                $prov['image'] = '';
                $prov['website'] = '';
                $prov['location'] = '';
                $prov['rating_score'] = isset($offer['seller']['rate_count']) ? trim($offer['seller']['rate_count']) : '';
                $prov['rating_star'] = isset($offer['seller']['rate_star']) ? trim($offer['seller']['rate_star']) : '';
                $prov['positive_feedback_percent'] = isset($offer['seller']['positive']) ? trim($offer['seller']['positive']) : '';
                $prov['condition'] = trim($offer['condition']);
                $prov['fulfillment'] = $offer['fulfillment'];
                $prov['is_free_ship'] = $offer['is_free_ship'];
                $prov['is_prime'] = $offer['is_prime'];
                $prov['price'] = trim(str_replace(',', '', $offer['price']));
                $prov['shipping_fee'] = $offer['ship_fee'];
                $prov['tax_fee'] = $offer['tax_fee'];
                $rs['providers'][] = $prov;
            }
        }
        if (!count($rs['providers']) && $check) {
            $rs['sell_price'] = 0;
            [false, 'no provider valid'];
        }
        $rs['price_api'] = $rs['sell_price'];
        $rs['currency_api'] = 'USD';
        $rs['ex_rate_api'] = 1;
        if ($this->store === AmazonProduct::STORE_JP) {
            $this->ensureJpPrice($rs);
        }
        return [true, $rs];

    }

    public function getTechnicalSpecific($data)
    {
        if (!$data) {
            return [];
        }
        $dessc = [];
        foreach ($data as $datum) {
            if (isset($datum['value']) && is_array($datum['value'])) {
                foreach ($datum['value'] as $key => $value) {
                    if (is_string($value)) {
                        $dessc['value'][] = $value;
                    } elseif (is_array($value)) {
                        foreach ($value as $k => $v) {
                            $temp = [];
                            $temp['name'] = trim(str_replace(':', '', $k));
                            $temp['value'] = $value;
                            $res[] = $temp;
                        }
                    }
                }
            }
        }
        if ($dessc && count($dessc) > 0) {
            $dessc['name'] = 'Description';
            $res[] = $dessc;
            $res = array_reverse($res);
        }
        return $res;
    }

    /**
     * @param $product AmazonProduct
     * @param $params array
     * @param $request AmazonDetailRequest
     */
    private function updateProduct(&$product, $params, $request)
    {
        foreach ($product->variation_mapping as $item) {
            if ($item->variation_sku == $request->asin_id) {
                $item->variation_start_price = $params['retail_price'] ? $params['retail_price'] : 0;
                $item->variation_price = $params['sell_price'];
                break;
            }
        }
        $product->providers = self::getProviders($params['providers']);
        $product->deal_price = $params['deal_price'];
        $product->retail_price = $params['retail_price'];
        $product->start_price = $params['start_price'];
        $product->sell_price = $params['sell_price'];
        $product->condition = $params['condition'];
        $product->sell_price_special = $params['sell_price_special'];
        $product->shipping_fee = $params['shipping_fee'];
        $product->shipping_weight = $params['shipping_weight'] ? $params['shipping_weight'] : ($product->shipping_weight ? $product->shipping_weight : 0.50);
    }

    private function isValidResponse($response)
    {
        return isset($response['response']) ||
            (isset($response['response']['sell_price'])
                && count($response['response']['sell_price']) > 0
                && isset($response['response']['retail_price'])
                && count($response['response']['retail_price']) > 0
                && isset($response['response']['deal_price'])
                && count($response['response']['deal_price']) > 0
                && isset($response['response']['title'])
                && $response['response']['title'] !== null);
    }

    private function getOptionGroup($data, $title, $skuCurrent)
    {
        if (count($data) == 0) {
            return [];
        }
        $rs = [];
        foreach ($data as $item) {
            if (isset($item['name'])) {
                $temp['name'] = trim(str_replace(':', '', $item['name']));
                if ($temp['name'] && $item['value']) {
                    $temp['values'] = [];
                    $temp['value_current'] = '';
                    $temp['option_link'] = true;
                    $temp['images_mapping'] = [];
                    $temp['sku'] = [];
                    foreach ($item['value'] as $value) {
                        $value_tem = '';
                        if (isset($value['asin_color']) && $value['asin_color']) {
                            $value_tem = $value['asin_color'];
                        } else if (isset($value['asin_size']) && $value['asin_size']) {
                            $value_tem = $value['asin_size'];
                        } else if (isset($value['name'])) {
                            $value_tem = $value['name'];
                        }
                        if ($value_tem) {
                            $temp['values'][] = $value_tem;
                            if (isset($value['asin_id']) && $value['asin_id']) {
                                $temp['sku'][] = [
                                    'asin_id' => $value['asin_id'],
                                    'value_option' => $value_tem,
                                    'link' => WeshopHelper::generateUrlDetail('amazon', $title, $skuCurrent, $value['asin_id']),
                                ];
                                if ($value['asin_id'] == $skuCurrent) {
                                    $temp['value_current'] = $value_tem;
                                } else if (ArrayHelper::keyExists('asin_url', $value) && !ArrayHelper::getValue($value, 'asin_url')) {
                                    $temp['value_current'] = $value_tem;
                                }
                            }
                            elseif (!($sku = ArrayHelper::getValue($value,'asin_id')) && !($v = ArrayHelper::getValue($value,'value'))){
                                $temp['value_current'] = $value_tem;
                            }
                        }
                        if (isset($value['asin_images']) && $value['asin_images']) {
                            $temp['images_mapping'][] = [
                                'value' => $value_tem,
                                'images' => [
                                    [
                                        'thumb' => $value['asin_images'],
                                        'main' => $value['asin_images'],
                                    ]
                                ]
                            ];
                        }
                    }
                    $rs[] = $temp;
                }
            }
        }
        return $rs;

    }

    private function getVariationMapping($data, $detailImage)
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
                            'value' => $option['values'][$value]
                        ];
                        foreach ($imageDiff as $key => $diff) {
                            if ($diff == $option['id']) {
                                $imageDiff[$key] = $option['values'][$value];
                            }
                        }
                    }

                }
            }

            $imageDiff = implode(' ', $imageDiff);
            $dat['image_diff'] = $imageDiff;
            foreach ($detailImage['images'] as $key => $image) {
                if ($key == $imageDiff) {
                    $dat['images'] = $this->getItemImage($image);
                    break;
                }
            }

            $varian[] = $dat;
        }
        return $varian;
    }

    private function getItemImage($data)
    {
        $imgs = [];
        if (count($data) > 0)
            foreach ($data as $datum) {
                foreach ($datum as $img) {
                    $temp = [];
                    $temp['thumb'] = $img['thumb'];
                    $temp['main'] = $img['large'];
                    $imgs[] = $temp;
                }
            }
        return $imgs;
    }

    public function getProviders($data)
    {
        $rs = [];
        foreach ($data as $datum) {
            $rs[] = new \common\products\Provider($datum);
        }
        return $rs;
    }

    private function getRelateProduct($data)
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

    private function getSearchProduct($params)
    {
        $rs = [];
        foreach ($params as $param) {
            $item = [];
            $item['item_id'] = $param['asin_id'];
            $item['image'] = $param['images'][0];
            $item['is_prime'] = $param['is_prime'];

            $sell_price = is_string($param['sell_price']) ? [trim(str_replace('$', '', $param['sell_price']))] : $param['sell_price'];
            $sell_price = $sell_price ? $sell_price : [0];
            $sell_price = array_filter($sell_price, function ($price) {
                return (int)$price > 0;
            });
            $param['sell_price'] = $sell_price;
            $item['prices_range'] = [];
            if (count($param['sell_price']) > 1) {
                if (min($param['sell_price']) < max($param['sell_price'])) {
                    $item['prices_range'] = [min($param['sell_price']), max($param['sell_price'])];
                }
            }
            $item['sell_price'] = isset($param['sell_price'][0]) ? $this->convertStringToNumber($param['sell_price'][0]) : 0;
            $item['retail_price'] = count($param['retail_price']) > 0 ? $this->convertStringToNumber($param['retail_price'][0]) : 0;
            $item['rate_star'] = $param['rate_star'];
            $item['rate_count'] = $param['rate_count'];
            $item['item_name'] = $param['title'];
            $rs[] = $item;
        }

        return $rs;
    }

    public function convertStringToNumber($str)
    {
        $num = str_replace(' ', '', $str);
        $num = str_replace(',', '', $num);
        return $num;
    }

    private function getFilterNew($filter)
    {
        $rs = [];
        foreach ($filter as $item) {
            if (isset($item['value'])) {
//                if (count($item['value']) == 0 || strpos(strtolower($item['name']),'customer review'))
//                    continue;
                $temp = [];
                $temp['name'] = $item['name'];
                $temp['values'] = array_map(function ($tag) {
                    return array(
                        'count' => 1,
                        'is_selected' => false,
                        'new_param' => $tag['value'],
                        'param' => $tag['value'],
                        'value' => $tag['name']
                    );
                }, $item['value']);
                $rs[] = $temp;
            } else {
                foreach ($item as $v) {
                    if (isset($v['value'])) {
                        if (count($v['value']) == 0)
                            continue;
                        $temp = [];
                        $temp['name'] = $v['name'];
                        $temp['values'] = array_map(function ($tag) {
                            return array(
                                'count' => 1,
                                'is_selected' => false,
                                'new_param' => $tag['value'],
                                'param' => $tag['value'],
                                'value' => $tag['name']
                            );
                        }, $v['value']);
                        $rs[] = $temp;
                    } else {
                    }
                }
            }
        }
        return $rs;
    }

    protected function ensureJpPrice(&$response)
    {
        /** @var  $exRate \common\components\ExchangeRate */
        $exRate = Yii::$app->exRate;
        $response['currency_api'] = 'JPY';
        $response['ex_rate_api'] = $exRate->jpyToUsd(1);
        $response['sell_price'] = $exRate->jpyToUsd($response['sell_price']);
        $response['shipping_fee'] = $exRate->jpyToUsd($response['shipping_fee']);
        $response['retail_price'] = $exRate->jpyToUsd($response['retail_price']);
        $response['tax_fee'] = $exRate->jpyToUsd($response['tax_fee']);
        $response['deal_price'] = $exRate->jpyToUsd($response['deal_price']);
        $response['start_price'] = $exRate->jpyToUsd($response['start_price']);
        if (isset($response['sell_price_special']) && is_array($response['sell_price_special'])) {
            foreach ($response['sell_price_special'] as $k => $v) {
                $response['sell_price_special'][$k] = $exRate->jpyToUsd($v);
            }
        }
        if (isset($response['relate_products']) && is_array($response['relate_products'])) {
            foreach ($response['relate_products'] as $k => $v) {
                $response['relate_products'][$k]['sell_price'] = $exRate->jpyToUsd($v['sell_price']);
            }
        }

        if (isset($response['suggest_set_purchase']) && is_array($response['suggest_set_purchase'])) {
            foreach ($response['suggest_set_purchase'] as $k => $v) {
                foreach ($v['sell_price'] as $key => $val) {
                    $response['suggest_set_purchase'][$k]['sell_price'][$key] = $exRate->jpyToUsd($val);
                }
            }
        }

        if (isset($response['suggest_set_session']) && is_array($response['suggest_set_session'])) {
            foreach ($v['sell_price'] as $key => $val) {
                $response['suggest_set_session'][$k]['sell_price'][$key] = $exRate->jpyToUsd($val);
            }
        }

        foreach ($response['providers'] as $k => $v) {
            $response['providers'][$k]['price'] = $exRate->jpyToUsd($v['price']);
            $response['providers'][$k]['shipping_fee'] = $exRate->jpyToUsd($v['shipping_fee']);
            $response['providers'][$k]['tax_fee'] = $exRate->jpyToUsd($v['tax_fee']);
        }
    }
}
