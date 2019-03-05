<?php
/**
 * Created by PhpStorm.
 * User: quannd
 * Date: 5/3/17
 * Time: 3:12 PM
 */

namespace common\models\amazon;


use common\components\Cache;
use common\components\RestClient;
use common\models\model\AmazonApiSearchLog;
use common\models\service\SiteService;
use yii\helpers\Url;

class AmazonClient
{
  /**
   * @return string
   */
  private static function getClientUrl()
  {
    return 'http://45.32.87.147/';
  }

  /**
   * @return string
   */
  private static function getSearchUrl()
  {
    return static::getClientUrl() . 'amazon/search';
  }

  /**
   * @return string
   */
  private static function getGetUrl()
  {
    return static::getClientUrl() . 'amazon/get';
  }

  static function postData(AmazonSearchForm $amazonSearchForm, $search = true, &$debug = true)
  {
    $data = RestClient::call(
      $search ? static::getSearchUrl() : static::getGetUrl(),
      $amazonSearchForm->getVars(),
      7, false, $debug
    );
    return $data;
  }


  /**
   * @param $asinId
   * @param null $parentAsinId
   * @param bool $cacheOnly
   * @return \common\models\amazon\AmazonProduct|null
   */
  static function getProduct($asinId, $parentAsinId = null, $cacheOnly = false)
  {
    $nocache = \Yii::$app->request->get('clear') == 'yess';
    $cacheKey = 'nap-asin-' . $asinId . (empty($parentAsinId) ? '-m' : '-s');
    $product = null;
    if (!$nocache) {
      $product = Cache::get($cacheKey);
    }
    if (!empty($product)) {
      return $product;
    } else if ($cacheOnly) {
      return null;
    }

    if (empty($parentAsinId)) {
      $searchForm = new AmazonSearchForm();
      $searchForm->asin_id = $asinId;
      $product = new AmazonProduct();
      $debug = true;

      $product->loadData(static::postData($searchForm, false, $debug)->response);

      if (!empty($debug)) {
        static::logRequest('GET_NEW', $debug, $searchForm, $product);
      }

      $product->initSuggestProduct();
      Cache::set($cacheKey, $product, 86400);
      return $product;
    }

    $product = static::getProduct($parentAsinId);

    $debug = true;

    $searchForm = new AmazonSearchForm();
    $searchForm->asin_id = $asinId;
    $searchForm->parent_asin_id = $parentAsinId;
    $searchForm->load_sub_url = $product->load_sub_url;
    $product->loadSubProduct(static::postData($searchForm, false, $debug)->response);

    if (!empty($debug)) {
      static::logRequest('GET_NEW', $debug, $searchForm, $product);
    }


    Cache::set($cacheKey, $product, 86400);
    return $product;
  }

  static function searchProduct($keyword = null, $categoryId = null, $param = null, $sort = null, $maxPrice = null, $minPrice = null, $page = 1)
  {

    $searchForm = new AmazonSearchForm();
    $searchForm->keyword = @trim($keyword);
    $searchForm->node_ids = $categoryId;
    $searchForm->rh = $param;
    $searchForm->sort = $sort;
    $searchForm->max_price = $maxPrice;
    $searchForm->min_price = $minPrice;
    $searchForm->page = $page;

    $nocache = \Yii::$app->request->get('clear') == 'yess';
    $cacheKey = json_encode($searchForm->getVars());
    $searchResult = null;
    if (!$nocache) {
      $searchResult = Cache::get($cacheKey);
      if (!empty($searchResult) && $searchResult->total_product > 0) {
        return $searchResult;
      }
    }

    $searchResult = new AmazonSearchResult();

    $debug = true;

    $searchResult->loadData(static::postData($searchForm, true, $debug)->response);

    if (!empty($debug)) {
      static::logRequest('SEARCH_NEW', $debug, $searchForm, $searchResult);
    }
    if ($searchResult->total_product > 0){
      Cache::set($cacheKey, $searchResult, 86400);
    }
    return $searchResult;
  }

  static function logRequest($type = 'SEARCH_NEW', $debug, $searchForm, $result)
  {
    $storeData = SiteService::getStore(true);

    if ($storeData->runMode != 2) {
      return false;
    }

    $log = new AmazonApiSearchLog();
    if ($type == 'SEARCH_NEW') {
      $log->item_number = count($result->products);
      $log->search_keyword = $searchForm->keyword;
      $log->page = $searchForm->page;
      $log->category_id = $searchForm->node_ids;
    } else {
      $log->product_id = $searchForm->asin_id;
      $log->price_range = json_encode($result->sell_price);
      $log->search_keyword = $result->title;
    }
    $log->response_time = $debug['total_time'];
    $log->http_status = $debug['http_code'];
    $log->store_id = $storeData->id;
    $log->type = $type;
    $log->url = Url::current([], true);
    $log->save(false);
  }

}