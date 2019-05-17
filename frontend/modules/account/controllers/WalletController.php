<?php


namespace frontend\modules\account\controllers;


class WalletController extends BaseAccountController
{
    public function actionIndex(){
        return $this->render('index');
    }
    public function actionTopUp(){
        return $this->render('top-up');
    }
    public function actionDetail(){
        return $this->render('detail');
    }
    public function actionWithdraw(){
        return $this->render('withdraw');
    }
}