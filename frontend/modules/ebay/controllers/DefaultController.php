<?php

namespace frontend\modules\ebay\controllers;

use frontend\controllers\PortalController;

/**
 * Default controller for the `ebay` module
 */
class DefaultController extends PortalController
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
