<?php

namespace landing\widgets\LandingCrouselProductThumbNail;

use landing\LandingWidget;

class LandingProductCarouselThumbNailWidget extends LandingWidget
{
    public $block = [];

    public function run()
    {
        $products = [];
        if (!empty($this->block['products'])) {
            $products = $this->block['products'];
        }
        $website = $this->getWebsite();
        $exchangeRate = $website->getExchangeRate();


        foreach ($products as $key => $val) {
            $products[$key]->sell_price = $website->showMoney($val->sell_price * $exchangeRate);
            $products[$key]->calculated_sell_price = $website->showMoney($val->calculated_sell_price * $exchangeRate);
            if (isset($val->start_price)) {
                $products[$key]->start_price = $website->showMoney($val->start_price * $exchangeRate);
                $products[$key]->calculated_start_price = $website->showMoney($val->calculated_start_price * $exchangeRate);
                $products[$key]->rate_count = 100 - ($val->sell_price / $val->start_price) * 100;
            }
        }

        $countProduct = count($products);
        $countPage = ceil($countProduct / 3);
        $groupProduct = [];
        $page = 1;
        $groupProduct[$page] = [];

        foreach ($products as $key => $val) {
            if ((count($groupProduct[$page]) < 3) || !isset($groupProduct[$page])) {
                $groupProduct[$page][] = $val;
            } else {
                $page++;
                $groupProduct[$page] = [];
            }
        }

//        echo '<pre>';
//        print_r($groupProduct);
//        echo '</pre>';

//        return $this->render("LandingProductCarouselThumbNailView", [
//            'block'=>$this->block['block'],
//            'products'=>$products,
//            'exchangeRate'=>$exchangeRate,
//            'groupProduct'=>$groupProduct,
//        ]);
    }
}

?>