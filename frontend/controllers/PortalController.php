<?php


namespace frontend\controllers;


class PortalController extends FrontendController
{
    public $layout = '@frontend/views/layouts/portal';

    public function actionItemError($errors){
        var_dump($errors);die;
    }
}