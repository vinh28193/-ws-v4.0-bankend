<?php


namespace frontend\modules\checkout\controllers;


use common\components\cart\CartSelection;
use common\products\BaseProduct;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class CartController extends BillingController
{

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (Yii::$app->user->getIsGuest()) {
                Yii::$app->user->loginRequired();
            }
            return true;
        }
        return false;
    }

    public function init()
    {
        parent::init();

    }

    public function actionIndex()
    {

        $cartManager = $this->module->cartManager;
        $items = $cartManager->getItems();
        if (Yii::$app->getRequest()->getIsPjax()) {
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

    public function actionAdd()
    {
        Yii::info(Yii::$app->request->post(), 'POST');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->bodyParams;
        $type = ArrayHelper::getValue($params, 'type', CartSelection::TYPE_SHOPPING);
        if ($type !== CartSelection::TYPE_BUY_NOW && Yii::$app->user->getIsGuest()) {
            return ['success' => false, 'message' => 'Please login to used this action'];
        }
        if (($item = ArrayHelper::getValue($params, 'item')) === null) {
            return ['success' => false, 'message' => 'add cart from empty data'];
        }

        if (($key = $this->module->cartManager->addItem($item, true)) === false) {
            return ['success' => false, 'message' => 'Can not add this item to cart'];
        };

        if ($type === CartSelection::TYPE_BUY_NOW || $type === CartSelection::TYPE_INSTALLMENT) {
            CartSelection::setSelectedItems($type, $key);
            $checkOutAction = Url::toRoute(['/checkout/shipping', 'type' => $type]);
            return ['success' => true, 'message' => 'You will be ' . $type . ' with cart:' . $key, 'data' => $checkOutAction];
        }
        return ['success' => true, 'message' => 'Can not Buy now this item', 'data' => $key];
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

    public function actionSelection()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->getRequest()->getBodyParams();
        if (!isset($params['id']) || ($key = $params['id']) === null || $key === '') {
            return ['success' => false, 'message' => 'Invalid params'];
        }
        ;
        if (!CartSelection::watchItem(CartSelection::TYPE_SHOPPING,$key)) {
            return ['success' => false, 'message' => "can not delete item `$key`"];
        }
        return ['success' => true, 'message' => "update selected item `$key`"];
    }

    public function actionRemove()
    {
        $cartManager = $this->module->cartManager;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->getRequest()->getBodyParams();
        if (!isset($params['id']) || ($key = $params['id']) === null || $key === '') {
            return ['success' => false, 'message' => 'Invalid params'];
        }
        if (!$cartManager->removeItem($key)) {
            return ['success' => false, 'message' => "can not delete item `$key`"];
        }
        return ['success' => false, 'message' => "item `$key` had been deleted"];
    }

    public function actionPayment()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (($carts = ArrayHelper::getValue($params, 'carts')) === null || empty($carts)) {
            return ['success' => false, 'message' => 'No cart select'];
        }
        if (($type = ArrayHelper::getValue($params, 'type')) === null) {
            $type = CartSelection::TYPE_SHOPPING;
        }
        CartSelection::setSelectedItems($type, $carts);
        $count = CartSelection::countSelectedItems($type);
        return ['success' => true, 'message' => "you will be $type with $count items", 'data' => Url::toRoute(['/checkout/shipping', 'type' => CartSelection::TYPE_SHOPPING])];
    }
}