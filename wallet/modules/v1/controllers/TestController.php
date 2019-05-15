<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 10/05/2018
 * Time: 01:25 PM
 */

namespace wallet\modules\v1\controllers;


use wallet\modules\v1\models\form\ChangeBalanceForm;
use yii\web\Controller;

class TestController extends \yii\base\Controller
{

    /**
     * @return array
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionMe(){
        return ['code' => true,'message' => 'You are connented','data' => \Yii::$app->user->getIdentity()];
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function actionTopup(){
        if(($amount = \Yii::$app->request->post('amount')) && ($walletTransactionId = \Yii::$app->request->post('walletTransactionId'))){
            $wallet = new  ChangeBalanceForm();
            $wallet->amount = $amount;
            $wallet->walletTransactionId = $walletTransactionId;
            print_r($wallet->topUp());
            die;
        }
        return false;
    }

    public function actionFreeze(){
        if(($amount = \Yii::$app->request->post('amount')) && ($walletTransactionId = \Yii::$app->request->post('walletTransactionId'))){
            $wallet = new  ChangeBalanceForm();
            $wallet->amount = $amount;
            $wallet->walletTransactionId = $walletTransactionId;
            print_r($wallet->freeze()) ;
            die();
        }
        return false;
    }

    /**
     * @return array|bool
     * @throws \yii\db\Exception
     */
    public function actionPayment(){
        if(($amount = \Yii::$app->request->post('amount')) && ($walletTransactionId = \Yii::$app->request->post('walletTransactionId'))){
            $wallet = new  ChangeBalanceForm();
            $wallet->amount = $amount;
            $wallet->walletTransactionId = $walletTransactionId;
            print_r($wallet->payment());
            die;
        }
        return false;
    }

    /**
     * @return array|bool
     * @throws \yii\db\Exception
     */
    public function actionWithdraw(){
        if(($amount = \Yii::$app->request->post('amount')) && ($walletTransactionId = \Yii::$app->request->post('walletTransactionId'))){
            $wallet = new  ChangeBalanceForm();
            $wallet->amount = $amount;
            $wallet->walletTransactionId = $walletTransactionId;
            print_r( $wallet->withDraw());
            die;
        }
        return false;
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function actionRefurn(){
        if(($amount = \Yii::$app->request->post('amount')) && ($walletTransactionId = \Yii::$app->request->post('walletTransactionId'))){
            $wallet = new  ChangeBalanceForm();
            $wallet->amount = $amount;
            $wallet->walletTransactionId = $walletTransactionId;
            print_r($wallet->refunded());
            die;
        }
        return false;
    }

    /**
     * @return array|bool
     */
    public function actionUnfreeadd(){
        if(($amount = \Yii::$app->request->post('amount')) && ($walletTransactionId = \Yii::$app->request->post('walletTransactionId'))){
            $wallet = new  ChangeBalanceForm();
            $wallet->amount = $amount;
            $wallet->walletTransactionId = $walletTransactionId;
            print_r($wallet->unFreezeAdd());
            die;
        }


        return false;
    }
}