<?php


namespace landing\controllers;


use common\models\cms\PageService;
use common\models\cms\WsPage;
use frontend\controllers\CmsController;

class LandingController extends CmsController
{

    public $layout = 'main';

    public $type = WsPage::TYPE_LANDING;

    public function init()
    {
        parent::init();
    }

    protected function getActivePage()
    {
        return PageService::getPage($this->type, 1, $this->request->get('id'), \Yii::$app->request->get('nocache','') === 'yes');
    }

}