<?php

namespace api\modules\v1\payment;

/**
 * payment module definition class
 */
class PaymentModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'api\modules\v1\payment\controllers';

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

        }
    }
}
