<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-28
 * Time: 10:34
 */

namespace api\modules\v1\payment;


class BasePayment extends \yii\base\BaseObject
{


    private $_providers = [];

    public function getProviders()
    {
        return $this->_providers;
    }

    public function setProviders($providers)
    {
        $results = [];
        foreach ($providers as $name => $provider){
            if (isset($provider['methods']) && ($methods = $provider['methods']) !== null) {
                $this->setMethods($methods,$name);
                unset($provider['methods']);
            }
            $results[$name] = $provider;
        }
        $this->_providers = $results;
    }

    /**
     * @var array
     */
    private $_provider = [];

    public function getProvider($name)
    {
        $providers = $this->getProviders();
        if (isset($providers[$name]) && ($provider = $providers[$name]) !== null) {
            $this->_provider = $provider;
        }
        return $this->_provider;
    }

    private $_methods = [];

    public function setMethods($methods,$providerName)
    {
        $results = [];
        foreach ($methods as $name => $method){
            if (isset($method['banks']) && ($banks = $method['banks']) !== null) {
                $this->setBanks($banks,$name);
                unset($method['banks']);
            }
            $method['providerName'] = $providerName;
            $results[$name] = $method;
        }

        $this->_methods = array_merge($this->getMethods(),$results);
    }
    public function getMethods()
    {
        return $this->_methods;
    }
    private $_method = [];

    public function getMethod($name)
    {
        $methods = $this->getMethods();
        if (isset($methods[$name]) && ($method = $methods[$name]) !== null) {
            $this->_method = $method;
        }
        return $this->_method;
    }

    private $_banks = [];

    public function getBanks()
    {
        return $this->_banks;
    }

    public function setBanks($banks,$methodName)
    {
        foreach ($banks as $bank){

        }
        $this->_banks = $banks;
    }

    private $_bank = [];

    public function getBank($name)
    {
        $banks = $this->getBanks();
        if (isset($banks[$name]) && ($bank = $banks[$name]) !== null) {
            $this->_bank = $bank;
        }
        return $this->_method;
    }


   public function init()
   {
       parent::init();
       if (count($this->getProviders()) === 0) {
           $simple_data = require 'simple_data.php';
           $this->setProviders($simple_data);
       }
   }
}