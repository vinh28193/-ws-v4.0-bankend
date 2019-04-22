<?php


namespace frontend\modules\ebay\controllers;


use common\products\forms\ProductDetailFrom;

class ItemController extends EbayController
{

    public function actionIndex($id){
        $form = new ProductDetailFrom();
        $form->id = $id;
        $form->type = 'ebay';
        $item = $form->detail();
        return $this->render('index',['item' => $item]);

    }
}