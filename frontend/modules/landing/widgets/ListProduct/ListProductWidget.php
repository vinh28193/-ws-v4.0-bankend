<?php
namespace landing\widgets\ListProduct;
use common\products\BaseProduct;
use landing\LandingWidget;

class ListProductWidget extends LandingWidget
{
    public $block = [];

    public function run()
    {
        $groupProduct = [];
        $products = [];
        if(!empty($this->block['products'])){
            $products = $this->block['products'];
        }

        $website = $this->getWebsite();
        $exchangeRate = $website->getExchangeRate();

        if(!empty($products)){
            foreach ($products as $key=>$val){
                $product_base = new BaseProduct([
                    'sell_price' => $val['calculated_sell_price'],
                    'start_price' => $val['start_price'],
                    'isInitialized' => true,
                ]);
                $products[$key]['calculated_sell_price'] = $website->showMoney($product_base->getLocalizeTotalPrice());
                if(isset($val['start_price']) && $val['start_price'] > $val['start_price']){
                    $products[$key]['calculated_start_price'] = $website->showMoney($product_base->getLocalizeTotalStartPrice());
                }else{
                    $products[$key]['calculated_start_price'] = 0;
                }
//                if($val['start_price']!=null && $val['start_price'] >0 ){
//                    $products[$key]['rate_count']   = 100-($val['sell_price']/$val['start_price'])*100;
//                }

            }
        }
        
        return $this->render("ListProductView", [
            'block'=>$this->block['block'],
            'products'=>$products,
            'exchangeRate'=>$exchangeRate,
        ]);
    }
}
?>