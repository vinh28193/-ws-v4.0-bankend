<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-05-17
 * Time: 08:37
 */

namespace frontend\modules\payment\providers\wallet;

use yii\authclient\OAuth2;
use yii\helpers\ArrayHelper;

class WalletClient extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->clientId = 'testclient';
        $this->clientSecret = 'testpass';
        if(($urlWallet = ArrayHelper::getValue(\Yii::$app->params,'Url_wallet_api'))){
            $this->authUrl = $urlWallet . '/v1/rest/authorize';
            $this->tokenUrl = $urlWallet . '/v1/rest/token';
            $this->apiBaseUrl = $urlWallet . '/v1';
        }
        parent::init();
//        if ($this->scope === null) {
//            $this->scope = implode(' ', [
//            ]);
//        }
    }

    /**
     * {@inheritdoc}
     */
    protected function initUserAttributes()
    {
        return $this->api('test/me', 'get');
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultName()
    {
        return 'wallet';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultTitle()
    {
        return 'Wallet';
    }

    /**
     * {@inheritdoc}
     */
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        $request->getHeaders()->set('Authorization', 'Bearer ' . $accessToken->getToken());
    }

    protected function getStateKeyPrefix()
    {
        return 'wallet_';
    }
}