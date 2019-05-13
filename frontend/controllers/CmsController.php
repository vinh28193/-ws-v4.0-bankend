<?php


namespace frontend\controllers;

use common\models\cms\PageForm;
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

    const MAX_ITEM_SIZE = 4;

    public $layout = '@frontend/views/layouts/cms';

    public $type = WsPage::TYPE_HOME;

    public $isShow = true;

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
            $this->_page = $this->getActivePage();
        }
        return $this->_page;
    }

    /**
     * @param $page
     */
    public function setPage($page)
    {
        $this->_page = $page;
    }

    /**
     * @return WsPage|null
     */
    protected function getActivePage()
    {
        return PageService::getPage($this->type, 1);
    }

    public function defaultLayoutParams()
    {
        return array_merge(parent::defaultLayoutParams(), [
            'page' => $this->page,'isShow' => $this->isShow
        ]);
    }

    public function defaultViewParams()
    {
        return array_merge(parent::defaultLayoutParams(), [
            ['page' => $this->page]
        ]);
    }

    /**
     * @param int $p
     * @param int $limit
     * @return array
     */
    public function renderBlock($p = 1, $limit = 6)
    {
        if ($limit > 1) {
            $offset = ($p - 1) * $limit;
        } else {
            $offset = -1;
        }
        return (new PageForm())->initBlock($this->page, $limit, $offset);
    }
}