<?php


namespace frontend\modules\amazonJp\controllers;

use common\helpers\WeshopHelper;
use Yii;
use common\products\BaseProduct;
use common\products\forms\ProductDetailFrom;


class ItemController extends AmazonJpController
{
    public function actionDetail($id)
    {
        $form = new ProductDetailFrom();
        $form->id = $id;
        $form->type = 'amazon-jp';
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
        $form->type = 'amazon-jp';
        if (($item = $form->detail()) === false) {
            $response['message'] = 'failed';
            $response['content'] = $form->getErrors();
        } else {
            /** @var $item BaseProduct */
            $fees = [];
            foreach ($item->getAdditionalFees()->keys() as $key) {
                $fees[$key] = $item->getAdditionalFees()->getTotalAdditionFees($key)[1];
            }
            $response['success'] = true;
            $response['message'] = 'success';
            $contentPrice = '<strong class="text-orange">' . WeshopHelper::showMoney($item->getLocalizeTotalPrice(), 1, '') . '<span class="currency">đ</span></strong>';
            if ($item->start_price) {
                $contentPrice .= '<b class="old-price">' . WeshopHelper::showMoney($item->getLocalizeTotalStartPrice(), 1, '') . '<span class="currency">đ</span></b>';
                $contentPrice .= '<span class="save">(Tiết kiệm: ' . WeshopHelper::showMoney($item->getLocalizeTotalStartPrice() - $item->getLocalizeTotalPrice(), 1, '') . 'đ)</span>';
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