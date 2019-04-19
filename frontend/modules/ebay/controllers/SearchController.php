<?php


namespace frontend\modules\ebay\controllers;



class SearchController extends EbayController
{

    public function actionIndex(){
        return $this->render('index');
    }
}