<?php
/**
 * Created by PhpStorm.
 * User: Panda
 * Date: 11/28/2017
 * Time: 4:18 PM
 */

namespace common\components\ebay;


use common\lib\EbaySearchForm;
use common\models\service\SiteService;
use linslin\yii2\curl\Curl;
use yii\httpclient\Client;

class EbayApiV3Client
{

    public static function Search(EbaySearchForm $searchForm)
    {
        $post = new \stdClass();
        $post->keywords = $searchForm->keywords ? $searchForm->keywords : '';
        $post->page = $searchForm->page ? $searchForm->page : 1;
        $post->itemsPerPage = 36;
        $cate = [];
        if ($searchForm->categories != null && !is_array($searchForm->categories)) {
            $cate[] = $searchForm->categories;
        } else {
            $cate = $searchForm->categories ? $searchForm->categories : [];
        }
        $post->categoryId = $cate;
        $post->sortOrder = $searchForm->order;
        $post->aspectFilter = $searchForm->aspectFilters;
        $post->itemFilter = $searchForm->itemFilters;
        $itemFilters = [];
        if (!empty($searchForm->type)) {
            $type = [
                'name' => 'ListingType',
                'value' => $searchForm->type == 1 ? ['Auction', 'AuctionWithBIN'] : ['FixedPrice', 'StoreInventory']
            ];
            $itemFilters[] = $type;
        }

        if (!empty($searchForm->max_price)) {
            $maxPrice = [
                'name' => 'MaxPrice',
                'value' => [strval($searchForm->max_price)]
            ];
            $itemFilters[] = $maxPrice;
        }
        if (!empty($searchForm->min_price)) {
            $maxPrice = [
                'name' => 'MinPrice',
                'value' => [strval($searchForm->min_price)]
            ];
            $itemFilters[] = $maxPrice;
        }
        if (!empty($searchForm->sellers)) {
            $seller = [
                'name' => 'Seller',
                'value' => !is_array($searchForm->sellers) ? [$searchForm->sellers] : $searchForm->sellers,
            ];
            $itemFilters[] = $seller;
        }
        if (!empty($itemFilters)) {
            $post->itemFilter = array_merge($post->itemFilter, $itemFilters);
        }

        if (!empty($searchForm->itemsPerPage)) {
            $post->itemsPerPage = $searchForm->itemsPerPage;
        }
        $rs = self::callApi('search', $post);
        return $rs;
    }

    public static function SearchFeed(EbaySearchForm $searchForm)
    {
        $post = new \stdClass();
        $post->keywords = $searchForm->keywords;
        $post->page = $searchForm->page ? $searchForm->page : 1;
        //$post->itemFilter = $searchForm->itemFilters;
        $cate = [];
        if ($searchForm->categories != null && !is_array($searchForm->categories)) {
            $cate[] = $searchForm->categories;
        } else {
            $cate = $searchForm->categories ? $searchForm->categories : [];
        }
        $post->categoryId = $cate;
        $itemFilters = [];

        if (!empty($searchForm->max_price)) {
            $maxPrice = [
                'name' => 'MaxPrice',
                'value' => [strval($searchForm->max_price)]
            ];
            $itemFilters[] = $maxPrice;
        }
        if (!empty($searchForm->min_price)) {
            $maxPrice = [
                'name' => 'MinPrice',
                'value' => [strval($searchForm->min_price)]
            ];
            $itemFilters[] = $maxPrice;
        }
        if (!empty($searchForm->itemsPerPage)) {
            $post->itemsPerPage = $searchForm->itemsPerPage;
        }

//        if (!empty($itemFilters)) {
//            $post->itemFilter = array_merge($post->itemFilter, $itemFilters);
//        }

        $rs = self::callApi('search', $post);
        return $rs;
    }

    public static function GetProduct($id)
    {
        $url = 'product?id=' . $id;
        $rs =  self::callApi($url);
        if(empty($rs['success'])){
            $rstemp = json_decode($rs,true);
        }
//        $store = SiteService::getStoreId();
        if($rstemp['success']== true){
            $dataLog['status'] = 200;
        }else{
            $dataLog['status'] = 500;
        }
//        $dataLog['action'] = \Yii::$app->controller->id;
//        $dataLog['request'] = $url;
//        $dataLog['sku'] = $id;
//        $dataLog['store_id'] = $store;
//        $dataLog['provider'] = 'EBAY';
//        //$dataLog['respone'] = ($rs);
//        $dataLog['andress'] = !empty($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'127.0.0.1';
//        Logging::LogProduct($dataLog);


        return $rs;
    }

    static function callApi($url, $data = null)
    {
        if(!is_string($data)){
            $data_string = json_encode((array)$data);
        }else{
            $data_string = $data;
        }

        $ebay_api = isset(\Yii::$app->params['ebay-api']) ? (\Yii::$app->params['ebay-api']) : 'http://ebay.api/v3';
        $url = $ebay_api . '/' . $url;

        $curl = new Curl();
        $rs = $curl->setRawPostData($data_string)->setHeaders(['Content-Type' => 'application/json'])->post($url);
        if($curl->responseCode == 200){
            return $rs;
        }else{
            \Yii::info($rs);
            return null;
        }

    }

    /**
     * @param $id
     * @param array $category
     * @param null $location
     */
    public static function GetSuggetItem($id, $category = [], $location = 'EBAY-US')
    {
        $url = 'suggest/similarItem';
        $post = new \stdClass();
        $post->itemId = $id;
        $post->categoryIds = $category;
        $post->location = $location;

        $rstemp = null;
        $rs = self::callApi($url, (array)$post);
        if (empty($rs['success'])) {
            $rstemp = json_decode($rs, true);
        }
        return $rstemp;
    }

    public static function GetMostWatch($cat){
        $url = 'suggest/mostWatch';
        $post = new \stdClass();
        $post->categoryId = $cat;
        $rs = self::callApi($url, $post);

        if (empty($rs['success'])) {
            $rstemp = json_decode($rs, true);
        }
        return $rstemp['data'];
    }
}