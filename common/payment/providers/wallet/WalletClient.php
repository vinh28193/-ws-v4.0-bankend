<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-05-17
 * Time: 08:37
 */

namespace common\payment\providers\wallet;

use yii\authclient\OAuth2;

class WalletClient extends OAuth2
{

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