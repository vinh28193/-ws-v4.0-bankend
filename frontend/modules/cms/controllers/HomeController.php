<?php


namespace frontend\modules\cms\controllers;


use common\models\cms\PageForm;
use common\models\cms\WsPage;
use frontend\controllers\CmsController;

class HomeController extends CmsController
{

    public function actionIndex()
    {
        if (($data = $this->renderBlock(1,6)) === false) {
            return $this->redirect('not-found');
        }
        return $this->render('index', ['data' => $data]);
    }


}