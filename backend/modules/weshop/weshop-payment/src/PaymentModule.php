<?php

namespace weshop\payment;

use Yii;
/**
 * payment module definition class
 */
class PaymentModule extends \yii\base\Module
{

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'weshop\\payment\\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application) {
            $app->response->format = \yii\web\Response::FORMAT_HTML;
        }
    }
}
