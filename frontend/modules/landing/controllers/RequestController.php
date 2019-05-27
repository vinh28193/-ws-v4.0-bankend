<?php


namespace landing\controllers;


use common\models\cms\WsPage;

class RequestController extends PageController
{

    public $type = WsPage::TYPE_LANDING_REQUEST;

    public $layout = 'request';

    public function actionIndex()
    {
        return $this->render('@landing/views/page/index', [
            'data_block' => $this->renderBlock(1, 50),
        ]);
    }
}