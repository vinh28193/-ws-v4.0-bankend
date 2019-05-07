<?php


namespace frontend\modules\cms\controllers;


use Yii;
use common\models\cms\WsPage;
use common\models\cms\PageService;
use frontend\controllers\CmsController;

class AmazonJpController extends CmsController
{

    public $type = WsPage::TYPE_AMZ_JP;

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