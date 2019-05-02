<?php


namespace frontend\controllers;

use common\models\cms\PageService;
use common\models\cms\WsPage;

/**
 * Class CmsController
 * @package frontend\controllers
 *
 * @property $page WsPage
 */
class CmsController extends FrontendController
{
    public $layout = '@frontend/views/layouts/cms';

    public $type = WsPage::TYPE_HOME;

    /**
     * @var WsPage
     */
    private $_page;

    /**
     * @return WsPage|null
     */
    public function getPage()
    {
        if ($this->_page === null) {
            $this->_page = PageService::getPage($this->type, 1);
        }
        return $this->_page;
    }

    /**
     * @param $page
     */
    public function setPage($page){
        $this->_page = $page;
    }

}