<?php


namespace frontend\modules\cms\controllers;

use Yii;
use common\models\cms\PageForm;
use common\models\cms\WsPage;
use frontend\controllers\CmsController;

class HomeController extends CmsController
{
    public function gaHomeWs()
    {
        Yii::info("HOME GA WS");
        Yii::$app->ga->request()
            ->setClientId(@uniqid())
            ->setDocumentPath('/HomeWs')
            ->setAsyncRequest(true)
            ->sendPageview();
    }

    public function actionIndex()
    {
        $this->gaHomeWs();
        if (($data = $this->renderBlock(1,6)) === false) {
            return $this->redirect('@frontend/views/common/404');
        }
        return $this->render('index', ['data' => $data]);
    }


}
