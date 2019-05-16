<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-05-09
 * Time: 14:16
 */
namespace wallet\modules\v1\base;
use yii\base\BootstrapInterface;
class WalletDevelopBootstrapping implements BootstrapInterface
{
    /**
     * @var mixed $identityCondition please refer to [[findOne()]] for the explanation of this parameter
     */
    public $identityCondition = 1;
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param \yii\console\Application|\yii\web\Application $app the application currently running
     */
    public function bootstrap($app)
    {
        if($app instanceof \yii\web\Application){
            if(!$app->getUser()->identity)  {
                $app->request->enableCookieValidation = false;
                $app->user->setIdentity(\wallet\modules\v1\models\WalletClient::findOne(1));
            }
        }
    }
}