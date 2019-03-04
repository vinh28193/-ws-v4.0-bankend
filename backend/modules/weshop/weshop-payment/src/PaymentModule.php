<?php

namespace weshop\payment;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use weshop\payment\methods\SimpleMethod;
use weshop\payment\collection\MethodCollection;

/**
 * payment module definition class
 */
class PaymentModule extends \yii\base\Module
{

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'weshop\\payment\\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        print_r($this->getMethods());
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application) {
            $app->response->format = \yii\web\Response::FORMAT_HTML;
        }
    }

    /**
     * @var MethodCollection Collection of methods.
     */
    private $_methods;

    /**
     * @return MethodCollection
     * @throws InvalidConfigException
     */
    public function getMethods(){
        if($this->_methods === null){
            $this->_methods = new MethodCollection();
            foreach ((array)$this->loadMethodsFromSources() as $name => $method){
                $this->_methods->add($name,$method);
            }

        }
        return $this->_methods;

    }

    /**
     * @return array|mixed|null
     * @throws InvalidConfigException
     */
    protected function loadMethodsFromSources(){
        $providers = require dirname(__DIR__).'/simple_data.php';
        $results = [];

        foreach ($providers as $name => $provider){
            if (!isset($provider['class'])) {
                throw new InvalidConfigException('Provider '.$name.' configuration must be an array containing a "class" element.');
            }
            $methods = ArrayHelper::remove($provider,'methods');
            if(empty($methods) || $methods === null){
                $methods = [$name =>['class' => SimpleMethod::className()]];
            }
            /** @var  $provider BasePaymentProvider */
            $provider = Yii::createObject($provider);
            foreach ($methods as $mName => $method){
                if (!isset($method['class'])) {
                    throw new InvalidConfigException('Method '.$mName.' configuration must be an array containing a "class" element.');
                }
                if (isset($method['banks']) && ($banks = $method['banks']) !== null) {
                    //$this->setBanks($banks, $name);
                    unset($method['banks']);
                }
                /** @var  $method BasePaymentMethod */
                $method = Yii::createObject($method);
                $method->setOwner($provider);
                $results[$name] = $methods;
            }

        }
        return $results;

    }
}
