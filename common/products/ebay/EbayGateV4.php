<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 11:20
 */

namespace common\products\ebay;


use linslin\yii2\curl\Curl;
use Yii;
use Exception;
use common\products\BaseGate;
use yii\helpers\ArrayHelper;

class EbayGateV4 extends BaseGate
{

    public $product_url = 'product_id';
    /**
     * @param $params
     * @param bool $refresh
     * @return array|mixed
     * @throws \yii\base\InvalidConfigException
     * Search Pages Ebay of Weshop
     */
    public function search($params, $refresh = false)
    {
//        $refresh = true;
        $request = new EbaySearchRequest();
        $request->load($params, '');
        if (!$request->validate()) {
            return [false, $request->getFirstErrors()];
        }
//        $re = $this->searchRequest($request->params());
//        print_r($re);die;
        // ToDo Caches : Get Thanh cong moi Luu cache @Phuchc 8/6/2019
       if (!($response = $this->cache->get($request->getCacheKey())) || $refresh) {
            list($success, $response) = $this->searchRequest($request->params());
            if($success){
                $this->cache->set($request->getCacheKey(), $response, $success === true ? self::MAX_CACHE_DURATION : 0);
            }else{
                return [false, null];
            }
       }
        return [true, (new EbaySearchResponse($this))->parser($response)];
    }

    /**
     * @param $params
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    private function searchRequest($params)
    {
        $url = $this->baseUrl.'/'.$this->searchUrl.'?q='.$params['keywords'].'&page_id='.$params['page'];
        if(($filters = ArrayHelper::getValue($params,'aspectFilter')) && count($filters)){
            $value = '';
            $name = '';
            foreach ($filters as $filter){
                $value = count($filter['value']) ? $filter['value'][count($filter['value']) - 1] : '';
                if($value){
                    $name = $filter['name'];
                }
            }
            $url .= '&itemFilterKey='.$name.'&itemFilterValue='.$value;
        }
        if(($cate = ArrayHelper::getValue($params,'categoryId')) && count($cate)){
            $url .= '&categoryId='.$cate[0];
        }
        if(($sortOrder = ArrayHelper::getValue($params,'sortOrder')) && $sortOrder){
            $url .= '&sortKey='.$sortOrder;
        }
        $curl = new Curl();
        $response = $curl->get($url);
        $response = json_decode($response,true);
        if($curl->responseCode !== 200){
            return [false, $response];
        }
        try{
            if(!isset($response['data']['sorts']) || !$response['data']['sorts']){
                if(isset($response['data']['sort']) && $response['data']['sort']){
                    $response['data']['sorts'] = $response['data']['sort'];
                    unset($response['data']['sort']);
                }else{
                    $response['data']['sorts'] = [
                        'BestMatch' =>  'Featured',
                        'PricePlusShippingHighest' =>  'Price: High to Low',
                        'PricePlusShippingLowest' =>  'Price: Low to High',
                        'StartTimeNewest' =>  'Time Newest',
                    ];
                }
            }
            return [true, $response];
        }catch (Exception $exception ){
            Yii::error($exception);
            return [false, $response];
        }
    }

    /**
     * @param $condition
     * @param bool $refresh
     * @return array|mixed
     * @throws \yii\base\InvalidConfigException
     * Lấy details Ebay
     */
    public function lookup($condition, $refresh = false)
    {
        $request = new EbayDetailRequest();
        $request->keyword = $condition;
        // ToDo Caches : Get Thanh cong moi Luu cache @Phuchc 8/6/2019
        //if (!($response = $this->cache->get($request->getCacheKey())) || $refresh) {
            list($ok, $response) = $this->lookupRequest($request->params());
            //$this->cache->set($request->getCacheKey(), $response, $ok === true ? self::MAX_CACHE_DURATION : 0);
       // }
        return [true, (new EbayDetailResponse($this))->parser($response)];

    }

    /**
     * @param $params
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function lookupRequest($params)
    {
        $url = $this->baseUrl.'/'.$this->product_url.'/'.$params;
        $curl = new Curl();
        $response = $curl->get($url);
        $response = json_decode($response,true);
        if($curl->responseCode !== 200){
            return [false, $response];
        }
        return [true, $response];
    }
}
