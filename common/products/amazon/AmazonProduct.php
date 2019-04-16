<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-29
 * Time: 17:58
 */

namespace common\products\amazon;


use common\products\BaseProduct;

class AmazonProduct extends BaseProduct
{

    const STORE_US = 'amazon.com';
    const STORE_JP = 'amazon.co.jp';

    public $store = self::STORE_US;

    public $manufacturer_description;
    public $best_seller;
    public $sort_desc;
    public $sell_price_special;
    public $load_sub_url;

    protected function generateOriginLink()
    {
        $source = self::STORE_US;
        if($this->type === self::TYPE_AMZON_JP){
            $source = self::STORE_JP;
        }
        $id = strtoupper($this->item_id);
        $this->item_origin_url = "https://$source/gp/product/$id?ie=UTF8&tag=wp034-20&camp=1789&linkCode=xm2&creativeASIN=$id";
        return $this->item_origin_url;
    }

    public function getSellPriceSpecial()
    {
        $price_specials = $this->sell_price_special;
        if (count($price_specials) > 0) {
            $sell_special = [];
            foreach ($price_specials as $price_special) {
                $calcfee = $this->getStoreManager()->showMoney($price_special * $this->getExchangeRate());
                array_push($sell_special, $calcfee);
            }

            echo implode(" - ", $sell_special);
        }
        return false;
    }
}