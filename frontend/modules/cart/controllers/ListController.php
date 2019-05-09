<?php


namespace frontend\modules\cart\controllers;

use common\products\BaseProduct;
use Yii;
use frontend\modules\cart\Module;
use frontend\controllers\PortalController;

class ListController extends PortalController
{

    /**
     * @var Module
     */
    public $module;

    public function actionIndex()
    {
        $cartManager = $this->module->cartManager;
//        $cartManager->update('8fe5cb7c582f868fa22c4ef56a652c0b',['image' => 'https://images-na.ssl-images-amazon.com/images/I/51aLZ8NqnaL.jpg', 'quantity' => 10]);
        $items = $cartManager->getItems();
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
        return ['success' => true, 'message' => 'updated "' . $key . '" success', 'data' => [
            'key' => $key,
            'quantity' => $product->getShippingQuantity(),
            'availableQuantity' => $product->available_quantity,
            'soldQuantity' => $product->quantity_sold,
            'weight' => $product->getShippingWeight(),
            'price' => $product->getLocalizeTotalPrice()
        ]];
    }

    public function actionRemove()
    {

    }
}