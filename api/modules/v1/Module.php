<?php

namespace api\modules\v1;

use yii\base\BootstrapInterface;
/**
 * v1 module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'api\modules\v1\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public function bootstrap($app){
        if($app instanceof \yii\web\Application){
            $rules = require __DIR__ . '/routers/routers.php';
            \Yii::info($app->getUrlManager()->enablePrettyUrl,'enablePrettyUrl');
            \Yii::info($app->getUrlManager()->enableStrictParsing,'enableStrictParsing');
            \Yii::info($app->getUrlManager()->showScriptName,'showScriptName');
            $app->getUrlManager()->addRules($rules,false);
        }
    }

    public function afterAction($action, $result)
    {
        return parent::afterAction($action, $result);
    }
}
