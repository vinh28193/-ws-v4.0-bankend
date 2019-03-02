<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-01
 * Time: 10:42
 */

namespace weshop\payment\providers\wallet;

class WeshopWalletProvider extends \weshop\payment\BasePaymentProvider
{

    /**
     * @return string provider id
     */
    public function getIsSanBox(){
        return true;
    }

    /**
     * @return string provider name
     */
    public function getName(){
        return 'Weshop';
    }

    /**
     * @param $request
     * @return void
     */
    public function sendRequest($request){

    }

    /**
     * @param $response
     * @return void
     */
     public function handleResponse($response){

     }
}