<?php


namespace frontend\modules\amazon\controllers;

use common\lib\AmazonProductGate;
use common\products\BaseProduct;
use common\products\forms\ProductDetailFrom;
use Yii;
use yii\helpers\Url;

class ItemController extends AmazonController
{
    public function actionDetail($id)
    {
        $form = new ProductDetailFrom();
        $form->load($this->request->getQueryParams(), '');
        $form->id = $id;
        $form->type = 'amazon';
        $form->getOffer = false;
        if (($item = $form->detail()) === false) {
            return $this->render('@frontend/views/common/item_error', [
                'errors' => $form->getErrors()
            ]);
        }
        $this->site_title = Yii::t('frontend', '{name} | Product US Amazon', ['name' => $item->item_name]);
        $this->site_description = Yii::t('frontend', 'Buy the "{name}" product on US Amazon immediately via {store} to get the product within 7-15 days with many attractive offers. Shopping US Amazon, eBay easily.', ['name' => $item->item_name,'store' => $this->storeManager->store->name]);
        $this->site_image = isset($item->primary_images[0]) ? $item->primary_images[0]->main : Url::to('/img/no_image.png',true);

        return $this->render('index', [
            'item' => $item
        ]);

    }

    public function actionVariation()
    {
        $response = ['success' => false, 'message' => Yii::t('frontend', 'Failed'), 'content' => []];
        $form = new ProductDetailFrom();
        $post = Yii::$app->getRequest()->post();
        $form->load(Yii::$app->getRequest()->post(), '');
        $form->getOffer = false;
        if (!$form || $form->id) {
            $response['message'] = Yii::t('frontend', 'Can not resolve request');
        }
        $form->type = 'amazon';
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
//            $item->start_price = $item->sell_price + 10;
            $response['success'] = true;
            $response['message'] = Yii::t('frontend', 'Success');
            $contentPrice = '<div class="title-price">'.Yii::t('frontend','Price').'</div><strong class="text-danger">' . $this->storeManager->showMoney($item->getLocalizeTotalPrice()) . '</strong>';
            if ($item->start_price > $item->sell_price) {
                $contentPrice .= '<b class="old-price">' . $this->storeManager->showMoney($item->getLocalizeTotalStartPrice()) . '</b>';
//                $contentPrice .= '<span class="save">(Tiết kiệm: ' . WeshopHelper::showMoney($item->getLocalizeTotalStartPrice() - $item->getLocalizeTotalPrice(), 1, '') . 'đ)</span>';
            }
            $sellerMore = '';
            if ($item->providers && count($item->providers) > 1) {
                foreach ($item->providers as $provider) {
                    if ($provider->prov_id != $item->provider->prov_id) {
                        $temp = clone $item;
                        $temp->updateBySeller($provider->prov_id);
                        $sellerMore .= \frontend\widgets\item\SellerMoreWidget::widget(['provider' => $provider, 'item' => $temp, 'storeManager' => $this->storeManager]);
                    }
                }
            }
            $response['content'] = [
                'fees' => $fees,
                'sellerCurrentId' => $item->provider->prov_id,
                'sellerCurrentName' => $item->provider->name,
                'sellerMore' => $sellerMore,
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
    public function actionGetOffer($id) {
        Yii::$app->response->format = 'json';
        $form = new ProductDetailFrom();
        $form->load($this->request->getQueryParams(), '');
        $form->id = $id;
        $form->type = 'amazon';
        if (($item = $form->detail()) === false) {
            return ['success' => false];
        }
        if($item->providers){
            $txt = "";
            $current_provider = $item->provider;
            foreach ($item->providers as $provider) {
                if(!$current_provider || $provider->prov_id != $current_provider->prov_id){
                    $item->updateBySeller($provider->prov_id);
                    $rate_star_seller = floatval($provider->rating_star);
                    $rate_count_seller = $provider->rating_score ? $provider->rating_score : 0;
                    $rate_star_seller = $rate_star_seller > intval($rate_star_seller) ? intval($rate_star_seller).'-5' : intval($rate_star_seller);
                    $txt .= "<tr>" .
                        "<td><strong class='text-danger'>".$this->storeManager->showMoney($item->getLocalizeTotalPrice())."</strong>";
                    if($item->start_price && $item->start_price > $item->sell_price){
                        $txt .= "<br><b class='old-price'>".$this->storeManager->showMoney($item->getLocalizeTotalStartPrice())."</b>";
                    }
                    $txt .= "</td>";
                    $txt .= "<td><strong>".$provider->condition."</strong></td>";
                    $txt .= "<td>" .
                        "<div class='text-blue'>".$provider->name."</div>" .
                        "<div class='rate text-orange'>" .
                        "" .($rate_star_seller ? "<i class='a-icon a-icon-star a-star-".$rate_star_seller."   review-rating'></i>" : "").
                        "" .($provider->positive_feedback_percent ? "<u class='text-blue font-weight-bold'>".Yii::t('frontend','{positive}% positive',['positive' => $provider->positive_feedback_percent])."</u>" : "").
                        "" .($rate_count_seller ? "<br><u class='text-blue font-weight-bold'>(".Yii::t('frontend','{countRate} total rating',['countRate' => $rate_count_seller]).")</u>" : "").
                        "</div>" .
                        "</td>";
                    $txt .= "<td>" .
                        "<a href='javascript: void(0);' class='btn btn-amazon shortcut-payment'  data-role='buynow' data-seller='".$provider->prov_id."' style='border-radius: 0px'>".Yii::t('frontend','Buy now')."</a>" .
                        "<a href='javascript: void(0);' class='btn btn-outline-info shortcut-payment'  data-role='shopping' data-seller='".$provider->prov_id."' style='border-radius: 0px'>".Yii::t('frontend','Cart')."</a>" .
                        "</td>";
                    $txt .= "</tr>";
                }
            };
            return ['success' => true,'data' => ['content' => $txt]];
        }
        return ['success' => false];
    }
}
