<?php
/**
 * Created by PhpStorm.
 * User: Thaim
 * Date: 9/12/2017
 * Time: 10:34 AM
 */

namespace common\lib;


use common\components\amazon\AmazonApiV2Client;
use common\components\Product;
use common\models\amazon\AmazonSearchForm;
use common\models\db\Category;

class AmazonSearchProductGate
{

    public $keyword;

    /**
     * @param AmazonSearchForm $searchForm
     * @return \common\models\amazon\AmazonSearchResult|mixed|null
     */
    public static function parse(AmazonSearchForm $searchForm)
    {
        $attempts = 0;
        do {
            $attempts++;
            $resultAPI = AmazonApiV2Client::search($searchForm);
            if (isset($resultAPI['response']) || isset($result['total_product']))
                break;
        } while ($attempts < 3);

        if (!isset($resultAPI['response'])) return [];
        $result = $resultAPI['response'];

        if (!isset($result['total_product'])) return [];
        $data['products'] = self::getProduct($result['products']);
        $data['total_product'] = $result['total_product'] > 0 ? $result['total_product'] : ($result['total_page'] > 0 ? $result['total_page'] * count($data['products']) : count($data['products']));


        $result['categories'] = array_unique($result['categories']);
        $data['primary_empty'] = ($result['primary_empty']) ? 1 : 0;

//        if (($owner = $searchForm->store) === AmazonProduct::STORE_JP && count($alias = $result['categories']) > 0) {
//            self::collectAlias($alias, 'search', $owner);
//        }

        $cates = Category::find()->where([
            'OR',
            ['alias' => $result['categories']],
            ['path' => $result['categories']]
        ])->andWhere(['site' => $searchForm->store === Product::STORE_US ? strtolower(Product::TYPE_AMZON_US) : ($searchForm->store === Product::STORE_JP ? strtolower(Product::TYPE_AMZON_JP) : null)])
            ->select(['alias as category_id', 'name as category_name', 'origin_name as origin_name'])->asArray()->all();
        $data['categories'] = $cates;
        $data['sorts'] = $result['sorts'];
        $data['filters'] = self::getFilter($result['filters']);
        $data['total_page'] = $result['total_page'];
        if ($searchForm->store === Product::STORE_JP)
            return self::getPriceJP($data);
        return $data;
    }

    static function getPriceJP($rs)
    {
        foreach ($rs['products'] as $k => $v) {
            $rs['products'][$k]['sell_price'] = Website::JPYtoUSD($rs['products'][$k]['sell_price']);
            $rs['products'][$k]['retail_price'] = Website::JPYtoUSD($rs['products'][$k]['retail_price']);
            foreach ($rs['products'][$k]['prices_range'] as $key => $value) {
                $rs['products'][$k]['prices_range'][$key] = Website::JPYtoUSD($value);
            }
        }
        return $rs;
    }

    static function getProduct($products)
    {
        $rs = [];
        foreach ($products as $product) {
            $i = new \stdClass();
            $i->item_id = $product['asin_id'];
            $i->image = $product['images'][0];
            $i->is_prime = $product['is_prime'];

            $sell_price = [];
            foreach ($product['sell_price'] as $index => $price) {
                if ($price != 0) $sell_price[] = $price;
            }
            $product['sell_price'] = $sell_price;
            if (count($product['sell_price']) > 1) {
                if (min($product['sell_price']) < max($product['sell_price']))
                    $i->prices_range = [min($product['sell_price']), max($product['sell_price'])];
                else $i->prices_range = null;
            }
            $i->sell_price = isset($product['sell_price'][0]) ? $product['sell_price'][0] : '';
            $i->retail_price = count($product['retail_price']) > 0 ? $product['retail_price'][0] : null;
            $i->rate_star = $product['rate_star'];
            $i->rate_count = $product['rate_count'];
            $i->item_name = $product['title'];
//            $i->usTax ='';
//            $i->usShippingFee ='';
            $rs[] = (array)$i;
        }

        return $rs;
    }

    static function getFilter($filter)
    {
        $rs = [];
        foreach ($filter as $item) {
            if (count($item['values']) == 0)
                continue;
            $temp = [];
            $temp['name'] = $item['name'];
            $temp['values'] = $item['values'];
            $rs[] = $temp;
        }
        return $rs;
    }

    function getCategories($data)
    {
        $rs = [];
        $data = array_unique($data);
        foreach ($data as $datum) {
            $t = explode(":", $datum);
//            $arr = array();
//            $ref = &$arr;
//            foreach ($t as $key) {
//                $ref['category_id']=$key;
//                $ref['category_name']= self::getCategoryName($key);
//                $ref = &$ref['child_category'][];
//            }
//            $rs[] =$arr;
            $arr = [];
            $count_level = count($t);
            $arr['category_id'] = $t[$count_level - 1];
            $arr['category_name'] = self::getCategoryName($arr['category_id']);
//            $arr['category_path']=$datum;
            $rs[] = $arr;
        }
        return $rs;

    }

    function getCategoryName($alias)
    {
        $cate = Category::find()->select('name')->where(['alias' => $alias])->one();
        return $cate['name'];
    }

}
