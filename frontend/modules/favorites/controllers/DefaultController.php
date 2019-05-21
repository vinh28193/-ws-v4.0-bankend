<?php

namespace frontend\modules\favorites\controllers;

use yii\web\Controller;

/**
 * Default controller for the `favorites` module
 */
class DefaultController extends Controller
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
