<?php


namespace frontend\modules\ebay\controllers;

use common\products\BaseProduct;
use Yii;
use common\products\forms\ProductDetailFrom;

class ItemController extends EbayController
{

    public function actionIndex($id)
    {
        $form = new ProductDetailFrom();
        $form->id = $id;
        $form->type = 'ebay';
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
//            $response['content'] = [
//                'fees' => $fees,
//                'queryParams' => $post,
//                'totalFee' => $item->getAdditionalFees()->getTotalAdditionFees()
//            ];
            $response['content'] = $item->current_variation;
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
    }
}