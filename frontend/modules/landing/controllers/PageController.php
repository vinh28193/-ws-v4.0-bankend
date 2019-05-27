<?php


namespace landing\controllers;


class PageController extends LandingController
{


    public function actionIndex()
    {
        return $this->render('index');
    }
}