<?php
/**
 * Created by PhpStorm.
 * User: quannd
 * Date: 5/3/17
 * Time: 3:20 PM
 */

namespace common\models\amazon;


use common\components\Product;

class AmazonSearchForm
{
  public $store = Product::STORE_US;
  public $node_ids, $keyword, $rh, $page = 1, $min_price, $max_price, $sort, $item_name; //for search product
  public $asin_id, $parent_asin_id, $load_sub_url,$is_first_load = false; //for get product

  function getVars()
  {
    if ($this->sort == 'relevancerank') {
      $this->sort = '';
    }

    $originDatas = get_object_vars($this);
//    if (empty($this->asin_id)) {
//      return $originDatas;
//    }

    $data = [];
    foreach ($originDatas as $key => $originData) {
      if (!empty($originData)) {
        $data[$key] = strval($originData);
      }
    }
    return $data;
  }
}