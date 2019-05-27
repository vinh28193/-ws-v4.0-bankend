<?php


namespace landing\controllers;


use common\models\cms\WsPage;

class RequestController extends LandingController
{

    public $type = WsPage::TYPE_LANDING_REQUEST;

    public function actionIndex()
    {

    }
}