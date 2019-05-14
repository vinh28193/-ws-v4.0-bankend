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
        $form->load($this->request->getQueryParams(),'');
        $form->id = $id;
        $form->type = 'amazon';
        if (($item = $form->detail()) === false) {
            return $this->render('@frontend/views/common/item_error', [
                'errors' => $form->getErrors()
            ]);
        }
        return $this->render('index', [
            'item' => $item
        ]);

    }

    public function actionVariation()
    {
        $response = ['success' => false, 'message' => 'can not call', 'content' => []];
        $form = new ProductDetailFrom();
        $post = Yii::$app->getRequest()->post();
        if ($form->load(Yii::$app->getRequest()->post(), '')) {
            $response['message'] = 'can not resolve request';
        }
        $form->type = 'amazon';
        if (($item = $form->detail()) === false) {
            $response['message'] = 'failed';
            $response['content'] = $form->getErrors();
        } else {
            /** @var $item BaseProduct */
            $fees = [];
            foreach ($item->getAdditionalFees()->keys() as $key) {
                $fees[$key] = $item->getAdditionalFees()->getTotalAdditionFees($key)[1];
            }
//            $item->start_price = $item->sell_price + 10;
            $response['success'] = true;
            $response['message'] = 'success';
            $contenPrice = '<strong class="text-orange">' . WeshopHelper::showMoney($item->getLocalizeTotalPrice(), 1, '') . '<span class="currency">đ</span></strong>';
            if ($item->start_price) {
                $contenPrice .= '<b class="old-price">' . WeshopHelper::showMoney($item->getLocalizeTotalStartPrice(), 1, '') . '<span class="currency">đ</span></b>';
                $contenPrice .= '<span class="save">(Tiết kiệm: ' . WeshopHelper::showMoney($item->getLocalizeTotalStartPrice() - $item->getLocalizeTotalPrice(), 1, '') . 'đ)</span>';
            }
            $response['content'] = [
                'fees' => $fees,
                'queryParams' => $post,
                'sellPrice' => WeshopHelper::showMoney($item->getLocalizeTotalPrice(), 1, ''),
                'startPrice' => $item->start_price ? WeshopHelper::showMoney($item->getLocalizeTotalStartPrice(), 1, '') : null,
                'savePrice' => $item->start_price ? WeshopHelper::showMoney($item->getLocalizeTotalStartPrice() - $item->getLocalizeTotalPrice(), 1, '') : null,
                'salePercent' => $item->getSalePercent(),
                'contentPrice' => $contenPrice,
            ];
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
    }
}