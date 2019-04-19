<?php


namespace frontend\modules\ebay\controllers;


class ItemController extends EbayController
{

    public function actionIndex(){
        return $this->render('index');
    }
}