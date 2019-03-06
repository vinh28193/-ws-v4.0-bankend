<?php
/**
 * Created by PhpStorm.
 * User: quannd
 * Date: 5/3/17
 * Time: 3:13 PM
 */

namespace common\models\amazon;


class AmazonSearchResult
{
  public $products = [], $filters = [], $start_product = 1, $total_page = 1, $total_product = 0, $categories = [];

  function loadData($data)
  {
    if (!is_array($data)) {
      $data = get_object_vars($data);
    }
    foreach ($data as $k => $v) {
      if (property_exists($this, $k)) {
        $this->$k = $v;
      }
    }

    foreach ($this->products as $k => $product) {
      $modelProduct = new AmazonProduct();
      $modelProduct->loadData($product, false);
      $this->products[$k] = $modelProduct;
    }
    $categories = [];
    foreach ($this->categories as $category) {
      $categories = array_merge($categories, explode(':', $category));
    }

    $this->categories = array_unique($categories);

    foreach ($this->filters as $k => $filter) {
      if ($filter->name == 'Avg. Customer Review') {
        $i = 4;
        foreach ($filter->values as $vk => $vv) {
          $this->filters[$k]->values[$vk]->value = $i . ' & Up';
          $i--;
        }
      }
    }
    if ($this->total_product == 0) {
      $this->total_product = $this->total_page * count($this->products);
    }
  }
}