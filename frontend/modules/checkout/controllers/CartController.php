<?php


namespace frontend\modules\checkout\controllers;

use common\components\cart\CartHelper;
use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\products\BaseProduct;
use common\components\cart\CartSelection;

class CartController extends BillingController
{

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (Yii::$app->user->getIsGuest()) {

//                Yii::$app->user->loginRequired();
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

        $cartContent = 'cartContent';
        $queryParams = $this->request->queryParams;
        $type = CartSelection::TYPE_SHOPPING;
        if (isset($queryParams['type'])) {
            if (ArrayHelper::isIn($queryParams['type'], CartSelection::getAllTypes())) {
                $type = $queryParams['type'];
            }
        }
        $ids = null;
        if (isset($queryParams['ref'])) {
            $ids = $queryParams['ref'];
        }
        $uuid = $this->filterUuid();
        $cartManager = $this->module->cartManager;
        $items = $cartManager->getItems($type, $ids, $this->filterUuid());
        if ($this->request->getIsPjax()) {
            return $this->renderPartial('index', [
                'uuid' => $uuid,
                'cartContent' => $cartContent,
                'items' => $items
            ]);
        }
        CartSelection::setSelectedItems(CartSelection::TYPE_SHOPPING, ArrayHelper::getColumn($items, '_id', false));
        return $this->render('index', [
            'uuid' => $uuid,
            'cartContent' => $cartContent,
            'items' => $items
        ]);

    }

    public function actionCheckUuid()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->filterUuid() !== null;
    }

    public function actionCount()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $count = $this->module->cartManager->countItems(CartSelection::TYPE_SHOPPING, $this->filterUuid());
        return ['success' => true,'count' => $count];
    }

    public function actionAdd()
    {
        Yii::info(Yii::$app->request->post(), 'POST');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->bodyParams;
        $type = ArrayHelper::getValue($params, 'type', CartSelection::TYPE_SHOPPING);
        if (($item = ArrayHelper::getValue($params, 'item')) === null) {
            return ['success' => false, 'message' => 'add cart from empty data'];
        }

        if (($key = $this->module->cartManager->addItem($type, $item, $this->filterUuid()))[0] === false) {
            return ['success' => false, 'message' => $key[1]];
        };

        if ($type === CartSelection::TYPE_BUY_NOW || $type === CartSelection::TYPE_INSTALLMENT) {
            CartSelection::setSelectedItems($type, (string)$key[0]);
            $checkOutAction = Url::toRoute(['/checkout/shipping', 'type' => $type]);
            return ['success' => true, 'message' => 'You will be ' . $type . ' with cart:' . $key[0], 'data' => $checkOutAction];
        } else {
            return ['success' => true, 'message' => $key[1], 'data' => [
                'key' => $key[0],
                'countItems' => $this->module->cartManager->countItems(CartSelection::TYPE_SHOPPING),
            ]];
        }
    }

    public function actionUpdate()
    {
        $cartManager = $this->module->cartManager;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $bodyParams = Yii::$app->getRequest()->getBodyParams();
        if (!isset($bodyParams['type']) || ($type = $bodyParams['type']) === null || $type === '') {
            return ['success' => false, 'message' => 'Invalid parameter, can not update with unknown cart type'];
        }
        if (!isset($bodyParams['id']) || ($id = $bodyParams['id']) === null || $id === '') {
            return ['success' => false, 'message' => 'Invalid parameter, can not update with unknown cart id'];
        }
        if (!isset($bodyParams['key']) || ($key = $bodyParams['key']) === null || !is_array($key) || empty($key)) {
            return ['success' => false, 'message' => 'Invalid parameter, can not update with unknown product id'];
        }
        if (!isset($bodyParams['param']) || ($param = $bodyParams['param']) === null || !is_array($param) || empty($param)) {
            return ['success' => false, 'message' => 'Invalid parameter, required parameter not pass form request'];
        }

        list($success, $message) = $cartManager->updateItem($type, $id, $key, $param);
        if (!$success) {
            return ['success' => false, 'message' => $message];
        }
        return ['success' => true, 'message' => 'updated "' . $id . '" success'];
    }

    public function actionSelection()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->getRequest()->getBodyParams();
        if (!isset($params['id']) || ($key = $params['id']) === null || $key === '') {
            return ['success' => false, 'message' => 'Invalid params'];
        }
        $selected = ArrayHelper::getValue($params, 'selected');
        $message = "item `$key`";
        if ($selected === 'true') {
            CartSelection::addSelectedItem(CartSelection::TYPE_SHOPPING, $key);
            $message .= ' added';
        } else {
            CartSelection::removeSelectedItem(CartSelection::TYPE_SHOPPING, $key);
            $message .= ' removed';
        }
        return ['success' => $selected, 'message' => $message, 'data' => CartSelection::getSelectedItems(CartSelection::TYPE_SHOPPING)];
    }

    public function actionRemove()
    {
        $cartManager = $this->module->cartManager;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $bodyParams = Yii::$app->getRequest()->getBodyParams();

        if (!isset($bodyParams['type']) || ($type = $bodyParams['type']) === null || $type === '') {
            return ['success' => false, 'message' => 'Sai tham số, không thể xóa sản phẩm của một loại không rõ'];
        }
        if (!isset($bodyParams['id']) || ($id = $bodyParams['id']) === null || $id === '') {
            return ['success' => false, 'message' => 'Sai tham số, không thể xóa sản phẩm không tồn tại'];
        }
        if (!isset($bodyParams['key']) || ($key = $bodyParams['key']) === null || !is_array($key) || empty($key)) {
            return ['success' => false, 'message' => 'Sai tham số, không thể xóa sản phẩm này'];
        }

        list($success, $message) = $cartManager->removeItem($type, $id, $key);

        return ['success' => $success, 'message' => $message, 'countItems' => $cartManager->countItems(CartSelection::TYPE_SHOPPING)];
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

    private function setDefaultSelected($items)
    {
        $selected = CartHelper::mapCartKeys($items);
        if (!empty($selected)) {
            CartSelection::setSelectedItems(CartSelection::TYPE_SHOPPING, $selected);
        }
    }
}
