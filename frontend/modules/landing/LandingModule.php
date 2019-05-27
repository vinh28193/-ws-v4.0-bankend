<?php

namespace frontend\modules\landing;

use Yii;

use yii\base\Module;

/**
 * landing module definition class
 */
class LandingModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\landing\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::setAlias('@landing', __DIR__);
        $this->layoutPath = Yii::getAlias('@landing/layouts');
        // custom initialization code goes here
    }

}
