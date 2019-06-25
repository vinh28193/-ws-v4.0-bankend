<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-07
 * Time: 09:06
 */


namespace common\products\amazon;

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
            print_r($request->getFirstErrors());
            die;
            return [false, $request->getFirstErrors()];
        }
//        $results = $this->searchInternalOld($request);
        $results = $this->searchInternal($request);
//        print_r($results);
//        die;
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
        if (!$request->validate()) {
            return [false, $request->getFirstErrors()];
        }

        $tokens = ["Check with `$request->store`, validated success"];
        $tokens[] = "refresh cache " . ($refresh === true ? 'true' : 'false');
        if (!$this->isEmpty($request->parent_asin_id) && $request->parent_asin_id !== $request->asin_id) {
            //Search ra san pham cha truoc de lay load sub url params, parent sku
            $tokens[] = "parent sku {$request->parent_asin_id} detected, try in case 1";
            $cloneRequest = clone $request;
            $cloneRequest->asin_id = $request->parent_asin_id;
            $cloneRequest->parent_asin_id = null;
            $results = $this->lookupInternal($cloneRequest);
//            if (!($results = $this->cache->get($cloneRequest->getCacheKey())) || $refresh) {
//                $results = $this->lookupInternal($cloneRequest);
//                $this->cache->set($cloneRequest->getCacheKey(), $results, $results[0] === true ? self::MAX_CACHE_DURATION : 0);
//            }
            print_r($results);
            die;
            list($ok, $response) = $results;
            $tokens[] = "response return :" . ($ok ? 'true' : 'false');
            if ($ok && is_array($response)) {
                /** @var  $product \common\products\amazon\AmazonProduct */
                $product = (new AmazonDetailResponse($this))->parser($response);
                //new sku cua san pham cha khac voi sku can tim kiem
                if ($product->item_id != $request->asin_id && !$request->is_first_load) {
                    $tokens[] = "product `{$product->item_id}` diff with `$request->asin_id`, load sub product is active";
                    if ($request->parent_asin_id != $product->parent_item_id) {
                        $tokens[] = "product parent sku `{$product->parent_item_id}` diff with request parent sku `$request->parent_asin_id`, `$product->parent_item_id` replaced  `$request->parent_asin_id`";
                        //gan lai gia tri parent_asin_id  neu khac form truyen len
                        $request->parent_asin_id = $product->parent_item_id;
                    }
                    //Gan gia tri load_sub_url
                    $request->load_sub_url = $product->load_sub_url;
                    $tokens[] = "register load sub url";
                    if (!($subs = $this->cache->get($request->getCacheKey())) || $refresh) {
                        $subs = $this->lookupInternal($request);
                        $this->cache->set($request->getCacheKey(), $subs, $subs[0] === true ? self::MAX_CACHE_DURATION : 0);
                    }
                    list($okSub, $productSub) = $subs;
                    $tokens[] = "load sub product, response return :" . ($okSub ? 'true' : 'false');
                    if ($okSub && is_array($productSub)) {
                        $productSub = (array)$productSub;
                        $tokens[] = "update product with sub product";
                        $this->updateProduct($product, $productSub, $request);
                    }

                }
                Yii::info(implode(", ", $tokens), __METHOD__);
                return [true, $product];
            }
            Yii::info(implode(", ", $tokens), __METHOD__);
            return [false, $response];
        } else {
            //Truong hop hop load sp lan dau
            $tokens[] = "single product request";
            $cloneRequest = clone $request;
            $cloneRequest->parent_asin_id = null;
            $cloneRequest->load_sub_url = null;
            $results = $this->lookupInternal($cloneRequest);
            print_r($results);
            die;
            if (!($results = $this->cache->get($cloneRequest->getCacheKey())) || $refresh) {
                $results = $this->lookupInternal($cloneRequest);
                $this->cache->set($cloneRequest->getCacheKey(), $results, $results[0] === true ? self::MAX_CACHE_DURATION : 0);
            }
            list($ok, $response) = $results;

            $tokens[] = "response return :" . ($ok ? 'true' : 'false');
            if ($ok && is_array($response)) {
                /** @var  $product \common\products\amazon\AmazonProduct */
                $product = (new AmazonDetailResponse($this))->parser($response);
                if ((($product->item_id != $request->asin_id) || (isset($product->parent_item_id) && $product->parent_item_id != $product->item_id)) && !$request->is_first_load) {
                    $tokens[] = "sub product detected";
                    $request->parent_asin_id = $product->parent_item_id;
                    $request->load_sub_url = $product->load_sub_url;
                    if (!($subs = $this->cache->get($request->getCacheKey())) || $refresh) {
                        $subs = $this->lookupInternal($request);
                        $this->cache->set($request->getCacheKey(), $subs, $subs[0] === true ? self::MAX_CACHE_DURATION : 0);
                    }
                    list($okSub, $productSub) = $subs;
                    $tokens[] = "load sub product, response return :" . ($okSub ? 'true' : 'false');
                    if ($okSub && is_array($productSub)) {
                        $productSub = (array)$productSub;
                        $tokens[] = "update product with sub product";
                        $this->updateProduct($product, $productSub, $request);
                    }
                }
                Yii::info(implode(", ", $tokens), __METHOD__);
                return [true, $product];
            }
            Yii::info(implode(", ", $tokens), __METHOD__);
            return [false, $response];
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
        $httpClient = $this->getHttpClient();
        $httpRequest = $httpClient->createRequest();
        $httpRequest->setFormat(Client::FORMAT_RAW_URLENCODED);
        $httpRequest->setMethod('POST');
        $httpRequest->setUrl($this->offerUrl);
        $httpRequest->setData([
            'store' => $this->store,
            'asin_id' => $itemId
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
     * @param $request AmazonSearchRequest
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    private function searchInternal($request)
    {
        print_r($request);die;
        $url = $this->baseUrl.'/'.$this->searchUrl.'?q='.urlencode($request->keyword).'&page='.$request->page;
        if($request->filter){
            $url .= '&filter='.urldecode($request->filter);
        }
        $curl = new curl\Curl();
        $response = $curl->get($url);
        $response = json_decode($response,true);
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
        foreach ($result['sorts'] as $value){
            $sorts = array_merge($sorts,$value);
        }
        $data['categories'] = array_map(function($tag) {
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

        $response = $curl->get($this->baseUrl.'/'.$this->asinsUrl.'/'.$request->asin_id);
        $response = json_decode($response,true);

        if (!$this->isValidResponse($response)) {
            return [false, 'can not send request'];
        }

//        } while ($attempts < 3);
//        if (!isset($response)) {
//            return [false, 'can not send request'];
//        }
        $amazon = $response['response'];
        $rs = [];
        $rs['categories'] = array_unique($amazon['node_ids']);
        $rs['item_id'] = $request->asin_id;
        $rs['item_sku'] = $request->asin_id;
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
        $rs['primary_images'] = $this->getItemImage($amazon['primary_images']);
        $rs['technical_specific'] = $amazon['specific_description'];
        $rs['variation_options'] = $this->getOptionGroup($amazon['sale_specifics'], $amazon['detail_images']);
        $rs['variation_mapping'] = $this->getVariationMapping($amazon['sale_specifics'], $amazon['detail_images']);
        $rs['relate_products'] = $this->getRelateProduct($amazon['suggest_products']);
        $rs['start_price'] = !empty($amazon["retail_price"]) ? ($amazon["retail_price"][0]) : 0.0;
        $rs['condition'] = isset($amazon['condition']) ? $amazon['condition'] : 'new';
        $rs['type'] = $this->store === AmazonProduct::STORE_JP ? AmazonProduct::TYPE_AMAZON_JP : AmazonProduct::TYPE_AMAZON_US;
        $rs['tax_fee'] = 0;
        $rs['store'] = $this->store;

//        $suggestSetCacheKey = "suggest_set_{$rs['item_sku']}";
//        if (!($suggestSets = $this->cache->get($suggestSetCacheKey))) {
        foreach ($amazon['suggest_sets'] as $suggestSet) {
            $key = $suggestSet['id'];
            if (($key == 'purchase' || $key == 'session') && count($suggestSet['asins']) > 0) {
                $suggestSets[$key] = $this->getAsins($suggestSet['asins']);
            }
        }
//            $this->cache->set($suggestSetCacheKey, $suggestSets, 3600);
//        }

        foreach (['purchase', 'session'] as $key) {
            $rs["suggest_set_$key"] = isset($suggestSets[$key]) ? $suggestSets[$key] : null;
        }

        if (!$request->is_first_load) {
//            $offersCacheKey = "offers_{$rs['item_sku']}";
//            if (!($offers = $this->cache->get($offersCacheKey))) {
            $offers = $this->getOffers($rs['item_sku']);
//                $this->cache->set($offersCacheKey, $offers, 3600);
//            }
            $check = false;
            $rs['providers'] = [];
            if (!$this->isEmpty($offers)) {
                foreach ($offers as $offer) {
                    if (in_array($offer['seller']['seller_name'], $this->sellerBlackLists)) {
                        $check = true;
                        continue;
                    }
                    $prov = [];
                    $prov['name'] = $offer['seller']['seller_name'];
                    $prov['image'] = '';
                    $prov['website'] = '';
                    $prov['location'] = '';
                    $prov['rating_score'] = $offer['seller']['rating_count'];
                    $prov['rating_star'] = $offer['seller']['rate_star'];
                    $prov['positive_feedback_percent'] = $offer['seller']['positive'];
                    $prov['condition'] = $offer['condition'];
                    $prov['fulfillment'] = $offer['fulfillment'];
                    $prov['is_free_ship'] = $offer['is_free_ship'];
                    $prov['is_prime'] = $offer['is_prime'];
                    $prov['price'] = $offer['price'];
                    $prov['shipping_fee'] = $offer['ship_fee'];
                    $prov['tax_fee'] = $offer['tax_fee'];
                    $rs['providers'][] = $prov;
                }
                $rs['sell_price'] = $offers[0]['price'];
                $rs['condition'] = $offers[0]['condition'];
                $rs['is_free_ship'] = $offers[0]['is_free_ship'];
                $rs['is_prime'] = $offers[0]['is_prime'];
                $rs['sell_price'] = $offers[0]['price'];
                $rs['shipping_fee'] = $offers[0]['ship_fee'];
                $rs['tax_fee'] = $offers[0]['tax_fee'];
            }
            if (!count($rs['providers']) && $check) {
                $rs['sell_price'] = 0;
                [false, 'no provider valid'];
            }
        }
        $rs['price_api'] = $rs['sell_price'];
        $rs['currency_api'] = 'USD';
        $rs['ex_rate_api'] = 1;
        if ($this->store === AmazonProduct::STORE_JP) {
            $this->ensureJpPrice($rs);
        }
        return [true, $rs];

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
        return isset($response['response']) || (count($response['response']['sell_price']) > 0 && count($response['response']['retail_price']) > 0 && count($response['response']['deal_price']) > 0 && $response['response']['title'] !== null);
    }

    private function getOptionGroup($data, $images)
    {
        if (count($data) == 0 || count($data['options']) == 0) {
            return [];
        }
        $rs = [];
        foreach ($data['options'] as $item) {
            $temp['name'] = $item['name'];
            $temp['values'] = $item['values'];
            $temp['images_mapping'] = [];
            if (count($images) > 0) {
                if (isset($images['diff_by'])) {
                    foreach ($images['diff_by'] as $diffBy) {
                        if ($diffBy == $item['id']) {
                            foreach ($item['values'] as $val) {
                                foreach ($images['images'] as $k => $v) {
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
                if (isset($images['diff_by'][0]) && $images['diff_by'][0] == $item['id']) {
                    foreach ($images['images'] as $k => $v) {
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

            $sell_price = $param['sell_price'];
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
            $item['sell_price'] = isset($param['sell_price'][0]) ? $param['sell_price'][0] : 0;
            $item['retail_price'] = count($param['retail_price']) > 0 ? $param['retail_price'][0] : 0;
            $item['rate_star'] = $param['rate_star'];
            $item['rate_count'] = $param['rate_count'];
            $item['item_name'] = $param['title'];
            $rs[] = $item;
        }

        return $rs;
    }

    private function getFilterNew($filter)
    {
        $rs = [];
        foreach ($filter as $item) {
            if(isset($item['value'])){
                if (count($item['value']) == 0 || strpos(strtolower($item['name']),'customer review'))
                    continue;
                $temp = [];
                $temp['name'] = $item['name'];
                $temp['values'] = array_map(function($tag) {
                    return array(
                        'count' => 1,
                        'is_selected' => false,
                        'new_param' => $tag['value'],
                        'param' => $tag['value'],
                        'value' => $tag['name']
                    );
                }, $item['value']);
                $rs[] = $temp;
            }else{
                foreach ($item as $v) {
                    if(isset($v['value'])){
                        if (count($v['value']) == 0)
                            continue;
                        $temp = [];
                        $temp['name'] = $v['name'];
                        $temp['values'] = array_map(function($tag) {
                            return array(
                                'count' => 1,
                                'is_selected' => false,
                                'new_param' => $tag['value'],
                                'param' => $tag['value'],
                                'value' => $tag['name']
                            );
                        }, $v['value']);
                        $rs[] = $temp;
                    }else{
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
        if(isset($response['sell_price_special']) && is_array($response['sell_price_special'])){
            foreach ($response['sell_price_special'] as $k => $v) {
                $response['sell_price_special'][$k] = $exRate->jpyToUsd($v);
            }
        }
        if(isset($response['relate_products']) && is_array($response['relate_products'])){
            foreach ($response['relate_products'] as $k => $v) {
                $response['relate_products'][$k]['sell_price'] = $exRate->jpyToUsd($v['sell_price']);
            }
        }

        if(isset($response['suggest_set_purchase']) && is_array($response['suggest_set_purchase'])){
            foreach ($response['suggest_set_purchase'] as $k => $v) {
                foreach ($v['sell_price'] as $key => $val) {
                    $response['suggest_set_purchase'][$k]['sell_price'][$key] = $exRate->jpyToUsd($val);
                }
            }
        }

        if(isset($response['suggest_set_session']) && is_array($response['suggest_set_session'])){
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
