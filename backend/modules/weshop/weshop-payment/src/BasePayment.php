<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-28
 * Time: 10:34
 */

namespace weshop\payment;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;

class BasePayment extends \yii\base\BaseObject
{

    private $_providers = [];

    public function getProviders()
    {
        return $this->_providers;
    }

    /**
     * @param $providers
     * @throws \yii\base\InvalidConfigException
     */
    public function setProviders($providers)
    {
        $results = [];
        foreach ($providers as $name => $provider) {
            if (!isset($provider['class'])) {
                throw new InvalidConfigException('Provider '.$name.' configuration must be an array containing a "class" element.');
            }
            $methods = ArrayHelper::remove($provider,'methods');
            /** @var  $provider BasePaymentProvider */
            $provider = Yii::createObject($provider);
            $provider->setOwner($this);
            if($methods !== null){
                $this->setMethods($methods, $provider);
            }
            $results[$name] = $provider;
        }
        $this->_providers = $results;
    }

    protected function loadProviders(){
        $simple_data = require dirname(__DIR__).'/simple_data.php';
        $this->setProviders($simple_data);
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

    /**
     * @param $methods
     * @param $provider
     * @param bool $overrideMethod
     * @throws InvalidConfigException
     */
    public function setMethods($methods, $provider, $overrideMethod = false)
    {
        $results = [];
        foreach ($methods as $name => $method) {
            if (!isset($method['class'])) {
                throw new InvalidConfigException('Method '.$name.' configuration must be an array containing a "class" element.');
            }
            if (isset($method['banks']) && ($banks = $method['banks']) !== null) {
                $this->setBanks($banks, $name);
                unset($method['banks']);
            }
            /** @var  $method BasePaymentMethod */
            $method = Yii::createObject($method);
            $method->setOwner($provider);
            $results[$name] = $method;
        }

        $this->_methods = $overrideMethod ? $results : array_merge($this->getMethods(), $results);
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

    public function setBanks($banks, $methodName)
    {
        foreach ($banks as $bank) {

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
            $this->loadProviders();
        }
    }
}