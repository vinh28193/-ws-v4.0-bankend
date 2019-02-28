<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-28
 * Time: 15:29
 */

namespace api\modules\v1\payment\providers;

class SimplePaymentProvider extends \api\modules\v1\payment\BasePaymentProvider
{

    public $merchantId = '122828873737';
    public $merchantPass = '122828873737';

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