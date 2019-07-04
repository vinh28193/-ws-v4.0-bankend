<?php


namespace frontend\modules\ebay\controllers;

use common\helpers\WeshopHelper;
use common\lib\EbayProductGate;
use common\products\BaseProduct;
use common\products\ebay\EbayGateV4;
use common\products\RelateProduct;
use Yii;
use common\products\forms\ProductDetailFrom;
use  frontend\modules\favorites\controllers\FavoriteObject as FavoriteObject;
use  frontend\modules\favorites\controllers\FavoriteObjectQueue as Favorite;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


class ItemController extends EbayController
{


    public function actionDetail($id)
    {
        $form = new ProductDetailFrom();
        $form->load($this->request->getQueryParams(), '');
        $form->id = $id;
        $form->type = 'ebay';
        $item = $form->detail();
        if (Yii::$app->request->isPjax) {
            if ($item === false) {
                return $this->renderAjax('@frontend/views/common/item_error', [
                    'errors' => $form->getErrors()
                ]);
            }
            return $this->renderAjax('index', [
                'item' => $item
            ]);
        }
        if ($item === false) {
            return $this->render('@frontend/views/common/item_error', [
                'errors' => $form->getErrors()
            ]);
        }
        $this->site_title = Yii::t('frontend', '{name} | Product eBay', ['name' => $item->item_name]);
        $this->site_description = Yii::t('frontend', 'Buy the "{name}" product on eBay immediately via Weshop to get the product within 7-15 days with many attractive offers. Shopping US Amazon, eBay easily.', ['name' => $item->item_name]);
        $this->site_image = isset($item->primary_images[0]) ? $item->primary_images[0]->main : Url::to('/img/no_image.png',true);

//        $category = $item->getCategory();

        //Load sau khi load xong content
//        $relate_product_rs = EbayProductGate::paserSugget($item->item_id, $category ? [$category->alias] : []);
//        $relate_product = isset($relate_product_rs['data']) ? ArrayHelper::getValue($relate_product_rs['data'], 'item') : [];
//        $item->relate_products = RelateProduct::setRelateProducts($relate_product);
        return $this->render('index', [
            'item' => $item
        ]);

    }


    public function actionVariation()
    {
        $response = ['success' => false, 'message' => Yii::t('frontend', 'Failed'), 'content' => []];
        $form = new ProductDetailFrom();
        $post = Yii::$app->getRequest()->post();
        if ($form->load(Yii::$app->getRequest()->post(), '')) {
            $response['message'] = Yii::t('frontend', 'Can not resolve request');
        }
        $form->type = 'ebay';
        if (($item = $form->detail()) === false) {
            $response['message'] = Yii::t('frontend', 'Failed');
            $response['content'] = $form->getErrors();
        } else {
            /** @var $item BaseProduct */
            $fees = [];
            foreach ($item->getAdditionalFees()->keys() as $key) {
                $fees[$key.'_text'] = $this->storeManager->showMoney($item->getAdditionalFees()->getTotalAdditionalFees($key)[1]);
                $fees[$key] = $item->getAdditionalFees()->getTotalAdditionalFees($key)[1];
            }
            $item->getInternationalShipping();
            $response['success'] = true;
            $response['message'] = Yii::t('frontend', 'Success');
            $contentPrice = '<div class="title-price">'.Yii::t('frontend','Price').'</div><strong class="text-danger">' . $this->storeManager->showMoney($item->getLocalizeTotalPrice()) . '</strong>';
            if ($item->start_price > $item->sell_price) {
                $contentPrice .= '<b class="old-price">' . $this->storeManager->showMoney($item->getLocalizeTotalStartPrice()) . '</b>';
//                $contentPrice .= '<span class="save">(Tiết kiệm: ' . WeshopHelper::showMoney($item->getLocalizeTotalStartPrice() - $item->getLocalizeTotalPrice(), 1, '') . 'đ)</span>';
            }
            $response['content'] = [
                'sellerMore' => '',
                'sellerCurrentName' => '',
                'fees' => $fees,
                'queryParams' => $post,
                'sellPrice_origin' => $item->getSellPrice(),
                'sellPrice' => $item->getLocalizeTotalPrice(),
                'startPrice' => $item->getLocalizeTotalStartPrice(),
                'salePercent' => $item->getSalePercent(),
                'contentPrice' => $contentPrice,
            ];
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
    }
    public function actionSuggest(){
        $sku = Yii::$app->request->post('sku');
        $relate_product_rs = EbayGateV4::getProductSuggest($sku);
        $relate_product = isset($relate_product_rs['data']) ? ArrayHelper::getValue($relate_product_rs['data'], 'item') : [];
        $products = RelateProduct::setRelateProducts($relate_product);
        $content = '';
        foreach ($products as $product){
            $content .= \frontend\widgets\item\RelateProduct::widget(['portal' => 'ebay' , 'product' => $product]);
        }
        Yii::$app->response->format = 'json';
        return ['success' => true,'content' => $content];
    }
    public function actionTopView(){
        $relate_product_rs = EbayGateV4::getProductTopView();
        $relate_product = isset($relate_product_rs['data']) ? ArrayHelper::getValue($relate_product_rs['data'], 'item') : [];
        $products = RelateProduct::setRelateProducts($relate_product);
        $content = '';
        foreach ($products as $product){
            $content .= \frontend\widgets\item\RelateProduct::widget(['portal' => 'ebay' , 'product' => $product]);
        }
        Yii::$app->response->format = 'json';
        return ['success' => true,'content' => $content];
    }
}
