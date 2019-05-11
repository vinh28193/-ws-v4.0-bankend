<?php

namespace frontend\modules\checkout;


use Yii;
use yii\di\Instance;
use yii\web\IdentityInterface;
use common\components\cart\CartManager;
/**
 * checkout module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\checkout\controllers';

    /**
     * @var string|CartManager
     */
    public $cartManager = 'cart';

    /**
     * @var IdentityInterface
     */
    public $user;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->cartManager = Instance::ensure($this->cartManager, CartManager::className());
        if (!$this->user instanceof IdentityInterface) {
            $this->user = Yii::$app->user->identity;
        }
        $view = Yii::$app->getView();
        PaymentAssets::register($view);
        // custom initialization code goes here
    }
}
