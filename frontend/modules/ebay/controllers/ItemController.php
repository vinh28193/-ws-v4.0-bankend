<?php


namespace frontend\modules\ebay\controllers;

use common\products\BaseProduct;
use Yii;
use common\products\forms\ProductDetailFrom;

class ItemController extends EbayController
{

    public function actionDetail($id)
    {
        $form = new ProductDetailFrom();
        $form->id = $id;
        $form->type = 'ebay';
        $item = $form->detail();
        if(Yii::$app->request->isPjax){
            if ($item === false) {
                return $this->renderAjax('@frontend/views/common/item_error', [
                    'errors' => $form->getErrors()
                ]);
            }
            return $this->renderAjax('index', [
                'item' => $item
            ]);
        }
        if ($item  === false) {
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
        $form->type = 'ebay';
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
            $response['content'] = [
                'fees' => $fees,
                'queryParams' => $post,
                'sellPrice' => $item->getLocalizeTotalPrice(),
                'startPrice' => $item->getLocalizeTotalStartPrice(),
                'salePercent' => $item->getSalePercent()
            ];
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
    }
}