<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-28
 * Time: 10:35
 */

namespace weshop\payment;

use weshop\payment\traits\OwnerTrait;
/**
 * Class BasePaymentProvider
 * @package api\modules\v1\payment
 *
 */
abstract class BasePaymentProvider extends \yii\base\BaseObject
{

    use OwnerTrait;

    /**
     * @var array|string
     */
    private $_httpClient;

    /**
     * @return array|null|string
     */
    public function getHttpClient()
    {
        if ($this->_httpClient) {
            $this->_httpClient = null;
        }
        return $this->_httpClient;
    }

    /**
     * @param array $httpClient
     */
    public function setHttpClient(array $httpClient)
    {
        $this->_httpClient = $httpClient;
    }

    /**
     * @return string provider id
     */
    abstract public function getIsSanBox();

    /**
     * @return string provider name
     */
    abstract public function getName();

    /**
     * @param $request
     * @return void
     */
    abstract public function sendRequest($request);

    /**
     * @param $response
     * @return void
     */
    abstract public function handleResponse($response);

}