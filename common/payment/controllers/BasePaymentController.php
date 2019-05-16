<?php


namespace common\payment\controllers;

use common\components\cart\CartManager;
use Yii;
use common\components\StoreManager;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\web\Request;

class BasePaymentController extends \yii\rest\Controller
{

    public $layout = false;
    /**
     * @var string|StoreManager
     */
    public $storeManager = 'storeManager';

    /**
     * @var string|Request
     */
    public $request = 'request';

    /**
     * @var string|CartManager
     */
    public $cartManager = 'cart';

    /**
     * @var IdentityInterface
     */
    public $user;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->storeManager = Instance::ensure($this->storeManager, StoreManager::className());
        $this->cartManager = Instance::ensure($this->cartManager, CartManager::className());
        $this->request = Instance::ensure($this->request, Request::className());
        if (!$this->user instanceof IdentityInterface) {
            $this->user = Yii::$app->user->identity;
        }
    }

    public function response($success, $message, $data = [], $status = 200)
    {
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        $response->data = [
            'success' => $success,
            'message' => $message,
            'data' => $data
        ];
        $response->setStatusCode($status);
        Yii::$app->set('response', $response);
    }
}