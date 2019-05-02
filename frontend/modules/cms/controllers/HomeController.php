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
        if (($data = $form->initBlock($this->getPage())) === false) {
            return $this->redirect('not-found');
        }
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        die();
        return $this->render('index', $data);
    }
}