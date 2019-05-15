<?php

namespace wallet\modules\v1\filters\auth;

use common\models\response\Response;
use \Yii;

class CompositeAuth extends \yii\filters\auth\CompositeAuth
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $oauthServer = Yii::$app->getModule('v1')->getServer();
        $oauthRequest = Yii::$app->getModule('v1')->getRequest();
        $check=$oauthServer->verifyResourceRequest($oauthRequest);
        if ($check) {
            $token = $oauthServer->getResourceController()->getToken();
            $scopeUtil = $oauthServer->getScopeUtil();
            if($scopeUtil->checkScope("default",$token['scope'])){
                return parent::beforeAction($action);
            }else{
                return  [false,"You are not have scope to do this",403];
                die ();
            }
        };
        return parent::beforeAction($action);
    }
}