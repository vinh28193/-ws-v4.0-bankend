<?php
namespace weshop\modules\landing\views\widgets\LandingProductBannerCenter;
use weshop\views\weshop\widgets\BaseWidget;

class LandingProductBannerCenterWidget extends BaseWidget{
    public $block = [];

    public function run(){
        $groupProduct = [];

        $products = $this->block['products'];
        $website = $this->getWebsite();
        $exchangeRate = $website->getExchangeRate();


        foreach ($products as $key=>$val){
            if(!empty($val->start_price) && !empty($val->sell_price) && $val->start_price !=0 && $val->sell_price !=0 ){
                $products[$key]->rate_count             = round (100-($val->sell_price/$val->start_price)*100);
            }

            $products[$key]->sell_price = $website->showMoney($val->sell_price*$exchangeRate);
            $products[$key]->calculated_sell_price = $website->showMoney($val->calculated_sell_price * $exchangeRate);
            if(isset($val->start_price)){
                $products[$key]->start_price            = $website->showMoney($val->start_price * $exchangeRate);
            }
            if(!empty($val->calculated_start_price)){
                $products[$key]->calculated_start_price = $website->showMoney($val->calculated_start_price * $exchangeRate);
            }


        }
        
        return $this->render("LandingProductBannerCenterView", [
            'block'=>$this->block['block'],
            'products'=>$products,
            'exchangeRate'=>$exchangeRate,
        ]);
    }
}
?>