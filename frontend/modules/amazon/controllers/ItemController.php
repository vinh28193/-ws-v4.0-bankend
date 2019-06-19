<?php


namespace frontend\modules\amazon\controllers;

use common\helpers\WeshopHelper;
use common\products\BaseProduct;
use common\products\forms\ProductDetailFrom;
use Yii;

class ItemController extends AmazonController
{
    public function actionDetail($id)
    {
        $form = new ProductDetailFrom();
        $form->load($this->request->getQueryParams(), '');
        $form->id = $id;
        $form->type = 'amazon';
        if (($item = $form->detail()) === false) {
            return $this->render('@frontend/views/common/item_error', [
                'errors' => $form->getErrors()
            ]);
        }
        $this->portalTitle = $item->item_name;
        $this->portalImage = $item->primary_images[0]->main;
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
        $form->type = 'amazon';
        if (($item = $form->detail()) === false) {
            $response['message'] = Yii::t('frontend', 'Failed');
            $response['content'] = $form->getErrors();
        } else {
            /** @var $item BaseProduct */
            $fees = [];
            foreach ($item->getAdditionalFees()->keys() as $key) {
                $fees[$key] = $item->getAdditionalFees()->getTotalAdditionalFees($key)[1];
            }
//            $item->start_price = $item->sell_price + 10;
            $response['success'] = true;
            $response['message'] = Yii::t('frontend', 'Success');
            $contentPrice = '<div class="title-price">'.Yii::t('frontend','Price').'</div><strong class="text-danger">' . $this->storeManager->showMoney($item->getLocalizeTotalPrice()) . '</strong>';
            if ($item->start_price) {
                $contentPrice .= '<b class="old-price">' . $this->storeManager->showMoney($item->getLocalizeTotalStartPrice()) . '</b>';
//                $contentPrice .= '<span class="save">(Tiết kiệm: ' . WeshopHelper::showMoney($item->getLocalizeTotalStartPrice() - $item->getLocalizeTotalPrice(), 1, '') . 'đ)</span>';
            }
            $response['content'] = [
                'fees' => $fees,
                'queryParams' => $post,
                'sellPrice' => $item->getLocalizeTotalPrice(),
                'startPrice' => $item->getLocalizeTotalStartPrice(),
                'salePercent' => $item->getSalePercent(),
                'contentPrice' => $contentPrice,
            ];
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
    }
}
