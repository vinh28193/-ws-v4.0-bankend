<?php


namespace frontend\modules\cart\controllers;


use Yii;
use frontend\modules\cart\Module;
use common\products\BaseProduct;
class ListController extends CartController
{

    public function actionIndex()
    {
        $cartManager = $this->module->cartManager;
//        $cartManager->update('8fe5cb7c582f868fa22c4ef56a652c0b',['image' => 'https://images-na.ssl-images-amazon.com/images/I/51aLZ8NqnaL.jpg', 'quantity' => 10]);
        $items = $cartManager->getItems();
        if(Yii::$app->getRequest()->getIsPjax()){
            if (count($items) === 0) {
                return $this->renderAjax('empty');
            }
            return $this->renderAjax('index', [
                'items' => $items
            ]);
        }
        if (count($items) === 0) {
            return $this->render('empty');
        }
        return $this->render('index', [
            'items' => $items
        ]);
    }

    public function actionRefresh()
    {

    }

    public function actionUpdate()
    {
        $cartManager = $this->module->cartManager;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->getRequest()->getBodyParams();
        if (!isset($params['id']) || ($key = $params['id']) === null || $key === '') {
            return ['success' => false, 'message' => 'Invalid params'];
        }
        unset($params['id']);
        /** @var $product BaseProduct|false */
        if (($product = $cartManager->update($key, $params)) === false) {
            return ['success' => false, 'message' => 'Can not update now'];
        }
        return ['success' => true, 'message' => 'updated "' . $key . '" success'];
    }

    public function actionRemove()
    {
        $cartManager = $this->module->cartManager;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->getRequest()->getBodyParams();
        if (!isset($params['id']) || ($key = $params['id']) === null || $key === '') {
            return ['success' => false, 'message' => 'Invalid params'];
        }
        if(!$cartManager->removeItem($key)){
            return ['success' => false, 'message' => "can not delete item `$key`"];
        }
        return ['success' => false, 'message' => "item `$key` had been deleted"];
    }
}