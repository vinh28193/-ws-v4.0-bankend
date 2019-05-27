<?php


namespace frontend\modules\landing\controllers;


use common\models\cms\PageService;

class PageController extends LandingController
{

    public $id;

    public function init()
    {
        parent::init();
        $this->id = $this->request->get('id');
    }

    protected function getActivePage()
    {
        return PageService::getPage($this->type, 1, $this->id);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}