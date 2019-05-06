<?php


namespace frontend\modules\ebay\controllers;


use common\products\forms\ProductDetailFrom;
use yii\helpers\Url;

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
}