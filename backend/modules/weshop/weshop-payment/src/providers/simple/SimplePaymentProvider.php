<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-28
 * Time: 15:29
 */

namespace weshop\payment\providers\simple;

class SimplePaymentProvider extends \weshop\payment\BasePaymentProvider
{

    public $username;
    public $password;
    public $submit_url;
    public $return_url;
    public $cancel_url;

    public function getIsSanBox()
    {
        return false;
    }

    public function getName()
    {
        return 'Simple';
    }

    public function sendRequest($request)
    {
        // TODO: Implement sendRequest() method.
    }

    public function handleResponse($response)
    {
        // TODO: Implement handleResponse() method.
    }
}