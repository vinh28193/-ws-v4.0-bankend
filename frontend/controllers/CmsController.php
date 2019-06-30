<?php


namespace frontend\controllers;

use Yii;
use common\models\cms\PageForm;
use common\models\cms\PageService;
use common\models\cms\WsPage;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class CmsController
 * @package frontend\controllers
 *
 * @property WsPage $page
 */
class CmsController extends FrontendController
{

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }

    const MAX_ITEM_SIZE = 4;

    public $layout = '@frontend/views/layouts/cms';

    public $type = WsPage::TYPE_HOME;

    public $isShow = false;

    public $titlePage;

    public function beforeAction($action)
    {
        if ($this->page === null) {
            throw new NotFoundHttpException(Yii::t('frontend', "Not found page {type}", ['type' => $this->type]));
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

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

    public function ogMetaTag()
    {
        return ArrayHelper::merge(parent::ogMetaTag(), $this->page !== null ? [
            'site_name' => $this->page->title,
            'url' => $this->page->url,
            'image' => $this->page->image,
            'description' => $this->page->description,
        ] : []);
    }

    public function linkTag()
    {
        return ArrayHelper::merge(parent::ogMetaTag(), $this->page !== null ? [
            'canonical' => $this->page->url,
        ] : []);
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
        Yii::info("storeId" , Yii::$app->storeManager->getId());
//         return PageService::getPage($this->type, Yii::$app->storeManager->getId() ? Yii::$app->storeManager->getId() : 1);
    }

    public function defaultLayoutParams()
    {
        return array_merge(parent::defaultLayoutParams(), [
            'page' => $this->page, 'isShow' => $this->isShow
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
