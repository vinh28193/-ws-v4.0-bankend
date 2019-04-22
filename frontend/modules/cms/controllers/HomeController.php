<?php


namespace frontend\modules\cms\controllers;


use common\models\cms\PageForm;
use common\models\cms\WsPage;
use frontend\controllers\CmsController;

class HomeController extends CmsController
{


    public function actionIndex()
    {
        $form = new PageForm();
        $form->type = WsPage::TYPE_HOME;
        if (($data = $form->initBlock()) === false) {
            return $this->redirect('not-found');
        }
        var_dump($data);die();
        return $this->render('index', $data);
    }
}