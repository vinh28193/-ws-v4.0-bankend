<?php


namespace frontend\modules\checkout\controllers;


class NotifyController extends CheckoutController
{

    public function actionOfficeSuccess($code)
    {
        return $this->render('office_success');
    }
}