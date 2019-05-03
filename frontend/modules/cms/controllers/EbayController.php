<?php


namespace frontend\modules\cms\controllers;

use common\models\cms\PageService;
use Yii;
use common\models\cms\WsPage;
use frontend\controllers\CmsController;

class EbayController extends CmsController
{

    const MAX_ITEM_SIZE = 4;

    public $type = WsPage::TYPE_EBAY;

    public function actionIndex()
    {
        $request = Yii::$app->getRequest();
        if($request->getIsAjax()){
            $p = Yii::$app->request->get('p');
            return $this->renderPartial('_content',[
                'content' => $this->renderBlock($p,self::MAX_ITEM_SIZE),
            ]);
        }
        $totalCount = PageService::getPageItem($this->getPage()->id,-1,-1);
        $numPages = (int)($totalCount / self::MAX_ITEM_SIZE) + (($totalCount % self::MAX_ITEM_SIZE > 0) ? 1 : 0);
        return $this->render('index', [
            'numPage' => $numPages,
            'content' => $this->renderBlock(1,self::MAX_ITEM_SIZE),
        ]);
    }


}