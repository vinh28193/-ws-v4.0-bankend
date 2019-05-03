<?php


namespace frontend\modules\cms\controllers;


use common\models\cms\PageForm;
use common\models\cms\WsPage;
use frontend\controllers\CmsController;

class HomeController extends CmsController
{

    public $layout = '@frontend/views/layouts/home';

    public function actionIndex()
    {
        $form = new PageForm();
        if (($data = $form->initBlock($this->page)) === false) {
            return $this->redirect('not-found');
        }
        return $this->render('index', ['data' => $data]);
    }
}