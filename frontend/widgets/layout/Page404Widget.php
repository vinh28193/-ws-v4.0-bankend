<?php


namespace frontend\widgets\layout;


use common\products\ebay\EbayGateV4;
use common\products\RelateProduct;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class Page404Widget extends Widget
{
    public function run()
    {
        $relate_product_rs = EbayGateV4::getProductTopView();
        $relate_product = isset($relate_product_rs['data']) ? ArrayHelper::getValue($relate_product_rs['data'], 'item') : [];
        $products = RelateProduct::setRelateProducts($relate_product);
        return $this->render('page_404',['productSuggest' => $products]);
    }
}