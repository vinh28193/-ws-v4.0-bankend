<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-27
 * Time: 16:45
 */

namespace common\components\rest;


class WeshopAuth extends \yii\filters\auth\HttpHeaderAuth
{

    /**
     * {@inheritdoc}
     */
    public $header = 'Authenticate';

    /**
     * {@inheritdoc}
     */
    public $pattern = '/^Weshop\s+(.*?)$/';

    /**
     * @var string the HTTP authentication realm
     */
    public $realm = 'api';

    /**
     * {@inheritdoc}
     */
    public function challenge($response)
    {
        $response->getHeaders()->set('WWW-Authenticate', "Weshop realm=\"{$this->realm}\"");
    }
}