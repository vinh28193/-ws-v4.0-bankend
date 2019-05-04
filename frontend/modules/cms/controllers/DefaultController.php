<?php

namespace frontend\modules\home\controllers;

use frontend\controllers\CmsController;

/**
 * Default controller for the `home` module
 */
class DefaultController extends CmsController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
