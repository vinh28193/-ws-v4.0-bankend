<?php


namespace frontend\modules\checkout\controllers;

use Yii;

class BillingController extends CheckoutController
{

    public function init()
    {
        if (($code = Yii::$app->request->get('code')) !== null) {
            $this->title = Yii::t('frontend', 'Payment success for invoice {code}', ['code' => $code]);
        }
        parent::init();
    }

    public function actionSuccess($code)
    {
        return $this->render('success', ['code' => $code]);
    }

    public function actionFail($code)
    {
        return $this->render('fail', ['code' => $code]);
    }
}