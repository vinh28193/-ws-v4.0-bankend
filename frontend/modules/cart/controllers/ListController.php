<?php


namespace frontend\modules\cart\controllers;

use frontend\modules\cart\Module;
use frontend\controllers\PortalController;

class ListController extends PortalController
{

    /**
     * @var Module
     */
    public $module;

    public function actionIndex(){
        $cartManager = $this->module->cartManager;
        $items = $cartManager->getItems();
        if(count($items) === 0){
            return $this->render('empty');
        }
        return $this->render('index',[
            'items' => $items
        ]);
    }
}