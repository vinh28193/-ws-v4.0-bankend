<?php

namespace landing;

use Yii;

use yii\base\Module;
use yii\web\Application;
use yii\base\BootstrapInterface;

/**
 * landing module definition class
 */
class LandingModule extends Module implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'landing\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->layoutPath = Yii::getAlias('@landing/layouts');
        // custom initialization code goes here
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {

    }
}
