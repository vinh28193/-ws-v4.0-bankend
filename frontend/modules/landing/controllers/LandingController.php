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
        $page = PageService::getPage($this->type, $this->storeManager->getId(), $this->request->get('id'), \Yii::$app->request->get('nocache','') === 'yes');
        if($page && $page instanceof WsPage){
            $this->site_title = $page->title;
            $this->site_description = $page->description;
            $this->site_keyword = $page->seo_keyword;
            if($page->image){
                $this->site_image = $page->image;
            }
        }
        return $page;
    }

}